<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment;

use lujie\extend\constants\StatusConst;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\helpers\ClassHelper;
use lujie\fulfillment\constants\FulfillmentConst;
use lujie\fulfillment\events\FulfillmentOrderEvent;
use lujie\fulfillment\events\FulfillmentWarehouseStockEvent;
use lujie\fulfillment\forms\FulfillmentItemForm;
use lujie\fulfillment\forms\FulfillmentOrderForm;
use lujie\fulfillment\models\FulfillmentAccount;
use lujie\fulfillment\models\FulfillmentOrder;
use lujie\fulfillment\models\FulfillmentWarehouse;
use lujie\fulfillment\models\FulfillmentWarehouseStock;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\base\Event;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\base\ModelEvent;
use yii\db\AfterSaveEvent;
use yii\db\BaseActiveRecord;

/**
 * Class BaseFulfillmentConnector
 * @package lujie\fulfillment
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BaseFulfillmentConnector extends Component implements BootstrapInterface
{
    /**
     * @var string|BaseActiveRecord
     */
    public $itemClass;

    /**
     * [
     *      'fulfillment_type' => 'orderClass'
     * ]
     * @var array|string[]|BaseActiveRecord[]
     */
    public $orderClasses = [];

    /**
     * [
     *      'fulfillment_type' => 'status_attribute'
     * ]
     * @var array
     */
    public $orderStatusAttribute = [
        FulfillmentConst::FULFILLMENT_TYPE_SHIPPING => 'status',
        FulfillmentConst::FULFILLMENT_TYPE_INBOUND => 'status',
    ];

    /**
     * [
     *      'fulfillment_type' => 'warehouse_id_attribute'
     * ]
     * @var array
     */
    public $orderWarehouseIdAttribute = [
        FulfillmentConst::FULFILLMENT_TYPE_SHIPPING => 'warehouse_id',
        FulfillmentConst::FULFILLMENT_TYPE_INBOUND => 'warehouse_id',
    ];

    /**
     * [
     *      'fulfillment_type' => ['order_status' => 'fulfillment_status']
     * ]
     * @var array
     */
    public $fulfillmentStatusMap = [];

    /**
     * [
     *      'fulfillment_type' => ['fulfillment_status' => 'order_status']
     * ]
     * @var array
     */
    public $orderStatusMap = [];

    /**
     * @var array
     */
    public $allowedDeletedFulfillmentStatus = [
        FulfillmentConst::FULFILLMENT_TYPE_SHIPPING => [
            FulfillmentConst::FULFILLMENT_STATUS_PENDING,
            FulfillmentConst::FULFILLMENT_STATUS_CANCELLED,
        ],
        FulfillmentConst::FULFILLMENT_TYPE_INBOUND => [
            FulfillmentConst::INBOUND_STATUS_PENDING,
            FulfillmentConst::INBOUND_STATUS_CANCELLED,
        ]
    ];

    /**
     * @param \yii\base\Application $app
     * @inheritdoc
     */
    public function bootstrap($app): void
    {
        $itemFormClass = ClassHelper::getFormClass($this->itemClass) ?: $this->itemClass;
        Event::on($itemFormClass, BaseActiveRecord::EVENT_AFTER_INSERT, [$this, 'afterItemSaved']);
        Event::on($itemFormClass, BaseActiveRecord::EVENT_AFTER_UPDATE, [$this, 'afterItemSaved']);

        foreach ($this->orderClasses as $fulfillmentType => $orderClass) {
            $orderFormClass = ClassHelper::getFormClass($orderClass) ?: $orderClass;
            Event::on($orderFormClass, BaseActiveRecord::EVENT_BEFORE_DELETE, [$this, 'beforeOrderDeleted'], ['fulfillmentType' => $fulfillmentType]);
            Event::on($orderFormClass, BaseActiveRecord::EVENT_AFTER_DELETE, [$this, 'afterOrderDeleted'], ['fulfillmentType' => $fulfillmentType]);
            Event::on($orderFormClass, BaseActiveRecord::EVENT_AFTER_INSERT, [$this, 'afterOrderSaved'], ['fulfillmentType' => $fulfillmentType]);
            Event::on($orderFormClass, BaseActiveRecord::EVENT_AFTER_UPDATE, [$this, 'afterOrderSaved'], ['fulfillmentType' => $fulfillmentType]);
            Yii::info("Listen fulfillment $fulfillmentType of $orderFormClass events", __METHOD__);
        }

        Event::on(
            BaseFulfillmentService::class,
            BaseFulfillmentService::EVENT_AFTER_FULFILLMENT_ORDER_UPDATED,
            [$this, 'afterFulfillmentOrderUpdated'],
            null,
            false
        );
        Event::on(
            BaseFulfillmentService::class,
            BaseFulfillmentService::EVENT_AFTER_FULFILLMENT_WAREHOUSE_STOCKS_UPDATED,
            [$this, 'afterFulfillmentWarehouseStocksUpdated'],
            null,
            false
        );
    }

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if (empty($this->itemClass)) {
            throw new InvalidConfigException('The property `itemClass` must be set.');
        }
    }

    #region Item Trigger

    /**
     * @param AfterSaveEvent $event
     * @inheritdoc
     */
    public function afterItemSaved(AfterSaveEvent $event): void
    {
        /** @var BaseActiveRecord $item */
        $item = $event->sender;
        $accountIds = FulfillmentAccount::find()->active()->asArray()->column();
        foreach ($accountIds as $accountId) {
            if ($this->isItemNeedPush($item, $accountId)) {
                $this->updateFulfillmentItem($item, $accountId);
            }
        }
    }

    /**
     * @param BaseActiveRecord $item
     * @param int $accountId
     * @return bool
     * @inheritdoc
     */
    public function isItemNeedPush(BaseActiveRecord $item, int $accountId): bool
    {
        return true;
    }

    /**
     * @param BaseActiveRecord|TraceableBehaviorTrait $item
     * @param int $accountId
     * @return bool
     * @inheritdoc
     */
    public function updateFulfillmentItem(BaseActiveRecord $item, int $accountId): bool
    {
        $itemId = $item->primaryKey;
        $fulfillmentItem = FulfillmentItemForm::find()
            ->fulfillmentAccountId($accountId)
            ->itemId($itemId)
            ->one()
            ?: new FulfillmentItemForm();

        $fulfillmentItem->item_updated_at = $item->updated_at;
        $fulfillmentItem->fulfillment_account_id = $accountId;
        $fulfillmentItem->item_id = $itemId;
        return $fulfillmentItem->save(false);
    }

    #endregion

    #region Outbound Order Trigger

    /**
     * @param ModelEvent $event
     * @inheritdoc
     */
    public function beforeOrderDeleted(ModelEvent $event): void
    {
        $fulfillmentType = $event->data['fulfillmentType'] ?? '';
        /** @var BaseActiveRecord $order */
        $order = $event->sender;
        $orderId = $order->primaryKey;
        $fulfillmentOrder = FulfillmentOrderForm::find()->fulfillmentType($fulfillmentType)->orderId($orderId)->one();
        if ($fulfillmentOrder !== null && !in_array($fulfillmentOrder->fulfillment_status, $this->allowedDeletedFulfillmentStatus[$fulfillmentType], true)) {
            $order->addError('fulfillment_status', 'Fulfillment Status not allowed to delete');
            $event->isValid = false;
        }
    }

    /**
     * @param Event $event
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @inheritdoc
     */
    public function afterOrderDeleted(Event $event): void
    {
        $fulfillmentType = $event->data['fulfillmentType'] ?? '';
        /** @var BaseActiveRecord $order */
        $order = $event->sender;
        $orderId = $order->primaryKey;
        $fulfillmentOrders = FulfillmentOrderForm::find()->fulfillmentType($fulfillmentType)->orderId($orderId)->all();
        foreach ($fulfillmentOrders as $fulfillmentOrder) {
            if (in_array($fulfillmentOrder->fulfillment_status, $this->allowedDeletedFulfillmentStatus[$fulfillmentType], true)) {
                $fulfillmentOrder->delete();
            } else {
                throw new InvalidArgumentException('Fulfillment Status not allowed to delete');
            }
        }
    }

    /**
     * @param AfterSaveEvent $event
     * @inheritdoc
     */
    public function afterOrderSaved(AfterSaveEvent $event): void
    {
        $fulfillmentType = $event->data['fulfillmentType'] ?? '';
        /** @var BaseActiveRecord $order */
        $order = $event->sender;
        if (!$this->isOrderNeedPush($order, $fulfillmentType)) {
            return;
        }
        $this->updateFulfillmentOrder($order, $fulfillmentType);
    }

    /**
     * @param BaseActiveRecord $order
     * @param string $fulfillmentType
     * @return bool
     * @inheritdoc
     */
    public function isOrderNeedPush(BaseActiveRecord $order, string $fulfillmentType): bool
    {
        //SHIPPING Fulfillment auto push, others manually
        return $fulfillmentType === FulfillmentConst::FULFILLMENT_TYPE_SHIPPING;
    }

    /**
     * @param BaseActiveRecord|TraceableBehaviorTrait $order
     * @param string $fulfillmentType
     * @return FulfillmentOrderForm|null
     * @inheritdoc
     */
    public function updateFulfillmentOrder(BaseActiveRecord $order, string $fulfillmentType): ?FulfillmentOrderForm
    {
        $warehouseId = $order->getAttribute($this->orderWarehouseIdAttribute[$fulfillmentType]);
        if (empty($warehouseId)) {
            return null;
        }
        $fulfillmentWarehouse = FulfillmentWarehouse::find()->warehouseId($warehouseId)->one();
        if ($fulfillmentWarehouse === null) {
            return null;
        }
        $fulfillmentAccount = FulfillmentAccount::findOne($fulfillmentWarehouse->fulfillment_account_id);
        if ($fulfillmentAccount === null || $fulfillmentAccount->status === StatusConst::STATUS_INACTIVE) {
            return null;
        }

        $orderStatus = $order->getAttribute($this->orderStatusAttribute[$fulfillmentType]);
        $orderId = $order->primaryKey;
        /** @var FulfillmentOrderForm $fulfillmentOrder */
        $fulfillmentOrder = FulfillmentOrderForm::find()
            ->fulfillmentAccountId($fulfillmentWarehouse->fulfillment_account_id)
            ->fulfillmentType($fulfillmentType)
            ->orderId($orderId)
            ->warehouseId($warehouseId)
            ->one();
        if ($fulfillmentOrder === null) {
            $fulfillmentOrder = new FulfillmentOrderForm();
            $fulfillmentOrder->fulfillment_type = $fulfillmentType;
            $fulfillmentOrder->fulfillment_account_id = $fulfillmentWarehouse->fulfillment_account_id;
            $fulfillmentOrder->order_id = $orderId;
            $fulfillmentOrder->order_status = $orderStatus;
            $fulfillmentOrder->order_updated_at = $order->updated_at;
            $fulfillmentOrder->warehouse_id = $fulfillmentWarehouse->warehouse_id;
            $fulfillmentOrder->external_warehouse_key = $fulfillmentWarehouse->external_warehouse_key;
            $fulfillmentOrder->fulfillment_status = FulfillmentConst::FULFILLMENT_STATUS_PENDING;
            $fulfillmentOrder->save(false);
            return $fulfillmentOrder;
        }

        if (empty($this->fulfillmentStatusMap[$fulfillmentType][$orderStatus])) {
            return $fulfillmentOrder;
        }
        $fulfillmentOrder->order_status = $orderStatus;
        $fulfillmentOrder->order_updated_at = $order->updated_at;
        $fulfillmentOrder->fulfillment_status = $this->fulfillmentStatusMap[$fulfillmentType][$orderStatus];
        $fulfillmentOrder->save(false);
        return $fulfillmentOrder;
    }

    #endregion

    #region Fulfillment Order Trigger

    /**
     * @param FulfillmentOrderEvent $event
     * @inheritdoc
     */
    public function afterFulfillmentOrderUpdated(FulfillmentOrderEvent $event): void
    {
        $this->updateOrder($event->fulfillmentOrder, $event->externalOrder);
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @param array $externalOrder
     * @return BaseActiveRecord|null
     * @inheritdoc
     */
    public function updateOrder(FulfillmentOrder $fulfillmentOrder, array $externalOrder): ?BaseActiveRecord
    {
        $fulfillmentType = $fulfillmentOrder->fulfillment_type;
        if (empty($this->orderStatusMap[$fulfillmentType][$fulfillmentOrder->fulfillment_status])) {
            return null;
        }
        $orderClass = $this->orderClasses[$fulfillmentType];
        /** @var BaseActiveRecord $order */
        $order = $orderClass::findOne($fulfillmentOrder->order_id);
        if ($order === null) {
            return null;
        }
        $warehouseId = $order->getAttribute($this->orderWarehouseIdAttribute[$fulfillmentType]);
        if ($warehouseId !== $fulfillmentOrder->warehouse_id) {
            return $order;
        }

        $newOrderStatus = $this->orderStatusMap[$fulfillmentType][$fulfillmentOrder->fulfillment_status];
        $orderStatus = $order->getAttribute($this->orderStatusAttribute[$fulfillmentType]);
        if ($orderStatus === $newOrderStatus && $fulfillmentOrder->order_updated_at > $fulfillmentOrder->external_updated_at) {
            return $order;
        }
        $order->setAttribute($this->orderStatusAttribute[$fulfillmentType], $newOrderStatus);
        $fulfillmentOrder->order_status = $newOrderStatus;
        $fulfillmentOrder->order_updated_at = $fulfillmentOrder->updated_at;

        $this->updateOrderAdditional($order, $fulfillmentOrder, $externalOrder);
        $order->save(false) && $fulfillmentOrder->save(false);
        return $order;
    }

    /**
     * @param BaseActiveRecord $order
     * @param FulfillmentOrder $fulfillmentOrder
     * @param array $externalOrder
     * @inheritdoc
     */
    protected function updateOrderAdditional(BaseActiveRecord $order, FulfillmentOrder $fulfillmentOrder, array $externalOrder): void
    {
    }

    #endregion

    #region Fulfillment Warehouse Stock Trigger

    /**
     * @param FulfillmentWarehouseStockEvent $event
     * @inheritdoc
     */
    public function afterFulfillmentWarehouseStocksUpdated(FulfillmentWarehouseStockEvent $event): void
    {
        $this->updateWarehouseStocks($event->fulfillmentWarehouseStocks);
    }

    /**
     * @param FulfillmentWarehouseStock[] $fulfillmentWarehosueStocks
     * @inheritdoc
     */
    protected function updateWarehouseStocks(array $fulfillmentWarehouseStocks): void
    {
    }

    #endregion
}

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
use lujie\fulfillment\forms\FulfillmentItemForm;
use lujie\fulfillment\forms\FulfillmentOrderForm;
use lujie\fulfillment\models\FulfillmentAccount;
use lujie\fulfillment\models\FulfillmentOrder;
use lujie\fulfillment\models\FulfillmentWarehouse;
use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\base\Event;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\base\ModelEvent;
use yii\db\AfterSaveEvent;
use yii\db\BaseActiveRecord;

/**
 * Class FulfillmentConnectService
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
     * @var string|BaseActiveRecord
     */
    public $outboundOrderClass;

    /**
     * @var string
     */
    public $outboundOrderStatusAttribute = 'status';

    /**
     * @var string
     */
    public $outboundOrderWarehouseIdAttribute = 'warehouse_id';

    /**
     * [
     *      'order_status' => 'fulfillment_status'
     * ]
     * @var array
     */
    public $fulfillmentStatusMap = [];

    /**
     * [
     *      'fulfillment_status' => 'order_status'
     * ]
     * @var array
     */
    public $orderStatusMap = [];

    /**
     * @var array
     */
    public $allowedDeletedFulfillmentStatus = [
        FulfillmentConst::FULFILLMENT_STATUS_PENDING,
        FulfillmentConst::FULFILLMENT_STATUS_CANCELLED,
    ];

    /**
     * @param \yii\base\Application $app
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $itemFormClass = ClassHelper::getFormClass($this->itemClass) ?: $this->itemClass;
        Event::on($itemFormClass, BaseActiveRecord::EVENT_AFTER_INSERT, [$this, 'afterItemSaved']);
        Event::on($itemFormClass, BaseActiveRecord::EVENT_AFTER_UPDATE, [$this, 'afterItemSaved']);

        $outboundOrderFormClass = ClassHelper::getFormClass($this->outboundOrderClass) ?: $this->outboundOrderClass;
        Event::on($outboundOrderFormClass, BaseActiveRecord::EVENT_BEFORE_DELETE, [$this, 'beforeOutboundOrderDeleted']);
        Event::on($outboundOrderFormClass, BaseActiveRecord::EVENT_AFTER_DELETE, [$this, 'afterOutboundOrderDeleted']);
        Event::on($outboundOrderFormClass, BaseActiveRecord::EVENT_AFTER_INSERT, [$this, 'afterOutboundOrderSaved']);
        Event::on($outboundOrderFormClass, BaseActiveRecord::EVENT_AFTER_UPDATE, [$this, 'afterOutboundOrderSaved']);

        Event::on(BaseFulfillmentService::class, BaseFulfillmentService::EVENT_AFTER_FULFILLMENT_ORDER_UPDATED, [$this, 'afterFulfillmentOrderUpdated'], null, false);
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
        if (empty($this->outboundOrderClass)) {
            throw new InvalidConfigException('The property `outboundOrderClass` must be set.');
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
    public function beforeOutboundOrderDeleted(ModelEvent $event): void
    {
        /** @var BaseActiveRecord $outboundOrder */
        $outboundOrder = $event->sender;
        $orderId = $outboundOrder->primaryKey;
        $fulfillmentOrder = FulfillmentOrderForm::find()->orderId($orderId)->one();
        if ($fulfillmentOrder !== null && !in_array($fulfillmentOrder->fulfillment_status, $this->allowedDeletedFulfillmentStatus)) {
            $outboundOrder->addError('fulfillment_status', 'Fulfillment Status not allowed to delete');
            $event->isValid = false;
        }
    }

    /**
     * @param Event $event
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @inheritdoc
     */
    public function afterOutboundOrderDeleted(Event $event): void
    {
        /** @var BaseActiveRecord $outboundOrder */
        $outboundOrder = $event->sender;
        $orderId = $outboundOrder->primaryKey;
        $fulfillmentOrder = FulfillmentOrderForm::find()->orderId($orderId)->one();
        if ($fulfillmentOrder !== null) {
            if (in_array($fulfillmentOrder->fulfillment_status, $this->allowedDeletedFulfillmentStatus)) {
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
    public function afterOutboundOrderSaved(AfterSaveEvent $event): void
    {
        /** @var BaseActiveRecord $outboundOrder */
        $outboundOrder = $event->sender;
        if (!$this->isOrderNeedPush($outboundOrder)) {
            return;
        }
        $this->updateFulfillmentOrder($outboundOrder);
    }

    /**
     * @param BaseActiveRecord $outboundOrder
     * @return bool
     * @inheritdoc
     */
    public function isOrderNeedPush(BaseActiveRecord $outboundOrder): bool
    {
        return true;
    }

    /**
     * @param BaseActiveRecord|TraceableBehaviorTrait $outboundOrder
     * @return bool|null
     * @inheritdoc
     */
    public function updateFulfillmentOrder(BaseActiveRecord $outboundOrder): ?bool
    {
        $warehouseId = $outboundOrder->getAttribute($this->outboundOrderWarehouseIdAttribute);
        $fulfillmentWarehouse = FulfillmentWarehouse::find()->warehouseId($warehouseId)->one();
        if ($fulfillmentWarehouse === null) {
            return null;
        }
        $fulfillmentAccount = FulfillmentAccount::findOne($fulfillmentWarehouse->fulfillment_account_id);
        if ($fulfillmentAccount === null || $fulfillmentAccount->status === StatusConst::STATUS_INACTIVE) {
            return null;
        }

        $orderStatus = $outboundOrder->getAttribute($this->outboundOrderStatusAttribute);
        $orderId = $outboundOrder->primaryKey;
        $fulfillmentOrder = FulfillmentOrderForm::find()
            ->fulfillmentAccountId($fulfillmentWarehouse->fulfillment_account_id)
            ->orderId($orderId)
            ->one();
        if ($fulfillmentOrder === null) {
            $fulfillmentOrder = new FulfillmentOrderForm();
            $fulfillmentOrder->fulfillment_account_id = $fulfillmentWarehouse->fulfillment_account_id;
            $fulfillmentOrder->order_id = $orderId;
            $fulfillmentOrder->order_status = $orderStatus;
            $fulfillmentOrder->order_updated_at = $outboundOrder->updated_at;
            $fulfillmentOrder->fulfillment_status = FulfillmentConst::FULFILLMENT_STATUS_PENDING;
            return $fulfillmentOrder->save(false);
        }

        if (empty($this->fulfillmentStatusMap[$orderStatus])) {
            return null;
        }
        $fulfillmentOrder->order_status = $orderStatus;
        $fulfillmentOrder->order_updated_at = $outboundOrder->updated_at;
        $fulfillmentOrder->fulfillment_status = $this->fulfillmentStatusMap[$orderStatus];
        return $fulfillmentOrder->save(false);
    }

    #endregion

    #region Fulfillment Order Trigger

    /**
     * @param FulfillmentOrderEvent $event
     * @inheritdoc
     */
    public function afterFulfillmentOrderUpdated(FulfillmentOrderEvent $event): void
    {
        $this->updateOutboundOrder($event->fulfillmentOrder, $event->externalOrder);
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool|null
     * @inheritdoc
     */
    public function updateOutboundOrder(FulfillmentOrder $fulfillmentOrder, array $externalOrder): ?bool
    {
        if (empty($this->orderStatusMap[$fulfillmentOrder->fulfillment_status])) {
            return null;
        }
        $outboundOrder = $this->outboundOrderClass::findOne($fulfillmentOrder->order_id);
        if ($outboundOrder === null) {
            return null;
        }

        $newOrderStatus = $this->orderStatusMap[$fulfillmentOrder->fulfillment_status];
        $outboundOrder->setAttribute($this->outboundOrderStatusAttribute, $newOrderStatus);
        $fulfillmentOrder->order_status = $newOrderStatus;

        $this->updateOutboundOrderAdditional($outboundOrder, $fulfillmentOrder, $externalOrder);
        return $outboundOrder->save(false) && $fulfillmentOrder->save(false);
    }

    /**
     * @param BaseActiveRecord $outboundOrder
     * @param FulfillmentOrder $fulfillmentOrder
     * @param array $externalOrder
     * @inheritdoc
     */
    protected function updateOutboundOrderAdditional(BaseActiveRecord $outboundOrder, FulfillmentOrder $fulfillmentOrder, array $externalOrder): void
    {

    }

    #endregion
}
<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel;

use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\helpers\ClassHelper;
use lujie\sales\channel\constants\SalesChannelConst;
use lujie\sales\channel\events\SalesChannelOrderEvent;
use lujie\sales\channel\forms\SalesChannelOrderForm;
use lujie\sales\channel\models\SalesChannelOrder;
use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\db\AfterSaveEvent;
use yii\db\BaseActiveRecord;

/**
 * Class BaseSalesChannelConnector
 * @package lujie\sales\channel
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseSalesChannelConnector extends Component implements BootstrapInterface
{
    /**
     * @var string|BaseActiveRecord
     */
    public $orderClass;

    /**
     * @var string
     */
    public $orderStatusAttribute = 'status';

    /**
     * [
     *      'order_status' => 'sales_channel_status'
     * ]
     * @var array
     */
    public $salesChannelStatusMap = [];

    /**
     * [
     *      'sales_channel_status' => 'order_status'
     * ]
     * @var array
     */
    public $orderStatusMap = [];

    /**
     * @param \yii\base\Application $app
     * @inheritdoc
     */
    public function bootstrap($app): void
    {
        $orderFormClass = ClassHelper::getFormClass($this->orderClass) ?: $this->orderClass;
        Event::on($orderFormClass, BaseActiveRecord::EVENT_AFTER_INSERT, [$this, 'afterOrderSaved']);
        Event::on($orderFormClass, BaseActiveRecord::EVENT_AFTER_UPDATE, [$this, 'afterOrderSaved']);

        Event::on(
            BaseSalesChannel::class,
            BaseSalesChannel::EVENT_AFTER_SALES_CHANNEL_ORDER_UPDATED,
            [$this, 'afterSalesChannelOrderUpdate'],
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
        if (empty($this->orderClass)) {
            throw new InvalidConfigException('The property `orderClass` must be set.');
        }
    }

    #region Outbound Order Trigger

    /**
     * @param AfterSaveEvent $event
     * @inheritdoc
     */
    public function afterOrderSaved(AfterSaveEvent $event): void
    {
        /** @var BaseActiveRecord $outboundOrder */
        $outboundOrder = $event->sender;
        if (!$this->isOrderNeedPush($outboundOrder)) {
            return;
        }
        $this->updateSalesChannelOrder($outboundOrder);
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
     * @param BaseActiveRecord|TraceableBehaviorTrait $order
     * @return bool|null
     * @inheritdoc
     */
    public function updateSalesChannelOrder(BaseActiveRecord $order): ?bool
    {
        $orderStatus = $order->getAttribute($this->orderStatusAttribute);
        if (empty($this->salesChannelStatusMap[$orderStatus])) {
            return null;
        }
        $orderId = $order->primaryKey;
        $salesChannelOrder = SalesChannelOrderForm::find()->orderId($orderId)->one();
        if ($salesChannelOrder === null) {
            return null;
        }
        $finishedStatus = [SalesChannelConst::CHANNEL_STATUS_SHIPPED, SalesChannelConst::CHANNEL_STATUS_CANCELLED];
        if (in_array($salesChannelOrder->sales_channel_status, $finishedStatus, true)) {
            return null;
        }
        $salesChannelOrder->order_status = $orderStatus;
        $salesChannelOrder->order_updated_at = $order->updated_at;
        $salesChannelOrder->sales_channel_status = $this->salesChannelStatusMap[$orderStatus];
        $this->updateSalesChannelOrderAdditional($salesChannelOrder, $order);
        return $salesChannelOrder->save(false);
    }

    /**
     * @param SalesChannelOrder $salesChannelOrder
     * @param BaseActiveRecord $outboundOrder
     * @inheritdoc
     */
    protected function updateSalesChannelOrderAdditional(SalesChannelOrder $salesChannelOrder, BaseActiveRecord $outboundOrder): void
    {
    }

    #endregion

    #region Sales Channel Order Trigger

    /**
     * @param SalesChannelOrderEvent $event
     * @inheritdoc
     */
    public function afterSalesChannelOrderUpdate(SalesChannelOrderEvent $event): void
    {
        $this->updateOrder($event->salesChannelOrder, $event->externalOrder);
    }

    /**
     * @param SalesChannelOrder $salesChannelOrder
     * @param array $externalOrder
     * @return BaseActiveRecord|null
     * @inheritdoc
     */
    public function updateOrder(SalesChannelOrder $salesChannelOrder, array $externalOrder): ?BaseActiveRecord
    {
        /** @var BaseActiveRecord $order */
        $order = $salesChannelOrder->order_id ? $this->orderClass::findOne($salesChannelOrder->order_id) : null;
        if ($order === null) {
            $order = $this->createOrder($salesChannelOrder, $externalOrder);
            if ($order === null) {
                return null;
            }
            if (!$order->save(false)) {
                return null;
            }
            $salesChannelOrder->order_id = $order->primaryKey;
            $salesChannelOrder->order_status = $order->getAttribute($this->orderStatusAttribute);
            $salesChannelOrder->order_updated_at = time();
            $salesChannelOrder->save(false);
        }

        if (empty($this->orderStatusMap[$salesChannelOrder->sales_channel_status])) {
            return $order;
        }
        $newOrderStatus = $this->orderStatusMap[$salesChannelOrder->sales_channel_status];
        $orderStatus = $order->getAttribute($this->orderStatusAttribute);
        if ($orderStatus === $newOrderStatus) {
            return $order;
        }
        $order->setAttribute($this->orderStatusAttribute, $newOrderStatus);
        $salesChannelOrder->order_status = $newOrderStatus;
        $salesChannelOrder->order_updated_at = time();

        $this->updateOrderAdditional($order, $salesChannelOrder, $externalOrder);
        $order->save(false) && $salesChannelOrder->save(false);
        return $order;
    }

    /**
     * @param BaseActiveRecord $outboundOrder
     * @param SalesChannelOrder $salesChannelOrder
     * @param array $externalOrder
     * @inheritdoc
     */
    protected function updateOrderAdditional(BaseActiveRecord $outboundOrder, SalesChannelOrder $salesChannelOrder, array $externalOrder): void
    {
    }

    /**
     * @param SalesChannelOrder $salesChannelOrder
     * @param array $externalOrder
     * @return BaseActiveRecord|null
     * @inheritdoc
     */
    abstract protected function createOrder(SalesChannelOrder $salesChannelOrder, array $externalOrder): ?BaseActiveRecord;

    #endregion
}

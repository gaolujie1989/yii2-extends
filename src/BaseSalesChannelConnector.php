<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel;


use lujie\extend\db\TraceableBehaviorTrait;
use lujie\sales\channel\events\SalesChannelOrderEvent;
use lujie\sales\channel\models\SalesChannelOrder;
use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\base\Event;
use yii\db\AfterSaveEvent;
use yii\db\BaseActiveRecord;

/**
 * Class SalesChannelConnect
 * @package lujie\sales\channel
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseSalesChannelConnector extends Component implements BootstrapInterface
{
    /**
     * @var BaseActiveRecord
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
    public function bootstrap($app)
    {
        Event::on($this->outboundOrderClass, BaseActiveRecord::EVENT_AFTER_INSERT, [$this, 'afterOutboundOrderSaved']);
        Event::on($this->outboundOrderClass, BaseActiveRecord::EVENT_AFTER_UPDATE, [$this, 'afterOutboundOrderSaved']);

        Event::on(BaseSalesChannel::class, BaseSalesChannel::EVENT_AFTER_SALES_CHANNEL_ORDER_UPDATED, [$this, 'afterSalesChannelOrderUpdate']);
    }

    #region Outbound Order Trigger

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
        $this->updateSalesChannelOrder($outboundOrder);
    }

    /**
     * @param BaseActiveRecord $outboundOrder
     * @return bool
     * @inheritdoc
     */
    abstract public function isOrderNeedPush(BaseActiveRecord $outboundOrder): bool;

    /**
     * @param BaseActiveRecord|TraceableBehaviorTrait $outboundOrder
     * @return bool|null
     * @inheritdoc
     */
    public function updateSalesChannelOrder(BaseActiveRecord $outboundOrder): ?bool
    {
        $orderId = $outboundOrder->primaryKey;
        $salesChannelOrder = SalesChannelOrder::find()->orderId($orderId)->one();
        if ($salesChannelOrder === null) {
            return null;
        }

        $orderStatus = $outboundOrder->getAttribute($this->outboundOrderStatusAttribute);
        if (empty($this->salesChannelStatusMap[$orderStatus])) {
            return null;
        }

        $salesChannelOrder->order_status = $orderStatus;
        $salesChannelOrder->order_updated_at = $outboundOrder->updated_at;
        $salesChannelOrder->sales_channel_status = $this->salesChannelStatusMap[$orderStatus];
        return $salesChannelOrder->save(false);
    }

    #endregion

    #region Fulfillment Order Trigger

    /**
     * @param SalesChannelOrderEvent $event
     * @inheritdoc
     */
    public function afterSalesChannelOrderUpdate(SalesChannelOrderEvent $event): void
    {
        $this->updateOutboundOrder($event->salesChannelOrder, $event->externalOrder);
    }

    /**
     * @param SalesChannelOrder $salesChannelOrder
     * @param array $externalOrder
     * @return bool|null
     * @inheritdoc
     */
    public function updateOutboundOrder(SalesChannelOrder $salesChannelOrder, array $externalOrder): ?bool
    {
        $outboundOrder = $this->outboundOrderClass::findOne($salesChannelOrder->order_id);
        if ($outboundOrder === null) {
            return null;
        }
        if (empty($this->orderStatusMap[$salesChannelOrder->sales_channel_status])) {
            return null;
        }

        $orderStatus = $this->orderStatusMap[$salesChannelOrder->sales_channel_status];
        $outboundOrder->setAttribute($this->outboundOrderStatusAttribute, $orderStatus);
        $this->updateOutboundOrderAdditional($outboundOrder, $salesChannelOrder);
        if ($outboundOrder->save(false)) {
            //skip trigger event
            $salesChannelOrder->updateAttributes(['order_status' => $orderStatus]);
            return true;
        }
        return false;
    }

    /**
     * @param BaseActiveRecord $outboundOrder
     * @param SalesChannelOrder $salesChannelOrder
     * @return mixed
     * @inheritdoc
     */
    abstract protected function updateOutboundOrderAdditional(BaseActiveRecord $outboundOrder, SalesChannelOrder $salesChannelOrder);

    #endregion
}
<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel;


use lujie\extend\db\TraceableBehaviorTrait;
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
 * Class SalesChannelConnect
 * @package lujie\sales\channel
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseSalesChannelConnector extends Component implements BootstrapInterface
{
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

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if (empty($this->outboundOrderClass)) {
            throw new InvalidConfigException('The property `outboundOrderClass` must be set.');
        }
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
        $orderStatus = $outboundOrder->getAttribute($this->outboundOrderStatusAttribute);
        if (empty($this->salesChannelStatusMap[$orderStatus])) {
            return null;
        }
        $orderId = $outboundOrder->primaryKey;
        $salesChannelOrder = SalesChannelOrderForm::find()->orderId($orderId)->one();
        if ($salesChannelOrder === null) {
            return null;
        }
        if (in_array($salesChannelOrder->sales_channel_status, [SalesChannelConst::CHANNEL_STATUS_SHIPPED, SalesChannelConst::CHANNEL_STATUS_CANCELLED])) {
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
            $outboundOrder = $this->createOutboundOrder($salesChannelOrder, $externalOrder);
            if (!$outboundOrder->save(false)) {
                return false;
            }
            $salesChannelOrder->order_id = $outboundOrder->primaryKey;
            $salesChannelOrder->order_status = $outboundOrder->getAttribute($this->outboundOrderStatusAttribute);
            $salesChannelOrder->save(false);
        }

        if (empty($this->orderStatusMap[$salesChannelOrder->sales_channel_status])) {
            return null;
        }
        $newOrderStatus = $this->orderStatusMap[$salesChannelOrder->sales_channel_status];
        $outboundOrder->setAttribute($this->outboundOrderStatusAttribute, $newOrderStatus);
        $salesChannelOrder->order_status = $newOrderStatus;

        $this->updateOutboundOrderAdditional($outboundOrder, $salesChannelOrder, $externalOrder);
        return $outboundOrder->save(false) && $salesChannelOrder->save(false);
    }

    /**
     * @param BaseActiveRecord $outboundOrder
     * @param SalesChannelOrder $salesChannelOrder
     * @return mixed
     * @inheritdoc
     */
    abstract protected function updateOutboundOrderAdditional(BaseActiveRecord $outboundOrder, SalesChannelOrder $salesChannelOrder, array $externalOrder);

    /**
     * @param SalesChannelOrder $salesChannelOrder
     * @param array $externalOrder
     * @return mixed
     * @inheritdoc
     */
    abstract protected function createOutboundOrder(SalesChannelOrder $salesChannelOrder, array $externalOrder): BaseActiveRecord;

    #endregion
}
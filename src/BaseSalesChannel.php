<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel;


use lujie\extend\helpers\TransactionHelper;
use lujie\sales\channel\constants\SalesChannelConst;
use lujie\sales\channel\events\SalesChannelOrderEvent;
use lujie\sales\channel\models\SalesChannelAccount;
use lujie\sales\channel\models\SalesChannelOrder;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

/**
 * Class BaseSalesChannel
 * @package lujie\sales\channel
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseSalesChannel extends Component implements SalesChannelInterface
{
    public const EVENT_AFTER_SALES_CHANNEL_ORDER_UPDATED = 'SALES_CHANNEL_ORDER_UPDATED';

    /**
     * @var SalesChannelAccount
     */
    public $account;

    #region External Model Key Field

    /**
     * @var string
     */
    public $externalOrderKeyField = 'id';

    /**
     * @var string
     */
    public $externalOrderStatusField = 'status';

    /**
     * [
     *      'external_order_status' => 'sales_channel_status'
     * ]
     * @var array
     */
    public $salesChannelStatusMap = [
        'wait_payment' => SalesChannelConst::CHANNEL_STATUS_WAIT_PAYMENT,
        'paid' => SalesChannelConst::CHANNEL_STATUS_PAID,
        'shipped' => SalesChannelConst::CHANNEL_STATUS_SHIPPED,
        'cancelled' => SalesChannelConst::CHANNEL_STATUS_CANCELLED,
    ];

    #endregion

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if ($this->account === null || !($this->account instanceof SalesChannelAccount)) {
            throw new InvalidConfigException('The property `account` can not be null and must be SalesChannelAccount');
        }
    }

    #region Order Pull

    /**
     * @param array $salesChannelOrders
     * @inheritdoc
     */
    public function pullSalesOrders(array $salesChannelOrders): void
    {
        $salesChannelOrders = ArrayHelper::index($salesChannelOrders, 'external_order_key');
        $externalOrderKeys = array_keys($salesChannelOrders);
        $externalOrders = $this->getExternalOrders($externalOrderKeys);
        foreach ($externalOrders as $externalOrder) {
            $externalOrderKey = $externalOrder[$this->externalOrderKeyField];
            $this->updateSalesChannelOrder($salesChannelOrders[$externalOrderKey], $externalOrder);
        }
    }

    /**
     * @return array
     * @inheritdoc
     */
    abstract protected function getExternalOrders(array $externalOrderKeys): array;

    /**
     * @param int $createdAtFrom
     * @param int $createdAtTo
     * @inheritdoc
     */
    public function pullNewSalesOrders(int $createdAtFrom,int $createdAtTo): void
    {
        $externalOrders = $this->getNewExternalOrders($createdAtFrom, $createdAtTo);
        if (empty($externalOrders)) {
            return;
        }
        $externalOrders = ArrayHelper::index($externalOrders, $this->externalOrderKeyField);
        $externalOrderKeys = array_keys($externalOrders);
        $salesChannelOrders = SalesChannelOrder::find()
            ->salesChannelAccountId($this->account->account_id)
            ->externalOrderKey($externalOrderKeys)
            ->indexBy('external_order_key')
            ->all();
        foreach ($externalOrders as $externalOrderKey => $externalOrder) {
            $salesChannelOrder = $salesChannelOrders[$externalOrderKey] ?? new SalesChannelOrder();
            if ($salesChannelOrder->getIsNewRecord()) {
                $salesChannelOrder->sales_channel_account_id = $this->account->account_id;
            }
            $this->updateSalesChannelOrder($salesChannelOrder, $externalOrder);
        }
    }

    /**
     * @param int $createdAtFrom
     * @param int $createdAtTo
     * @return array
     * @inheritdoc
     */
    abstract protected function getNewExternalOrders(int $createdAtFrom, int $createdAtTo): array;

    /**
     * update sales channel order info, like external order_id, order_no, extra...
     * @param SalesChannelOrder $salesChannelOrder
     * @param array $externalOrder
     * @return bool
     * @throws InvalidConfigException
     * @throws \Throwable
     * @inheritdoc
     */
    protected function updateSalesChannelOrder(SalesChannelOrder $salesChannelOrder, array $externalOrder): bool
    {
        $salesChannelOrder->order_pulled_at = time();
        $salesChannelOrder->external_order_key = $externalOrder[$this->externalOrderKeyField];
        $salesChannelOrder->external_order_status = $externalOrder[$this->externalOrderStatusField];

        $newSalesChannelStatus = $this->salesChannelStatusMap[$salesChannelOrder->external_order_status] ?? null;
        if ($newSalesChannelStatus) {
            $salesChannelOrder->sales_channel_status = $newSalesChannelStatus;
        }
        $this->updateSalesChannelOrderAdditional($salesChannelOrder, $externalOrder);
        return SalesChannelOrder::getDb()->transaction(function() use ($salesChannelOrder, $externalOrder) {
            if ($salesChannelOrder->save(false)) {
                $this->triggerSalesChannelOrderEvent($salesChannelOrder, $externalOrder);
                return true;
            }
            return false;
        });
    }

    /**
     * @param SalesChannelOrder $salesChannelOrder
     * @param array $externalOrder
     * @inheritdoc
     */
    abstract protected function updateSalesChannelOrderAdditional(SalesChannelOrder $salesChannelOrder, array $externalOrder): void;

    /**
     * @param SalesChannelOrder $salesChannelOrder
     * @param array $externalOrder
     * @inheritdoc
     */
    protected function triggerSalesChannelOrderEvent(SalesChannelOrder $salesChannelOrder, array $externalOrder): void
    {
        $event = new SalesChannelOrderEvent();
        $event->salesChannelOrder = $salesChannelOrder;
        $event->externalOrder = $externalOrder;
        $this->trigger(self::EVENT_AFTER_SALES_CHANNEL_ORDER_UPDATED, $event);
    }

    #endregion

    #region Order Action ship/cancel

    /**
     * @param SalesChannelOrder $channelOrder
     * @return bool
     * @inheritdoc
     */
    abstract public function shipSalesOrder(SalesChannelOrder $channelOrder): bool;

    /**
     * @param SalesChannelOrder $channelOrder
     * @return bool
     * @inheritdoc
     */
    abstract public function cancelSalesOrder(SalesChannelOrder $channelOrder): bool;

    #endregion

    #region Stock Push

    public function pushStocks(): void
    {
        // TODO: Implement pushStocks() method.
    }

    #endregion

    #region Price Push

    public function pushPrices(): void
    {
        // TODO: Implement pushPrices() method.
    }

    #endregion

    #region Product Push

    public function pushProducts(): void
    {
        // TODO: Implement pushProducts() method.
    }

    #endregion
}
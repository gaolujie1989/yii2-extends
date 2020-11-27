<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel;


use lujie\data\loader\DataLoaderInterface;
use lujie\sales\channel\models\SalesChannelAccount;
use lujie\sales\channel\models\SalesChannelOrder;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * Class BaseSalesChannel
 * @package lujie\sales\channel
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseSalesChannel extends BaseObject implements SalesChannelInterface
{
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
    public $salesChannelStatusMap = [];

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
        return $salesChannelOrder->save(false);
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
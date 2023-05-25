<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\sales\channel\channels\ebay;

use lujie\ebay\EbayRestClient;
use lujie\sales\channel\BaseSalesChannel;
use lujie\sales\channel\constants\SalesChannelConst;
use lujie\sales\channel\models\SalesChannelItem;
use lujie\sales\channel\models\SalesChannelOrder;

/**
 * Class EbaySalesChannel
 * @package lujie\sales\channel\channels\ebay
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class EbaySalesChannel extends BaseSalesChannel
{
    /**
     * @var EbayRestClient
     */
    public $client;

    /**
     * @var string
     */
    public $externalOrderKeyField = 'orderId';

    /**
     * @var string
     */
    public $externalOrderStatusField = 'orderPaymentStatus';

    /**
     * @param array $externalOrderKeys
     * @return array
     * @inheritdoc
     */
    protected function getExternalOrders(array $externalOrderKeys): array
    {
        $eachOrders = $this->client->eachOrders([
            'orderIds' => implode(',', $externalOrderKeys),
            'limit' => 200,
        ]);
        return iterator_to_array($eachOrders, false);
    }

    /**
     * @param int $createdAtFrom
     * @param int $createdAtTo
     * @return array
     * @inheritdoc
     */
    protected function getNewExternalOrders(int $createdAtFrom, int $createdAtTo): array
    {
        $createdTimeFrom = gmdate('Y-m-d\TH:i:s\Z', $createdAtFrom);
        $createdTimeTo = gmdate('Y-m-d\TH:i:s\Z', $createdAtTo);
        $createdEachOrders = $this->client->eachOrders([
            'filter' => "creationdate:[{$createdTimeFrom}..{$createdTimeTo}]",
            'limit' => 200,
        ]);
        return iterator_to_array($createdEachOrders, false);
//
//        $createdTimeFrom = gmdate('Y-m-d\TH:i:s\Z', $createdAtFrom);
//        $createdTimeTo = gmdate('Y-m-d\TH:i:s\Z', $createdAtTo);
//        $updatedEachOrders = $this->client->eachOrders([
//            'filter' => "lastmodifieddate:[{$createdTimeFrom}..{$createdTimeTo}]",
//            'limit' => 200,
//        ]);
//        return array_merge(
//            iterator_to_array($createdEachOrders, false),
//            iterator_to_array($updatedEachOrders, false)
//        );
    }

    /**
     * @param string $externalOrderKey
     * @return array|null
     * @inheritdoc
     */
    protected function getExternalOrder(string $externalOrderKey): ?array
    {
        return $this->client->getOrder(['id' => $externalOrderKey]);
    }

    /**
     * @param array $externalOrder
     * @param SalesChannelOrder $salesChannelOrder
     * @return array|null
     * @inheritdoc
     */
    protected function saveExternalOrder(array $externalOrder, SalesChannelOrder $salesChannelOrder): ?array
    {
        if ($salesChannelOrder->sales_channel_status === SalesChannelConst::CHANNEL_STATUS_TO_SHIPPED) {
            $this->client->shipOrder($externalOrder);
            return $this->getExternalOrder($salesChannelOrder->external_order_key);
        }
        return null;
    }

    /**
     * @param SalesChannelOrder $salesChannelOrder
     * @param array $externalOrder
     * @param bool $changeActionStatus
     * @return bool
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function updateSalesChannelOrder(SalesChannelOrder $salesChannelOrder, array $externalOrder, bool $changeActionStatus = false): bool
    {
        $salesChannelOrder->external_created_at = strtotime($externalOrder['creationDate']);
        $salesChannelOrder->external_updated_at = strtotime($externalOrder['lastModifiedDate']);
        return parent::updateSalesChannelOrder($salesChannelOrder, $externalOrder, $changeActionStatus);
    }

    /**
     * @param array $externalOrder
     * @return int|null
     * @inheritdoc
     */
    protected function getSalesChannelStatus(array $externalOrder): ?int
    {
        $cancelState = $externalOrder['cancelStatus']['cancelState'];
        if ($cancelState === 'CANCELLED') {
            return SalesChannelConst::CHANNEL_STATUS_CANCELLED;
        }
        if ($cancelState === 'IN_PROGRESS') {
            return SalesChannelConst::CHANNEL_STATUS_PENDING;
        }
        if ($externalOrder['orderFulfillmentStatus'] === 'FULFILLED') {
            return SalesChannelConst::CHANNEL_STATUS_SHIPPED;
        }
        if ($externalOrder['orderPaymentStatus'] === 'PAID') {
            return SalesChannelConst::CHANNEL_STATUS_PAID;
        }
        return SalesChannelConst::CHANNEL_STATUS_WAIT_PAYMENT;
    }

    /**
     * @param array $externalItem
     * @return array|null
     * @inheritdoc
     */
    protected function getExternalItem(array $externalItem): ?array
    {
        // TODO: Implement getExternalItem() method.
    }

    protected function saveExternalItem(array $externalItem, SalesChannelItem $salesChannelItem): ?array
    {
        // TODO: Implement saveExternalItem() method.
    }

    protected function saveExternalItemStocks(array $externalItemStocks): ?array
    {
        // TODO: Implement saveExternalItemStocks() method.
    }

}

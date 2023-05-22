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
        return iterator_to_array($eachOrders);
    }

    /**
     * @param int $createdAtFrom
     * @param int $createdAtTo
     * @return array
     * @inheritdoc
     */
    protected function getNewExternalOrders(int $createdAtFrom, int $createdAtTo): array
    {
        $createdTimeFrom = date('c', $createdAtFrom);
        $createdTimeTo = date('c', $createdAtTo);
        $eachOrders = $this->client->eachOrders([
            'filter' => "creationdate:[{$createdTimeFrom}..{$createdTimeTo}]",
            'limit' => 200,
        ]);
        return iterator_to_array($eachOrders);
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

    protected function saveExternalOrder(array $externalOrder, SalesChannelOrder $salesChannelOrder): ?array
    {
        if ($salesChannelOrder->sales_channel_status === SalesChannelConst::CHANNEL_STATUS_TO_SHIPPED) {
            $shipOrder = $this->client->shipOrder($externalOrder);
        }
        return null;
    }

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

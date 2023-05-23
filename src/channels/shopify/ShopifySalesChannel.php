<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\channels\shopify;

use lujie\sales\channel\BaseSalesChannel;
use lujie\sales\channel\constants\SalesChannelConst;
use lujie\sales\channel\models\SalesChannelItem;
use lujie\sales\channel\models\SalesChannelOrder;
use lujie\shopify\ShopifyAdminRestClient;
use yii\base\InvalidConfigException;
use yii\di\Instance;

/**
 * Class AmazonSalesChannel
 * @package lujie\sales\channel\channels\amazon
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShopifySalesChannel extends BaseSalesChannel
{
    /**
     * @var ShopifyAdminRestClient
     */
    public $client;

    public $allowedCancelled = true;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->client = Instance::ensure($this->client, ShopifyAdminRestClient::class);
    }

    /**
     * @param array $externalOrderKeys
     * @return array
     * @inheritdoc
     */
    protected function getExternalOrders(array $externalOrderKeys): array
    {
        $eachOrders = $this->client->eachOrders(['ids' => implode(',', $externalOrderKeys)]);
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
        $eachOrders = $this->client->eachOrders([
            'created_at_min' => date('c', $createdAtFrom),
            'created_at_max' => date('c', $createdAtTo)
        ]);
        return iterator_to_array($eachOrders, false);
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
        if ($salesChannelOrder->sales_channel_status === SalesChannelConst::CHANNEL_STATUS_TO_CANCELLED) {
            if ($this->allowedCancelled) {
                return $this->client->cancelOrder([]);
            }
            return null;
        }
        if ($salesChannelOrder->sales_channel_status === SalesChannelConst::CHANNEL_STATUS_TO_SHIPPED) {

        }
        return null;
    }

    /**
     * @param array $externalItem
     * @return array|null
     * @inheritdoc
     */
    protected function getExternalItem(array $externalItem): ?array
    {
        return $this->client->getProduct(['id' => $externalItem['product_id']]);
    }

    /**
     * @param array $externalItem
     * @param SalesChannelItem $salesChannelItem
     * @return array|null
     * @inheritdoc
     */
    protected function saveExternalItem(array $externalItem, SalesChannelItem $salesChannelItem): ?array
    {
        if ($salesChannelItem->external_item_key) {
            $externalItem['id'] = $salesChannelItem->external_item_key;
            return $this->client->updateProduct($externalItem);
        }
        return $this->client->createProduct($externalItem);
    }

    protected function saveExternalItemStocks(array $externalItemStocks): ?array
    {
        $inventoryLevels = [];
        foreach ($externalItemStocks as $externalItemStock) {
            $inventoryLevels[] = $this->client->setInventoryLevel($externalItemStock);
        }
        return $inventoryLevels;
    }
}

<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\channels\shopify;

use lujie\sales\channel\BaseSalesChannel;
use lujie\sales\channel\models\SalesChannelItem;
use lujie\sales\channel\models\SalesChannelOrder;
use yii\db\BaseActiveRecord;

/**
 * Class AmazonSalesChannel
 * @package lujie\sales\channel\channels\amazon
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShopifySalesChannel extends BaseSalesChannel
{
    protected function getExternalOrders(array $externalOrderKeys): array
    {
        // TODO: Implement getExternalOrders() method.
    }

    protected function getNewExternalOrders(int $createdAtFrom, int $createdAtTo): array
    {
        // TODO: Implement getNewExternalOrders() method.
    }

    public function shipSalesOrder(SalesChannelOrder $channelOrder): bool
    {
        // TODO: Implement shipSalesOrder() method.
    }

    public function cancelSalesOrder(SalesChannelOrder $channelOrder): bool
    {
        // TODO: Implement cancelSalesOrder() method.
    }

    protected function formatExternalItemData(BaseActiveRecord $item, SalesChannelItem $salesChannelItem): ?array
    {
        // TODO: Implement formatExternalItemData() method.
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
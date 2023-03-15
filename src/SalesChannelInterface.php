<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel;

use lujie\sales\channel\models\SalesChannelItem;
use lujie\sales\channel\models\SalesChannelOrder;
use yii\base\NotSupportedException;

/**
 * Interface SalesChannelInterface
 * @package lujie\sales\channel
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
interface SalesChannelInterface
{
    /**
     * @param SalesChannelOrder[] $salesChannelOrders
     * @inheritdoc
     */
    public function pullSalesOrders(array $salesChannelOrders): void;

    /**
     * for some marketplace service, it can query new order by created time
     * use this query for better performance
     * @param int $createdAtFrom
     * @param int $createdAtTo
     * @inheritdoc
     */
    public function pullNewSalesOrders(int $createdAtFrom, int $createdAtTo): void;

    /**
     * @param SalesChannelOrder $channelOrder
     * @return bool
     * @inheritdoc
     */
    public function pushSalesOrder(SalesChannelOrder $salesChannelOrder): bool;

    /**
     * @param SalesChannelOrder $channelOrder
     * @return bool
     * @deprecated
     */
    public function shipSalesOrder(SalesChannelOrder $channelOrder): bool;

    /**
     * @param SalesChannelOrder $channelOrder
     * @return bool
     * @deprecated
     */
    public function cancelSalesOrder(SalesChannelOrder $channelOrder): bool;

    /**
     * @param SalesChannelItem $salesChannelItem
     * @inheritdoc
     */
    public function pushSalesItem(SalesChannelItem $salesChannelItem): bool;

    /**
     * @param SalesChannelItem $salesChannelItem
     * @return bool
     * @inheritdoc
     */
    public function checkSalesItemUpdated(SalesChannelItem $salesChannelItem): bool;

    /**
     * @param array $salesChannelItems
     * @return bool
     * @inheritdoc
     */
    public function pushSalesItemStocks(array $salesChannelItems): bool;
}

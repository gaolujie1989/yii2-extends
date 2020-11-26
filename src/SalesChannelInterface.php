<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel;

use lujie\sales\channel\models\SalesChannelOrder;

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
    public function shipSalesOrder(SalesChannelOrder $channelOrder): bool;

    /**
     * @param SalesChannelOrder $channelOrder
     * @return bool
     * @inheritdoc
     */
    public function cancelSalesOrder(SalesChannelOrder $channelOrder): bool;

    /**
     * @inheritdoc
     */
    public function pushStocks(): void;

    /**
     * @inheritdoc
     */
    public function pushPrices(): void;

    /**
     * @inheritdoc
     */
    public function pushProducts(): void;
}
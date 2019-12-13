<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment;

use lujie\fulfillment\common\Item;
use lujie\fulfillment\common\Order;
use lujie\fulfillment\models\FulfillmentItem;
use lujie\fulfillment\models\FulfillmentOrder;

interface FulfillmentServiceInterface
{
    /**
     * @param array $condition
     * @inheritdoc
     */
    public function pullWarehouses(array $condition = []): void;

    /**
     * @param FulfillmentItem $fulfillmentItem
     * @return bool
     * @inheritdoc
     */
    public function pushItem(FulfillmentItem $fulfillmentItem): bool;

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool
     * @inheritdoc
     */
    public function pushFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool;

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool
     * @inheritdoc
     */
    public function cancelFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool;

    /**
     * @param FulfillmentOrder[] $fulfillmentOrders
     * @inheritdoc
     */
    public function pullFulfillmentOrders(array $fulfillmentOrders): void;

    /**
     * @param FulfillmentItem[] $fulfillmentItems
     * @inheritdoc
     */
    public function pullWarehouseStocks(array $fulfillmentItems): void;
}

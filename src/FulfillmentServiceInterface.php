<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment;

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
     * @param $item
     * @return mixed
     * @inheritdoc
     */
    public function pushItem(FulfillmentItem $fulfillmentItem): bool;

    /**
     * @param $order
     * @return mixed
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

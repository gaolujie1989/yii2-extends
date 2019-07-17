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
     * @param $item
     * @return mixed
     * @inheritdoc
     */
    public function pushItem(FulfillmentItem $fulfillmentItem): bool;

    /**
     * @param array $condition
     * @return mixed
     * @inheritdoc
     */
    public function pullExistItems(array $condition = []): void;

    /**
     * @param $order
     * @return mixed
     * @inheritdoc
     */
    public function pushFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool;

    /**
     * @return mixed
     * @inheritdoc
     */
    public function pullFulfillmentOrders(array $condition = []): void;
}

<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\f4px;

use lujie\fulfillment\BaseFulfillmentService;
use lujie\fulfillment\common\Item;
use lujie\fulfillment\common\Order;
use lujie\fulfillment\models\FulfillmentItem;
use lujie\fulfillment\models\FulfillmentOrder;
use lujie\fulfillment\models\FulfillmentWarehouse;
use lujie\fulfillment\models\FulfillmentWarehouseStock;

/**
 * Class PmFulfillmentService
 * @package lujie\fulfillment\pm
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class F4pxFulfillmentService extends BaseFulfillmentService
{

    #region Item Push

    protected function formatExternalItemData(Item $item, FulfillmentItem $fulfillmentItem): array
    {
        // TODO: Implement formatExternalItemData() method.
    }

    protected function getExternalItem(Item $item): ?array
    {
        // TODO: Implement getExternalItem() method.
    }

    protected function saveExternalItem(array $externalItem, FulfillmentItem $fulfillmentItem): ?array
    {
        // TODO: Implement saveExternalItem() method.
    }

    protected function updateFulfillmentItem(FulfillmentItem $fulfillmentItem, array $externalItem): bool
    {
        // TODO: Implement updateFulfillmentItem() method.
    }

    #endregion

    #region Order Push

    protected function formatExternalOrderData(Order $order, FulfillmentOrder $fulfillmentOrder): array
    {
        // TODO: Implement formatExternalOrderData() method.
    }

    protected function getExternalOrder(Order $order): ?array
    {
        // TODO: Implement getExternalOrder() method.
    }

    protected function saveExternalOrder(array $externalOrder, FulfillmentOrder $fulfillmentOrder): ?array
    {
        // TODO: Implement saveExternalOrder() method.
    }

    protected function updateFulfillmentOrder(FulfillmentOrder $fulfillmentOrder, array $externalOrder): bool
    {
        // TODO: Implement updateFulfillmentOrder() method.
    }

    #endregion

    #region Order Action hold/ship/cancel

    public function holdFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        // TODO: Implement holdFulfillmentOrder() method.
    }

    public function shipFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        // TODO: Implement shipFulfillmentOrder() method.
    }

    public function cancelFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        // TODO: Implement cancelFulfillmentOrder() method.
    }

    #endregion

    #region Order Pull

    protected function getExternalOrders(array $externalOrderKeys): array
    {
        // TODO: Implement getExternalOrders() method.
    }

    #endregion

    #region Warehouse Stock Pull

    protected function getExternalWarehouses(array $condition = []): array
    {
        // TODO: Implement getExternalWarehouses() method.
    }

    protected function updateFulfillmentWarehouse(FulfillmentWarehouse $fulfillmentWarehouse, array $externalWarehouse): bool
    {
        // TODO: Implement updateFulfillmentWarehouse() method.
    }

    protected function getExternalWarehouseStocks(array $externalItemKeys): array
    {
        // TODO: Implement getExternalWarehouseStocks() method.
    }

    protected function updateFulfillmentWarehouseStock(FulfillmentWarehouseStock $fulfillmentWarehouseStock, array $externalWarehouseStock): bool
    {
        // TODO: Implement updateFulfillmentWarehouseStock() method.
    }

    #endregion

}

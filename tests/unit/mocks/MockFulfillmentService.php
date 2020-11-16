<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tests\unit\mocks;

use lujie\fulfillment\BaseFulfillmentService;
use lujie\fulfillment\common\Item;
use lujie\fulfillment\common\Order;
use lujie\fulfillment\models\FulfillmentItem;
use lujie\fulfillment\models\FulfillmentOrder;
use lujie\fulfillment\models\FulfillmentWarehouse;
use lujie\fulfillment\models\FulfillmentWarehouseStock;

/**
 * Class MockFulfillmentService
 * @package lujie\fulfillment\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockFulfillmentService extends BaseFulfillmentService
{

    #region Item Push

    protected function formatExternalItemData(Item $item, FulfillmentItem $fulfillmentItem): array
    {
        return [];
    }

    protected function getExternalItem(Item $item): ?array
    {
        return null;
    }

    protected function saveExternalItem(array $externalItem, FulfillmentItem $fulfillmentItem): ?array
    {
        return ['SUCCESS'];
    }

    protected function updateFulfillmentItem(FulfillmentItem $fulfillmentItem, array $externalItem): bool
    {
        $now = time();
        $fulfillmentItem->external_item_key = 1;
        $fulfillmentItem->external_created_at = $now;
        $fulfillmentItem->external_updated_at = $now;
        return $fulfillmentItem->save(false);
    }

    #endregion

    #region Order Push

    protected function formatExternalOrderData(Order $order, FulfillmentOrder $fulfillmentOrder): array
    {
        $now = time();
        return [
            'order_key' => 'order_key',
            'order_status' => 'PUSHED',
            'order_additional' => ['AA' => 'BB'],
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }

    protected function getExternalOrder(Order $order): ?array
    {
        return null;
    }

    protected function saveExternalOrder(array $externalOrder, FulfillmentOrder $fulfillmentOrder): ?array
    {
        return ['SUCCESS'];
    }

    protected function updateFulfillmentOrder(FulfillmentOrder $fulfillmentOrder, array $externalOrder): bool
    {
        $fulfillmentOrder->external_order_key = $externalOrder['order_key'];
        $fulfillmentOrder->external_order_status = $externalOrder['order_status'];
        $fulfillmentOrder->external_order_additional = $externalOrder['order_additional'];
        $fulfillmentOrder->external_created_at = $externalOrder['created_at'];
        $fulfillmentOrder->external_updated_at = $externalOrder['updated_at'];
        return $fulfillmentOrder->save(false);
    }

    #endregion

    #region Order Action hold/ship/cancel

    public function holdFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        $now = time();
        $fulfillmentOrder->external_order_status = 'HOLD';
        $fulfillmentOrder->external_order_additional = ['HH' => 'HH'];
        $fulfillmentOrder->external_updated_at = $now;
        return $fulfillmentOrder->save(false);
    }

    public function shipFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        $now = time();
        $fulfillmentOrder->external_order_status = 'SHIPPING';
        $fulfillmentOrder->external_order_additional = ['SS' => 'SS'];
        $fulfillmentOrder->external_updated_at = $now;
        return $fulfillmentOrder->save(false);
    }

    public function cancelFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        $now = time();
        $fulfillmentOrder->external_order_status = 'CANCELLED';
        $fulfillmentOrder->external_order_additional = ['CC' => 'DD'];
        $fulfillmentOrder->external_updated_at = $now;
        return $fulfillmentOrder->save(false);
    }

    #endregion

    #region Order Pull

    protected function getExternalOrders(array $externalOrderKeys): array
    {
        $now = time();
        $externalOrders = [];
        foreach ($externalOrderKeys as $externalOrderKey) {
            $externalOrders[$externalOrderKey] = [
                'order_status' => 'PROCESSING',
                'order_additional' => ['AA' => 'BB'],
                'created_at' => $now - 10,
                'updated_at' => $now,
            ];
        }
        return $externalOrders;
    }

    #endregion

    #region Warehouse Stock Pull

    protected function getExternalWarehouses(array $condition = []): array
    {
        return [];
    }

    protected function updateFulfillmentWarehouse(FulfillmentWarehouse $fulfillmentWarehouse, array $externalWarehouse): bool
    {
        $fulfillmentWarehouse->external_warehouse_key = 'W-XXX';
        return $fulfillmentWarehouse->save(false);
    }

    protected function getExternalWarehouseStocks(array $externalItemKeys): array
    {
        return [];
    }

    protected function updateFulfillmentWarehouseStock(FulfillmentWarehouseStock $fulfillmentWarehouseStock, array $externalWarehouseStock): bool
    {
        $fulfillmentWarehouseStock->stock_qty = 1;
        return $fulfillmentWarehouseStock->save(false);
    }

    #endregion

}

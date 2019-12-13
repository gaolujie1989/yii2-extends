<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tests\unit\mocks;

use lujie\fulfillment\FulfillmentServiceInterface;
use lujie\fulfillment\models\FulfillmentItem;
use lujie\fulfillment\models\FulfillmentOrder;
use lujie\fulfillment\models\FulfillmentWarehouse;
use lujie\fulfillment\models\FulfillmentWarehouseStock;
use yii\base\BaseObject;

/**
 * Class MockFulfillmentService
 * @package lujie\fulfillment\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockFulfillmentService extends BaseObject implements FulfillmentServiceInterface
{
    public $accountId;

    public static $externalWarehouseIds = [1];

    /**
     * @param array $condition
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function pullWarehouses(array $condition = []): void
    {
        $fulfillmentWarehouses = FulfillmentWarehouse::find()
            ->accountId($this->accountId)
            ->externalWarehouseId(static::$externalWarehouseIds)
            ->indexBy('external_warehouse_id')
            ->all();
        foreach (static::$externalWarehouseIds as $externalWarehouseId) {
            $fulfillmentWarehouse = $fulfillmentWarehouses[$externalWarehouseId] ?? new FulfillmentWarehouse();
            $fulfillmentWarehouse->fulfillment_account_id = $this->accountId;
            $fulfillmentWarehouse->external_warehouse_id = $externalWarehouseId;
            $fulfillmentWarehouse->external_warehouse_name = 'W-' . $externalWarehouseId;
            $fulfillmentWarehouse->mustSave(false);
        }
    }

    /**
     * @param FulfillmentItem $fulfillmentItem
     * @return bool
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function pushItem(FulfillmentItem $fulfillmentItem): bool
    {
        $now = time();
        $fulfillmentItem->external_item_id = 1;
        $fulfillmentItem->external_item_no = 1;
        $fulfillmentItem->external_item_parent_id = 1;
        $fulfillmentItem->external_created_at = $now;
        $fulfillmentItem->external_updated_at = $now;
        $fulfillmentItem->mustSave(false);
        return true;
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function pushFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        $now = time();
        $fulfillmentOrder->external_order_id = 1;
        $fulfillmentOrder->external_order_no = 'ORDER-NO-XXX';
        $fulfillmentOrder->external_order_status = 'PUSHED';
        $fulfillmentOrder->external_order_additional = ['AA' => 'BB'];
        $fulfillmentOrder->external_created_at = $now;
        $fulfillmentOrder->external_updated_at = $now;
        $fulfillmentOrder->mustSave(false);
        return true;
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function cancelFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        $now = time();
        $fulfillmentOrder->external_order_status = 'CANCELLED';
        $fulfillmentOrder->external_order_additional = ['CC' => 'DD'];
        $fulfillmentOrder->external_updated_at = $now;
        $fulfillmentOrder->mustSave(false);
        return true;
    }

    /**
     * @param FulfillmentOrder[] $fulfillmentOrders
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function pullFulfillmentOrders(array $fulfillmentOrders): void
    {
        $now = time();
        foreach ($fulfillmentOrders as $fulfillmentOrder) {
            $fulfillmentOrder->external_order_status = 'PROCESSING';
            $fulfillmentOrder->external_updated_at = $now - 10;
            $fulfillmentOrder->order_pulled_at = $now;
            $fulfillmentOrder->mustSave(false);
        }
    }

    /**
     * @param FulfillmentItem[] $fulfillmentItems
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function pullWarehouseStocks(array $fulfillmentItems): void
    {
        foreach ($fulfillmentItems as $fulfillmentItem) {
            $fulfillmentWarehouseStocks = FulfillmentWarehouseStock::find()
                ->accountId($this->accountId)
                ->externalItemId($fulfillmentItem->external_item_id)
                ->externalWarehouseId(static::$externalWarehouseIds)
                ->indexBy('external_warehouse_id')
                ->all();
            foreach (static::$externalWarehouseIds as $externalWarehouseId) {
                $fulfillmentWarehouseStock = $fulfillmentWarehouseStocks[$externalWarehouseId] ?? new FulfillmentWarehouseStock();
                $fulfillmentWarehouseStock->fulfillment_account_id = $this->accountId;
                $fulfillmentWarehouseStock->external_warehouse_id = $externalWarehouseId;
                $fulfillmentWarehouseStock->external_item_id = $fulfillmentItem->external_item_id;
                $fulfillmentWarehouseStock->stock_qty = 1;
                $fulfillmentWarehouseStock->mustSave(false);
            }
        }
    }
}

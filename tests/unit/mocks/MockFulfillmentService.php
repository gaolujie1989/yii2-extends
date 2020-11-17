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
use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;

/**
 * Class MockFulfillmentService
 * @package lujie\fulfillment\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockFulfillmentService extends BaseFulfillmentService
{
    public static $EXTERNAL_ITEM_DATA = [];

    public static $GENERATE_ITEM_KEYS = [];

    public static $EXTERNAL_ORDER_DATA = [];

    public static $GENERATE_ORDER_KEYS = [];

    public static $EXTERNAL_STOCK_DATA = [];

    #region Item Push

    /**
     * @param Item $item
     * @param FulfillmentItem $fulfillmentItem
     * @return array
     * @inheritdoc
     */
    protected function formatExternalItemData(Item $item, FulfillmentItem $fulfillmentItem): array
    {
        $itemData = ArrayHelper::toArray($item);
        unset($itemData['itemId']);
        return $itemData;
    }

    /**
     * @param Item $item
     * @return array|null
     * @inheritdoc
     */
    protected function getExternalItem(Item $item): ?array
    {
        foreach (static::$EXTERNAL_ITEM_DATA as $externalItemData) {
            if ($externalItemData['itemNo'] === $item->itemNo) {
                return $externalItemData;
            }
        }
        return null;
    }

    /**
     * @param array $externalItem
     * @param FulfillmentItem $fulfillmentItem
     * @return array|null
     * @inheritdoc
     */
    protected function saveExternalItem(array $externalItem, FulfillmentItem $fulfillmentItem): ?array
    {
        $now = time();
        if ($fulfillmentItem->external_item_key) {
            if (empty(static::$EXTERNAL_ITEM_DATA[$fulfillmentItem->external_item_key])) {
                throw new InvalidArgumentException('External Item already exists.');
            }
            $externalItemKey = $fulfillmentItem->external_item_key;
            $externalItem = array_merge(static::$EXTERNAL_ITEM_DATA[$fulfillmentItem->external_item_key], $externalItem);
            $externalItem['updated_at'] = $now;
            if ($externalItem['updated_at'] <= $externalItem['created_at']) {
                $externalItem['updated_at'] = $externalItem['created_at'] + 1;
            }
        } else {
            $externalItemKey = array_shift(static::$GENERATE_ITEM_KEYS);
            if (isset(static::$EXTERNAL_ITEM_DATA[$externalItemKey])) {
                throw new InvalidArgumentException('External Item already exists.');
            }
            $externalItem['created_at'] = $now;
            $externalItem['updated_at'] = $now;

        }
        $externalItem[$this->externalItemKeyField] = $externalItemKey;
        static::$EXTERNAL_ITEM_DATA[$externalItemKey] = $externalItem;
        return $externalItem;
    }

    /**
     * @param FulfillmentItem $fulfillmentItem
     * @param array $externalItem
     * @return bool
     * @inheritdoc
     */
    protected function updateFulfillmentItem(FulfillmentItem $fulfillmentItem, array $externalItem): bool
    {
        $fulfillmentItem->external_created_at = $externalItem['created_at'];
        $fulfillmentItem->external_updated_at = $externalItem['updated_at'];
        return parent::updateFulfillmentItem($fulfillmentItem, $externalItem);
    }

    #endregion

    #region Order Push

    /**
     * @param Order $order
     * @param FulfillmentOrder $fulfillmentOrder
     * @return array
     * @inheritdoc
     */
    protected function formatExternalOrderData(Order $order, FulfillmentOrder $fulfillmentOrder): array
    {
        $orderData = ArrayHelper::toArray($order);
        unset($orderData['orderId']);
        return $orderData;
    }

    /**
     * @param Order $order
     * @return array|null
     * @inheritdoc
     */
    protected function getExternalOrder(Order $order): ?array
    {
        foreach (static::$EXTERNAL_ORDER_DATA as $externalOrderData) {
            if ($externalOrderData['orderNo'] === $order->orderNo) {
                return $externalOrderData;
            }
        }
        return null;
    }

    /**
     * @param array $externalOrder
     * @param FulfillmentOrder $fulfillmentOrder
     * @return string[]|null
     * @inheritdoc
     */
    protected function saveExternalOrder(array $externalOrder, FulfillmentOrder $fulfillmentOrder): ?array
    {
        $now = time();
        if ($fulfillmentOrder->external_order_key) {
            if (empty(static::$EXTERNAL_ORDER_DATA[$fulfillmentOrder->external_order_key])) {
                throw new InvalidArgumentException('External Order already exists.');
            }
            $externalOrderKey = $fulfillmentOrder->external_order_key;
            $externalOrder = array_merge(static::$EXTERNAL_ORDER_DATA[$fulfillmentOrder->external_order_key], $externalOrder);
            $externalOrder['updated_at'] = $now;
            if ($externalOrder['updated_at'] <= $externalOrder['created_at']) {
                $externalOrder['updated_at'] = $externalOrder['created_at'] + 1;
            }
        } else {
            $externalOrderKey = array_shift(static::$GENERATE_ORDER_KEYS);
            if (isset(static::$EXTERNAL_ORDER_DATA[$externalOrderKey])) {
                throw new InvalidArgumentException('External Order already exists.');
            }
            $externalOrder['created_at'] = $now;
            $externalOrder['updated_at'] = $now;
            $externalOrder['order_status'] = 'READY';
        }
        $externalOrder[$this->externalOrderKeyField] = $externalOrderKey;
        static::$EXTERNAL_ORDER_DATA[$externalOrderKey] = $externalOrder;
        return $externalOrder;
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @param array $externalOrder
     * @return bool
     * @inheritdoc
     */
    protected function updateFulfillmentOrder(FulfillmentOrder $fulfillmentOrder, array $externalOrder): bool
    {
        $fulfillmentOrder->external_order_status = $externalOrder['order_status'];
        $fulfillmentOrder->external_created_at = $externalOrder['created_at'];
        $fulfillmentOrder->external_updated_at = $externalOrder['updated_at'];
        return parent::updateFulfillmentOrder($fulfillmentOrder, $externalOrder);
    }

    #endregion

    #region Order Action hold/ship/cancel

    public function holdFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        $now = time();
        $fulfillmentOrder->external_order_status = 'HOLDING';
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

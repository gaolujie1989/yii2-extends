<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tests\unit\mocks;

use lujie\fulfillment\BaseFulfillmentService;
use lujie\fulfillment\common\Item;
use lujie\fulfillment\common\Order;
use lujie\fulfillment\constants\FulfillmentConst;
use lujie\fulfillment\models\FulfillmentItem;
use lujie\fulfillment\models\FulfillmentOrder;
use lujie\fulfillment\models\FulfillmentWarehouse;
use lujie\fulfillment\models\FulfillmentWarehouseStock;
use lujie\fulfillment\models\FulfillmentWarehouseStockMovement;
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

    public static $GENERATE_ITEM_KEYS = ['ITEM_K1'];

    public static $EXTERNAL_ORDER_DATA = [];

    public static $GENERATE_ORDER_KEYS = ['ORDER_K1'];

    public static $EXTERNAL_WAREHOUSE_DATA = [
        [
            'id' => 'W01',
            'name' => 'WarehouseDE'
        ],
        [
            'id' => 'W02',
            'name' => 'WarehouseES'
        ]
    ];

    public static $EXTERNAL_STOCK_DATA = [
        [
            'itemId' => 'ITEM_K1',
            'warehouseId' => 'W01',
            'stock_qty' => 1
        ],
        [
            'itemId' => 'ITEM_K1',
            'warehouseId' => 'W02',
            'stock_qty' => 2
        ]
    ];

    public static $EXTERNAL_MOVEMENT_DATA = [
        [
            'id' => 'M001',
            'itemId' => 'ITEM_K1',
            'warehouseId' => 'W01',
            'moved_qty' => 1,
            'reason' => 'INBOUND',
            'related_type' => 'INBOUND_ORDER',
            'related_id' => '15267',
            'createdAt' => 1577808000123
        ],
        [
            'id' => 'M002',
            'itemId' => 'ITEM_K1',
            'warehouseId' => 'W02',
            'moved_qty' => 2,
            'reason' => 'CORRECT'
        ]
    ];

    /**
     * @var array
     */
    public $fulfillmentStatusMap = [
        'HOLDING' => FulfillmentConst::FULFILLMENT_STATUS_HOLDING,
        'SHIPPING' => FulfillmentConst::FULFILLMENT_STATUS_PROCESSING,
        'ERROR' => FulfillmentConst::FULFILLMENT_STATUS_SHIP_PENDING,
        'SHIPPED' => FulfillmentConst::FULFILLMENT_STATUS_SHIPPED,
        'CANCELLED' => FulfillmentConst::FULFILLMENT_STATUS_CANCELLED,
    ];

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
            $externalOrder['status'] = 'SHIPPING';
        }
        $externalOrder[$this->externalOrderKeyField] = $externalOrderKey;
        static::$EXTERNAL_ORDER_DATA[$externalOrderKey] = $externalOrder;
        return $externalOrder;
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @param array $externalOrder
     * @return bool
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function updateFulfillmentOrder(FulfillmentOrder $fulfillmentOrder, array $externalOrder): bool
    {
        $fulfillmentOrder->external_created_at = $externalOrder['created_at'];
        $fulfillmentOrder->external_updated_at = $externalOrder['updated_at'];
        if ($fulfillmentOrder->external_order_status === 'SHIPPED') {
            $fulfillmentOrder->external_order_additional = [
                'trackingNumbers' => $externalOrder['trackingNumbers'] ?? []
            ];
        }
        return parent::updateFulfillmentOrder($fulfillmentOrder, $externalOrder);
    }

    #endregion

    #region Order Action hold/ship/cancel

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function holdFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        $externalOrder = $this->saveExternalOrder([
            'status' => 'HOLDING',
        ], $fulfillmentOrder);
        return $this->updateFulfillmentOrder($fulfillmentOrder, $externalOrder);
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function shipFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        $externalOrder = $this->saveExternalOrder([
            'status' => 'SHIPPING',
        ], $fulfillmentOrder);
        return $this->updateFulfillmentOrder($fulfillmentOrder, $externalOrder);
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function cancelFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        $externalOrder = $this->saveExternalOrder([
            'status' => 'CANCELLED',
        ], $fulfillmentOrder);
        return $this->updateFulfillmentOrder($fulfillmentOrder, $externalOrder);
    }

    #endregion

    #region Order Pull

    /**
     * @param array $externalOrderKeys
     * @return array
     * @inheritdoc
     */
    protected function getExternalOrders(array $externalOrderKeys): array
    {
        return array_intersect_key(static::$EXTERNAL_ORDER_DATA, array_flip($externalOrderKeys));
    }

    /**
     * @param int $shippedAtFrom
     * @param int $shippedAtTo
     * @return array
     * @inheritdoc
     */
    protected function getShippedExternalOrders(int $shippedAtFrom, int $shippedAtTo): array
    {
        return static::$EXTERNAL_ORDER_DATA;
    }

    #endregion

    #region

    /**
     * @param array $condition
     * @return array
     * @inheritdoc
     */
    protected function getExternalWarehouses(array $condition = []): array
    {
        return static::$EXTERNAL_WAREHOUSE_DATA;
    }

    #endregion

    #region Stock Pull

    protected function getExternalWarehouseStocks(array $externalItemKeys): array
    {
        return static::$EXTERNAL_STOCK_DATA;
    }

    protected function updateFulfillmentWarehouseStock(FulfillmentWarehouseStock $fulfillmentWarehouseStock, array $externalWarehouseStock): bool
    {
        $fulfillmentWarehouseStock->stock_qty = $externalWarehouseStock['stock_qty'];
        return $fulfillmentWarehouseStock->save(false);
    }

    #endregion

    #region Stock Movement Pull

    /**
     * @param FulfillmentWarehouse $fulfillmentWarehouse
     * @param int $movementAtFrom
     * @param int $movementAtTo
     * @param FulfillmentItem|null $fulfillmentItem
     * @return array
     * @inheritdoc
     */
    protected function getExternalWarehouseStockMovements(FulfillmentWarehouse $fulfillmentWarehouse, int $movementAtFrom, int $movementAtTo, ?FulfillmentItem $fulfillmentItem = null): array
    {
        return array_filter(static::$EXTERNAL_MOVEMENT_DATA, static function($movement) use ($fulfillmentWarehouse) {
            return $movement['warehouseId'] === $fulfillmentWarehouse->external_warehouse_key;
        });
    }

    /**
     * @param FulfillmentWarehouseStockMovement $fulfillmentStockMovement
     * @param array $externalStockMovement
     * @return bool
     * @inheritdoc
     */
    protected function updateFulfillmentWarehouseStockMovements(FulfillmentWarehouseStockMovement $fulfillmentStockMovement, array $externalStockMovement): bool
    {
        $fulfillmentStockMovement->moved_qty = $externalStockMovement['moved_qty'];
        $fulfillmentStockMovement->related_type = $externalStockMovement['related_type'] ?? '';
        $fulfillmentStockMovement->related_key = $externalStockMovement['related_id'] ?? '';
        return $fulfillmentStockMovement->save(false);
    }

    #region

}

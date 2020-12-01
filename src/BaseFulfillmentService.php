<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment;

use lujie\data\loader\DataLoaderInterface;
use lujie\fulfillment\common\Item;
use lujie\fulfillment\common\Order;
use lujie\fulfillment\constants\FulfillmentConst;
use lujie\fulfillment\events\FulfillmentOrderEvent;
use lujie\fulfillment\models\FulfillmentAccount;
use lujie\fulfillment\models\FulfillmentItem;
use lujie\fulfillment\models\FulfillmentOrder;
use lujie\fulfillment\models\FulfillmentWarehouse;
use lujie\fulfillment\models\FulfillmentWarehouseStock;
use lujie\fulfillment\models\FulfillmentWarehouseStockMovement;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * Class BaseFulfillmentService
 * @package lujie\fulfillment
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseFulfillmentService extends Component implements FulfillmentServiceInterface
{
    public const EVENT_AFTER_FULFILLMENT_ORDER_UPDATED = 'AFTER_FULFILLMENT_ORDER_UPDATED';

    /**
     * @var FulfillmentAccount
     */
    public $account;

    /**
     * @var DataLoaderInterface
     */
    public $itemLoader;

    /**
     * @var DataLoaderInterface
     */
    public $orderLoader;

    #region External Model Key Field

    /**
     * @var string
     */
    public $externalItemKeyField = 'id';

    /**
     * @var string
     */
    public $externalOrderKeyField = 'id';

    /**
     * @var string
     */
    public $externalOrderStatusField = 'status';

    /**
     * @var string
     */
    public $externalWarehouseKeyField = 'id';

    /**
     * @var string
     */
    public $stockItemKeyField = 'itemId';

    /**
     * @var string
     */
    public $stockWarehouseKeyField = 'warehouseId';

    /**
     * @var string
     */
    public $externalMovementKeyField = 'id';

    /**
     * @var string
     */
    public $externalMovementTimeField = 'createdAt';

    /**
     * [
     *      'external_order_status' => 'fulfillment_status'
     * ]
     * @var array
     */
    public $fulfillmentStatusMap = [];

    #endregion

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if ($this->account === null || !($this->account instanceof FulfillmentAccount)) {
            throw new InvalidConfigException('The property `account` can not be null and must be FulfillmentAccount');
        }
        $this->itemLoader = Instance::ensure($this->itemLoader, DataLoaderInterface::class);
        $this->orderLoader = Instance::ensure($this->orderLoader, DataLoaderInterface::class);
    }

    #region Item Push

    /**
     * @param FulfillmentItem $fulfillmentItem
     * @return bool
     * @inheritdoc
     */
    public function pushItem(FulfillmentItem $fulfillmentItem): bool
    {
        if ($fulfillmentItem->fulfillment_account_id !== $this->account->account_id) {
            Yii::info("FulfillmentItem account {$fulfillmentItem->fulfillment_account_id} is not equal with service account {$this->account->account_id}", __METHOD__);
            return false;
        }

        /** @var Item $item */
        $item = $this->itemLoader->get($fulfillmentItem->item_id);
        if (empty($item) || empty($item->itemBarcodes)) {
            Yii::info("Empty Item or ItemBarcodes", __METHOD__);
            return false;
        }

        if (empty($fulfillmentItem->item_pushed_at) && $externalItem = $this->getExternalItem($item)) {
            Yii::info("Item not pushed, but exist in external, update FulfillmentItem", __METHOD__);
            $this->updateFulfillmentItem($fulfillmentItem, $externalItem);
        }

        $externalItem = $this->formatExternalItemData($item, $fulfillmentItem);
        if ($externalItem = $this->saveExternalItem($externalItem, $fulfillmentItem)) {
            Yii::info("Item pushed success, update FulfillmentItem", __METHOD__);
            return $this->updateFulfillmentItem($fulfillmentItem, $externalItem);
        }
        Yii::warning("Item pushed failed, skip update FulfillmentItem", __METHOD__);
        return false;
    }

    /**
     * transform item to external item data
     * @param Item $item
     * @param FulfillmentItem $fulfillmentItem
     * @return array
     */
    abstract protected function formatExternalItemData(Item $item, FulfillmentItem $fulfillmentItem): array;

    /**
     * get external item by item with item_no or barcode
     * @param Item $item
     * @return array|null
     */
    abstract protected function getExternalItem(Item $item): ?array;

    /**
     * save external item
     * @param array $externalItem
     * @param FulfillmentItem $fulfillmentItem
     * @return array|null
     */
    abstract protected function saveExternalItem(array $externalItem, FulfillmentItem $fulfillmentItem): ?array;

    /**
     * update fulfillment item info, like external item_id, item_no, extra...
     * @param FulfillmentItem $fulfillmentItem
     * @param array $externalItem
     * @return bool
     */
    protected function updateFulfillmentItem(FulfillmentItem $fulfillmentItem, array $externalItem): bool
    {
        $fulfillmentItem->external_item_key = $externalItem[$this->externalItemKeyField];
        return $fulfillmentItem->save(false);
    }

    #endregion

    #region Order Push

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool
     * @inheritdoc
     */
    public function pushFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        if ($fulfillmentOrder->fulfillment_account_id !== $this->account->account_id) {
            Yii::info("FulfillmentOrder account {$fulfillmentOrder->fulfillment_account_id} is not equal with service account {$this->account->account_id}", __METHOD__);
            return false;
        }

        /** @var Order $order */
        $order = $this->orderLoader->get($fulfillmentOrder->order_id);
        if (empty($order) || empty($order->orderItems)) {
            Yii::info("Empty Order or OrderItems", __METHOD__);
            return false;
        }
        if (empty($fulfillmentOrder->order_pushed_at) && $externalOrder = $this->getExternalOrder($order)) {
            Yii::info("Order not pushed, but exist in external, update FulfillmentOrder", __METHOD__);
            $this->updateFulfillmentOrder($fulfillmentOrder, $externalOrder);
        }

        $externalOrder = $this->formatExternalOrderData($order, $fulfillmentOrder);
        if ($externalOrder = $this->saveExternalOrder($externalOrder, $fulfillmentOrder)) {
            Yii::info("Order pushed success, update FulfillmentOrder", __METHOD__);
            return $this->updateFulfillmentOrder($fulfillmentOrder, $externalOrder);
        }
        Yii::warning("Order pushed failed, skip update FulfillmentOrder", __METHOD__);
        return false;
    }

    /**
     * transform order to external order data
     * @param Order $order
     * @param FulfillmentOrder $fulfillmentOrder
     * @return array
     */
    abstract protected function formatExternalOrderData(Order $order, FulfillmentOrder $fulfillmentOrder): array;

    /**
     * get external order by order with order_no
     * @param Order $order
     * @return array|null
     */
    abstract protected function getExternalOrder(Order $order): ?array;

    /**
     * save external order
     * @param array $externalOrder
     * @param FulfillmentOrder $fulfillmentOrder
     * @return array|null
     */
    abstract protected function saveExternalOrder(array $externalOrder, FulfillmentOrder $fulfillmentOrder): ?array;

    /**
     * update fulfillment order info, like external order_id, order_no, extra...
     * @param FulfillmentOrder $fulfillmentOrder
     * @param array $externalOrder
     * @return bool
     * @throws InvalidConfigException
     * @throws \Throwable
     * @inheritdoc
     */
    protected function updateFulfillmentOrder(FulfillmentOrder $fulfillmentOrder, array $externalOrder): bool
    {
        $fulfillmentOrder->order_pulled_at = time();
        $fulfillmentOrder->external_order_key = $externalOrder[$this->externalOrderKeyField];
        $fulfillmentOrder->external_order_status = $externalOrder[$this->externalOrderStatusField];

        $newFulfillmentStatus = $this->fulfillmentStatusMap[$fulfillmentOrder->external_order_status] ?? null;
        if ($newFulfillmentStatus === FulfillmentConst::FULFILLMENT_STATUS_SHIPPED
            && empty($fulfillmentOrder->external_order_additional['trackingNumbers'])) {
            Yii::info("ExternalOrder shipped, but no trackingNumbers, set FulfillmentStatus to PICKING", __METHOD__);
            $newFulfillmentStatus = FulfillmentConst::FULFILLMENT_STATUS_PICKING;
        }
        if ($newFulfillmentStatus) {
            $fulfillmentOrder->fulfillment_status = $newFulfillmentStatus;
        }
        return FulfillmentOrder::getDb()->transaction(function() use ($fulfillmentOrder, $externalOrder) {
            if ($fulfillmentOrder->save(false)) {
                $this->triggerFulfillmentOrderEvent($fulfillmentOrder, $externalOrder);
                return true;
            }
            return false;
        });
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @param array $externalOrder
     * @inheritdoc
     */
    protected function triggerFulfillmentOrderEvent(FulfillmentOrder $fulfillmentOrder, array $externalOrder): void
    {
        $event = new FulfillmentOrderEvent();
        $event->fulfillmentOrder = $fulfillmentOrder;
        $event->externalOrder = $externalOrder;
        $this->trigger(self::EVENT_AFTER_FULFILLMENT_ORDER_UPDATED, $event);
    }

    #endregion

    #region Order Action hold/ship/cancel

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool
     * @inheritdoc
     */
    abstract public function holdFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool;

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool
     * @inheritdoc
     */
    abstract public function shipFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool;

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool
     * @inheritdoc
     */
    abstract public function cancelFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool;

    #endregion

    #region Order Pull

    /**
     * @param array $fulfillmentOrders
     * @inheritdoc
     */
    public function pullFulfillmentOrders(array $fulfillmentOrders): void
    {
        $fulfillmentOrders = ArrayHelper::index($fulfillmentOrders, 'external_order_key');
        $externalOrderKeys = array_keys($fulfillmentOrders);
        $externalOrders = $this->getExternalOrders($externalOrderKeys);
        foreach ($externalOrders as $externalOrder) {
            $externalOrderKey = $externalOrder[$this->externalOrderKeyField];
            $this->updateFulfillmentOrder($fulfillmentOrders[$externalOrderKey], $externalOrder);
        }
    }

    /**
     * @param array $externalOrderKeys
     * @return array
     */
    abstract protected function getExternalOrders(array $externalOrderKeys): array;

    /**
     * @param int $shippedAtFrom
     * @param int $shippedAtTo
     * @return mixed|void
     * @inheritdoc
     */
    public function pullShippedFulfillmentOrders(int $shippedAtFrom, int $shippedAtTo): void
    {
        $externalOrders = $this->getShippedExternalOrders($shippedAtFrom, $shippedAtTo);
        if (empty($externalOrders)) {
            return;
        }
        $externalOrders = ArrayHelper::index($externalOrders, $this->externalOrderKeyField);
        $externalOrderKeys = array_keys($externalOrders);
        $query = FulfillmentOrder::find()
            ->fulfillmentAccountId($this->account->account_id)
            ->externalOrderKey($externalOrderKeys);
        foreach ($query->each() as $fulfillmentOrder) {
            $this->updateFulfillmentOrder($fulfillmentOrder, $externalOrders[$fulfillmentOrder->external_order_key]);
        }
    }

    abstract protected function getShippedExternalOrders(int $shippedAtFrom, int $shippedAtTo): array;

    #endregion

    #region Warehouse Pull

    /**
     * @param array $condition
     * @inheritdoc
     */
    public function pullWarehouses(array $condition = []): void
    {
        $externalWarehouses = $this->getExternalWarehouses($condition);
        foreach ($externalWarehouses as $externalWarehouse) {
            $externalWarehouseKey = $externalWarehouse[$this->externalWarehouseKeyField];
            $fulfillmentWarehouse = FulfillmentWarehouse::find()
                ->fulfillmentAccountId($this->account->account_id)
                ->externalWarehouseKey($externalWarehouseKey)
                ->one();

            if ($fulfillmentWarehouse === null) {
                $fulfillmentWarehouse = new FulfillmentWarehouse();
                $fulfillmentWarehouse->fulfillment_account_id = $this->account->account_id;
                $fulfillmentWarehouse->external_warehouse_key = $externalWarehouseKey;
            }
            $this->updateFulfillmentWarehouse($fulfillmentWarehouse, $externalWarehouse);
        }
    }

    /**
     * @param array $condition
     * @return array
     */
    abstract protected function getExternalWarehouses(array $condition = []): array;

    /**
     * @param FulfillmentWarehouse $fulfillmentWarehouse
     * @param array $externalWarehouse
     * @return bool
     */
    protected function updateFulfillmentWarehouse(FulfillmentWarehouse $fulfillmentWarehouse, array $externalWarehouse): bool
    {
        return $fulfillmentWarehouse->save(false);
    }

    #endregion

    #region Stock Pull

    /**
     * @param FulfillmentItem[] $fulfillmentItems
     * @inheritdoc
     */
    public function pullWarehouseStocks(array $fulfillmentItems): void
    {
        $warehouseIds = FulfillmentWarehouse::find()
            ->fulfillmentAccountId($this->account->account_id)
            ->getWarehouseIds();
        $externalWarehouseKeys = array_keys($warehouseIds);
        $itemIds = ArrayHelper::map($fulfillmentItems, 'external_item_key', 'item_id');
        $externalItemKeys = array_keys($itemIds);

        $fulfillmentWarehouseStocks = FulfillmentWarehouseStock::find()
            ->fulfillmentAccountId($this->account->account_id)
            ->externalWarehouseKey($externalWarehouseKeys)
            ->externalItemKey($externalItemKeys)
            ->indexBy(static function ($model) {
                /** @var FulfillmentWarehouseStock $model */
                return $model->external_warehouse_key . '-' . $model->external_item_key;
            })
            ->all();

        $now = time();
        $externalWarehouseStocks = $this->getExternalWarehouseStocks($externalItemKeys);

        foreach ($externalWarehouseStocks as $externalWarehouseStock) {
            $externalWarehouseKey = $externalWarehouseStock[$this->stockWarehouseKeyField];
            $externalItemKey = $externalWarehouseStock[$this->stockItemKeyField];
            if (empty($warehouseIds[$externalWarehouseKey]) || empty($itemIds[$externalItemKey])) {
                Yii::debug("Empty warehouseId or itemId, skip", __METHOD__);
                continue;
            }

            $stockKey = $externalWarehouseKey . '-' . $externalItemKey;
            $fulfillmentWarehouseStock = $fulfillmentWarehouseStocks[$stockKey] ?? new FulfillmentWarehouseStock();
            if ($fulfillmentWarehouseStock->getIsNewRecord()) {
                $fulfillmentWarehouseStock->fulfillment_account_id = $this->account->account_id;
                $fulfillmentWarehouseStock->external_warehouse_key = $externalWarehouseKey;
                $fulfillmentWarehouseStock->external_item_key = $externalItemKey;
                $fulfillmentWarehouseStock->warehouse_id = $warehouseIds[$externalWarehouseKey];
                $fulfillmentWarehouseStock->item_id = $itemIds[$externalItemKey];
            }

            $fulfillmentWarehouseStock->stock_pulled_at = $now;
            $this->updateFulfillmentWarehouseStock($fulfillmentWarehouseStock, $externalWarehouseStock);
        }
        $pulledExternalItemKeys = ArrayHelper::getColumn($externalWarehouseStocks, $this->stockItemKeyField);
        $notPulledExternalItemKeys = array_diff($externalItemKeys, $pulledExternalItemKeys);

        if ($notPulledExternalItemKeys) {
            FulfillmentWarehouseStock::deleteAll([
                'fulfillment_account_id' => $this->account->account_id,
                'external_warehouse_key' => $externalWarehouseKeys,
                'external_item_key' => $notPulledExternalItemKeys,
            ]);
        }
        FulfillmentItem::updateAll(['stock_pulled_at' => $now], [
            'fulfillment_account_id' => $this->account->account_id,
            'external_item_key' => $externalItemKeys
        ]);
    }

    /**
     * @param array $externalItemKeys
     * @return array
     */
    abstract protected function getExternalWarehouseStocks(array $externalItemKeys): array;

    /**
     * @param FulfillmentWarehouseStock $fulfillmentWarehouseStock
     * @param array $externalWarehouseStock
     * @return bool
     */
    abstract protected function updateFulfillmentWarehouseStock(FulfillmentWarehouseStock $fulfillmentWarehouseStock, array $externalWarehouseStock): bool;

    #endregion

    #region Stock Movement Pull

    /**
     * @param FulfillmentWarehouse $fulfillmentWarehouse
     * @param int $movementAtFrom
     * @param int $movementAtTo
     * @param FulfillmentItem|null $fulfillmentItem
     * @inheritdoc
     */
    public function pullWarehouseStockMovements(FulfillmentWarehouse $fulfillmentWarehouse, int $movementAtFrom, int $movementAtTo, ?FulfillmentItem $fulfillmentItem = null): void
    {
        if ($fulfillmentWarehouse->fulfillment_account_id !== $this->account->account_id) {
            Yii::info("FulfillmentWarehouse account {$fulfillmentWarehouse->fulfillment_account_id} is not equal with service account {$this->account->account_id}", __METHOD__);
            return;
        }
        if (!$fulfillmentWarehouse->support_movement) {
            Yii::info("FulfillmentWarehouse {$fulfillmentWarehouse->external_warehouse_key} not support movement", __METHOD__);
            return;
        }

        $externalMovements = $this->getExternalWarehouseStockMovements($fulfillmentWarehouse, $movementAtFrom, $movementAtTo, $fulfillmentItem);
        if (empty($externalMovements)) {
            return;
        }
        $externalMovements = ArrayHelper::index($externalMovements, $this->externalMovementKeyField);
        $stockMovementKeys = array_keys($externalMovements);
        $existMovementKeys = FulfillmentWarehouseStockMovement::find()
            ->fulfillmentAccountId($this->account->account_id)
            ->externalMovementKey($stockMovementKeys)
            ->getExternalMovementKey();
        $newMovementKeys = array_diff($stockMovementKeys, $existMovementKeys);
        if (empty($newMovementKeys)) {
            return;
        }

        $externalItemKeys = [];
        foreach ($newMovementKeys as $newMovementKey) {
            $externalItemKey = $externalMovements[$newMovementKey][$this->stockItemKeyField];
            $externalItemKeys[$externalItemKey] = $externalItemKey;
        }
        $itemIds = FulfillmentItem::find()
            ->fulfillmentAccountId($this->account->account_id)
            ->externalItemKey($externalItemKeys)
            ->getItemIds(true);
        foreach ($newMovementKeys as $newMovementKey) {
            $newStockMovement = $externalMovements[$newMovementKey];
            $externalItemKey = $newStockMovement[$this->stockItemKeyField];
            if (empty($itemIds[$externalItemKey])) {
                Yii::debug('Empty itemId of externalItemKey, skip', __METHOD__);
                continue;
            }
            $fulfillmentMovement = new FulfillmentWarehouseStockMovement();
            $fulfillmentMovement->fulfillment_account_id = $fulfillmentWarehouse->fulfillment_account_id;
            $fulfillmentMovement->external_movement_key = $newMovementKey;
            $fulfillmentMovement->external_warehouse_key = $fulfillmentWarehouse->external_warehouse_key;
            $fulfillmentMovement->external_item_key = $externalItemKey;
            $fulfillmentMovement->warehouse_id = $fulfillmentWarehouse->warehouse_id;
            $fulfillmentMovement->item_id = $itemIds[$externalItemKey];
            $this->updateFulfillmentWarehouseStockMovements($fulfillmentMovement, $newStockMovement);
        }
        $this->updateFulfillmentWarehouseExternalMovementTime($fulfillmentWarehouse, $externalMovements);
    }

    /**
     * @param FulfillmentWarehouse $fulfillmentWarehouse
     * @return array
     * @inheritdoc
     */
    abstract protected function getExternalWarehouseStockMovements(FulfillmentWarehouse $fulfillmentWarehouse, int $movementAtFrom, int $movementAtTo, ?FulfillmentItem $fulfillmentItem = null): array;

    /**
     * @param FulfillmentWarehouseStockMovement $fulfillmentStockMovement
     * @param array $externalStockMovement
     * @return bool
     * @inheritdoc
     */
    abstract protected function updateFulfillmentWarehouseStockMovements(FulfillmentWarehouseStockMovement $fulfillmentStockMovement, array $externalStockMovement): bool;

    /**
     * @param FulfillmentWarehouse $fulfillmentWarehouse
     * @param array $externalMovements
     * @return bool
     * @inheritdoc
     */
    protected function updateFulfillmentWarehouseExternalMovementTime(FulfillmentWarehouse $fulfillmentWarehouse, array $externalMovements): bool
    {
        $newestMovementTime = max(ArrayHelper::getColumn($externalMovements, $this->externalMovementTimeField));
        if (is_numeric($newestMovementTime)) {
            if (strlen($newestMovementTime) > 10) {
                $newestMovementTime = substr($newestMovementTime, 0, 10);
            }
        } else {
            $newestMovementTime = strtotime($newestMovementTime);
        }
        $fulfillmentWarehouse->external_movement_at = $newestMovementTime;
        return $fulfillmentWarehouse->save(false);
    }

    #endregion
}
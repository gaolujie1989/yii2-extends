<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment;

use lujie\data\loader\DataLoaderInterface;
use lujie\fulfillment\common\Item;
use lujie\fulfillment\common\Order;
use lujie\fulfillment\constants\FulfillmentConst;
use lujie\fulfillment\models\FulfillmentAccount;
use lujie\fulfillment\models\FulfillmentItem;
use lujie\fulfillment\models\FulfillmentOrder;
use lujie\fulfillment\models\FulfillmentWarehouse;
use lujie\fulfillment\models\FulfillmentWarehouseStock;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * Class BaseFulfillmentService
 * @package lujie\fulfillment
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseFulfillmentService extends BaseObject implements FulfillmentServiceInterface
{
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
            return false;
        }

        /** @var Item $item */
        $item = $this->itemLoader->get($fulfillmentItem->item_id);
        if (empty($item) || empty($item->itemBarcodes)) {
            return false;
        }

        if (empty($fulfillmentItem->item_pushed_at) && $externalItem = $this->getExternalItem($item)) {
            $this->updateFulfillmentItem($fulfillmentItem, $externalItem);
        }

        $externalItem = $this->formatExternalItemData($item, $fulfillmentItem);
        if ($externalItem = $this->saveExternalItem($externalItem, $fulfillmentItem)) {
            return $this->updateFulfillmentItem($fulfillmentItem, $externalItem);
        }
        return false;
    }

    /**
     * transform item to external item data
     * @param Item $item
     * @param FulfillmentItem $fulfillmentItem
     * @return array
     * @inheritdoc
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
            return false;
        }

        /** @var Order $order */
        $order = $this->orderLoader->get($fulfillmentOrder->order_id);
        if (empty($order) || empty($order->orderItems)) {
            return false;
        }
        if (empty($fulfillmentOrder->order_pushed_at) && $externalOrder = $this->getExternalOrder($order)) {
            $this->updateFulfillmentOrder($fulfillmentOrder, $externalOrder);
        }

        $externalOrder = $this->formatExternalOrderData($order, $fulfillmentOrder);
        if ($externalOrder = $this->saveExternalOrder($externalOrder, $fulfillmentOrder)) {
            return $this->updateFulfillmentOrder($fulfillmentOrder, $externalOrder);
        }
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
     */
    protected function updateFulfillmentOrder(FulfillmentOrder $fulfillmentOrder, array $externalOrder): bool
    {
        $fulfillmentOrder->external_order_key = $externalOrder[$this->externalOrderKeyField];

        $newFulfillmentStatus = $this->fulfillmentStatusMap[$fulfillmentOrder->external_order_status] ?? null;
        if ($newFulfillmentStatus === FulfillmentConst::FULFILLMENT_STATUS_SHIPPED
            && empty($fulfillmentOrder->external_order_additional['trackingNumbers'])) {
            $newFulfillmentStatus = FulfillmentConst::FULFILLMENT_STATUS_PICKING;
        }
        if ($newFulfillmentStatus) {
            $fulfillmentOrder->fulfillment_status = $newFulfillmentStatus;
        }

        return $fulfillmentOrder->save(false);
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

    #endregion

    #region Stock Pull

    /**
     * @param array $condition
     * @throws InvalidConfigException
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
     * @inheritdoc
     */
    protected function updateFulfillmentWarehouse(FulfillmentWarehouse $fulfillmentWarehouse, array $externalWarehouse): bool
    {
        return $fulfillmentWarehouse->save(false);
    }

    /**
     * @param FulfillmentItem $fulfillmentItems
     * @inheritdoc
     */
    public function pullWarehouseStocks(array $fulfillmentItems): void
    {
        $warehouseIds = FulfillmentWarehouse::find()
            ->fulfillmentAccountId($this->account->account_id)
            ->getWarehouseIdsIndexByExternalWarehouseKey();
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
            if (empty($warehouseIds[$externalWarehouseKey])) {
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
}
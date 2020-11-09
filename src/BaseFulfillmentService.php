<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment;

use lujie\data\loader\DataLoaderInterface;
use lujie\fulfillment\common\Item;
use lujie\fulfillment\common\Order;
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

    /**
     * @var string
     */
    public $externalWarehouseIdField = 'id';

    /**
     * @var string
     */
    public $externalWarehouseCodeField;

    /**
     * @var string
     */
    public $externalItemIdField = 'id';

    /**
     * @var string
     */
    public $externalItemNoField;

    /**
     * @var string
     */
    public $externalOrderIdField = 'id';

    /**
     * @var string
     */
    public $externalOrderNoField;

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
        if ($fulfillmentItem->fulfillment_account_id !== $this->account->fulfillment_account_id) {
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
            $this->updateFulfillmentItem($fulfillmentItem, $externalItem);
        }
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
     */
    abstract protected function updateFulfillmentItem(FulfillmentItem $fulfillmentItem, array $externalItem): void;

    #endregion

    #region Order Push

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool
     * @inheritdoc
     */
    public function pushFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        if ($fulfillmentOrder->fulfillment_account_id !== $this->account->fulfillment_account_id) {
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
            $this->updateFulfillmentOrder($fulfillmentOrder, $externalOrder);
        }
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
     */
    abstract protected function updateFulfillmentOrder(FulfillmentOrder $fulfillmentOrder, array $externalOrder): void;

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

    public function pullFulfillmentOrders(array $fulfillmentOrders): void
    {
        // TODO: Implement pullFulfillmentOrders() method.
    }

    #endregion

    #region Warehouse Stock Pull

    /**
     * @param array $condition
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function pullWarehouses(array $condition = []): void
    {
        $externalWarehouses = $this->getExternalWarehouses($condition);
        foreach ($externalWarehouses as $externalWarehouse) {
            $query = FulfillmentWarehouse::find()
                ->fulfillmentAccountId($this->account->fulfillment_account_id);
            if ($this->externalWarehouseIdField) {
                $query->externalWarehouseId($externalWarehouse[$this->externalWarehouseIdField]);
            } else if ($this->externalWarehouseCodeField) {
                $query->externalWarehouseName($externalWarehouse[$this->externalWarehouseCodeField]);
            } else {
                throw new InvalidConfigException('Missing external warehouse unique key field');
            }
            $fulfillmentWarehouse = $query->one();
            if ($fulfillmentWarehouse === null) {
                $fulfillmentWarehouse = new FulfillmentWarehouse();
                $fulfillmentWarehouse->fulfillment_account_id = $this->account->fulfillment_account_id;
                if ($this->externalWarehouseIdField) {
                    $fulfillmentWarehouse->external_warehouse_id = $externalWarehouse[$this->externalWarehouseIdField];
                } else if ($this->externalWarehouseCodeField) {
                    $fulfillmentWarehouse->external_warehouse_name = $externalWarehouse[$this->externalWarehouseCodeField];
                }
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
    abstract protected function updateFulfillmentWarehouse(FulfillmentWarehouse $fulfillmentWarehouse, array $externalWarehouse): bool;

    /**
     * @param FulfillmentItem $fulfillmentItems
     * @inheritdoc
     */
    public function pullWarehouseStocks(array $fulfillmentItems): void
    {
        $query = FulfillmentWarehouse::find()
            ->fulfillmentAccountId($this->account->fulfillment_account_id);

        if ($this->externalWarehouseIdField) {
            $warehouseIds = $query->getWarehouseIdsIndexByExternalWarehouseId();
            $externalWarehouseIds = array_keys($warehouseIds);
            $externalItemId2ItemIds = ArrayHelper::map($fulfillmentItems, 'external_item_id', 'item_id');
            $externalItemIds = array_keys($externalItemId2ItemIds);

            $fulfillmentWarehouseStocks = FulfillmentWarehouseStock::find()
                ->fulfillmentAccountId($this->account->fulfillment_account_id)
                ->externalWarehouseId($externalWarehouseIds)
                ->externalItemId($externalItemIds)
                ->indexBy(static function ($model) {
                    /** @var FulfillmentWarehouseStock $model */
                    return $model->external_warehouse_id . '-' . $model->external_item_id;
                })
                ->all();

        } else if ($this->externalWarehouseCodeField) {
            $warehouseIds = $query->getWarehouseIdsIndexByExternalWarehouseName();
            $externalWarehouseCodes = array_keys($warehouseIds);
        }

        $now = time();
        $externalItemId2ItemIds = ArrayHelper::map($fulfillmentItems, 'external_item_id', 'item_id');
        $externalItemIds = array_keys($externalItemId2ItemIds);
        $fulfillmentWarehouseStocks = FulfillmentWarehouseStock::find()
            ->fulfillmentAccountId($this->account->fulfillment_account_id)
            ->externalWarehouseId($externalWarehouseIds)
            ->externalItemId($externalItemIds)
            ->indexBy(static function ($model) {
                /** @var FulfillmentWarehouseStock $model */
                return $model->external_warehouse_id . '-' . $model->external_item_id;
            })
            ->all();

        $externalWarehouseStocks = $this->getExternalWarehouseStocks($externalItemIds);
        foreach ($externalWarehouseStocks as $externalWarehouseStock) {
            if (empty($warehouseIds[$externalWarehouseStock['warehouseId']])) {
                continue;
            }
            $key = $externalWarehouseStock['warehouseId'] . '-' . $externalWarehouseStock['variationId'];
            $fulfillmentWarehouseStock = $fulfillmentWarehouseStocks[$key] ?? new FulfillmentWarehouseStock();
            $this->updateFulfillmentWarehouseStock($fulfillmentWarehouseStock, $externalWarehouseStock);
        }
        $pulledExternalItemIds = ArrayHelper::getColumn($externalWarehouseStocks, 'variationId');
        $notPulledExternalItemIds = array_diff($externalItemIds, $pulledExternalItemIds);

        if ($notPulledExternalItemIds) {
            FulfillmentWarehouseStock::deleteAll([
                'fulfillment_account_id' => $this->account->fulfillment_account_id,
                'external_warehouse_id' => $externalWarehouseIds,
                'external_item_id' => $notPulledExternalItemIds,
            ]);
        }
        FulfillmentItem::updateAll(['stock_pulled_at' => $now], [
            'fulfillment_account_id' => $this->account->fulfillment_account_id,
            'external_item_id' => $externalItemIds
        ]);
    }

    /**
     * @param array $externalItemIds
     * @return array
     */
    abstract protected function getExternalWarehouseStocks(array $externalItemIds): array;

    /**
     * @param FulfillmentWarehouseStock $fulfillmentWarehouseStock
     * @param array $externalWarehouseStock
     * @return bool
     */
    abstract protected function updateFulfillmentWarehouseStock(FulfillmentWarehouseStock $fulfillmentWarehouseStock, array $externalWarehouseStock): bool;

    #endregion
}
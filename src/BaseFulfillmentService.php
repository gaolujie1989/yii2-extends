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
use lujie\plentyMarkets\PlentyMarketsRestClient;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\di\Instance;

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
        if (empty($fulfillmentItem->external_item_id) && $externalItem = $this->getExternalItem($item)) {
            $this->updateFulfillmentItem($fulfillmentItem, $externalItem);
        }

        $externalItem = $this->formatExternalItemData($item);
        if ($externalItem = $this->saveExternalItem($externalItem, $fulfillmentItem)) {
            $this->updateFulfillmentItem($fulfillmentItem, $externalItem);
        }
    }

    /**
     * transform item to external item data
     * @param Item $item
     * @return array
     */
    abstract protected function formatExternalItemData(Item $item): array;

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
     * update fulfillment item info, like external itemId, itemNo, extra...
     * @param FulfillmentItem $fulfillmentItem
     * @param array $externalItem
     */
    abstract protected function updateFulfillmentItem(FulfillmentItem $fulfillmentItem,  array $externalItem): void;

    #endregion

    #region Order Push

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


    }

    /**
     * transform item to external item data
     * @param Item $item
     * @return array
     */
    abstract protected function formatExternalOrderData(Item $item): array;

    /**
     * get external item by item with item_no or barcode
     * @param Item $item
     * @return array|null
     */
    abstract protected function getExternalOrder(Item $item): ?array;

    /**
     * save external item
     * @param array $externalItem
     * @param FulfillmentItem $fulfillmentItem
     * @return array|null
     */
    abstract protected function saveExternalOrder(array $externalItem, FulfillmentItem $fulfillmentItem): ?array;

    /**
     * update fulfillment item info, like external itemId, itemNo, extra...
     * @param FulfillmentItem $fulfillmentItem
     * @param array $externalItem
     */
    abstract protected function updateFulfillmentOrder(FulfillmentItem $fulfillmentItem,  array $externalItem): void;

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

    public function pullFulfillmentOrders(array $fulfillmentOrders): void
    {
        // TODO: Implement pullFulfillmentOrders() method.
    }

    #endregion

    #region Warehouse Stock Pull

    public function pullWarehouses(array $condition = []): void
    {
        // TODO: Implement pullWarehouses() method.
    }

    public function pullWarehouseStocks(array $fulfillmentItems): void
    {
        // TODO: Implement pullWarehouseStocks() method.
    }

    #endregion
}
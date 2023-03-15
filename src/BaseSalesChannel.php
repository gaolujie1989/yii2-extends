<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel;

use lujie\data\exchange\transformers\TransformerInterface;
use lujie\data\storage\DataStorageInterface;
use lujie\extend\constants\ExecStatusConst;
use lujie\sales\channel\constants\SalesChannelConst;
use lujie\sales\channel\events\SalesChannelOrderEvent;
use lujie\sales\channel\models\SalesChannelAccount;
use lujie\sales\channel\models\SalesChannelItem;
use lujie\sales\channel\models\SalesChannelOrder;
use Yii;
use yii\base\Component;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\base\UserException;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * Class BaseSalesChannel
 * @package lujie\sales\channel
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseSalesChannel extends Component implements SalesChannelInterface
{
    public const EVENT_AFTER_SALES_CHANNEL_ORDER_UPDATED = 'SALES_CHANNEL_ORDER_UPDATED';

    /**
     * @var SalesChannelAccount
     */
    public $account;

    /**
     * @var TransformerInterface
     */
    public $itemTransformer;

    /**
     * @var TransformerInterface
     */
    public $itemStockTransformer;

    /**
     * @var TransformerInterface
     */
    public $orderTransformer;

    /**
     * @var DataStorageInterface
     */
    public $orderDataStorage;

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
     * [
     *      'external_order_status' => 'sales_channel_status'
     * ]
     * @var array
     */
    public $salesChannelStatusMap = [
        'wait_payment' => SalesChannelConst::CHANNEL_STATUS_WAIT_PAYMENT,
        'paid' => SalesChannelConst::CHANNEL_STATUS_PAID,
        'pending' => SalesChannelConst::CHANNEL_STATUS_PENDING,
        'shipped' => SalesChannelConst::CHANNEL_STATUS_SHIPPED,
        'cancelled' => SalesChannelConst::CHANNEL_STATUS_CANCELLED,
    ];

    /**
     * @var array[]
     */
    public $salesChannelStatusActionTransitions = [
        SalesChannelConst::CHANNEL_STATUS_TO_SHIPPED => [
            SalesChannelConst::CHANNEL_STATUS_SHIPPED,
            SalesChannelConst::CHANNEL_STATUS_CANCELLED,
        ],
        SalesChannelConst::CHANNEL_STATUS_TO_CANCELLED => [
            SalesChannelConst::CHANNEL_STATUS_SHIPPED,
            SalesChannelConst::CHANNEL_STATUS_CANCELLED,
        ],
    ];

    #endregion

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if (!($this->account instanceof SalesChannelAccount)) {
            throw new InvalidConfigException('The property `account` can not be null and must be SalesChannelAccount');
        }
        $this->itemTransformer = Instance::ensure($this->itemTransformer, TransformerInterface::class);
        if (property_exists($this->itemTransformer, 'salesChannel')) {
            $this->itemTransformer->salesChannel = $this;
        }
        $this->itemStockTransformer = Instance::ensure($this->itemTransformer, TransformerInterface::class);
        if (property_exists($this->itemStockTransformer, 'salesChannel')) {
            $this->itemStockTransformer->salesChannel = $this;
        }
        $this->orderTransformer = Instance::ensure($this->itemTransformer, TransformerInterface::class);
        if (property_exists($this->orderTransformer, 'salesChannel')) {
            $this->orderTransformer->salesChannel = $this;
        }
        if ($this->orderDataStorage) {
            $this->orderDataStorage = Instance::ensure($this->orderDataStorage, DataStorageInterface::class);
        }
    }

    #region Order Pull

    /**
     * @param array $salesChannelOrders
     * @throws InvalidConfigException
     * @throws \Throwable
     * @inheritdoc
     */
    public function pullSalesOrders(array $salesChannelOrders): void
    {
        $salesChannelOrders = ArrayHelper::index($salesChannelOrders, 'external_order_key');
        $externalOrderKeys = array_keys($salesChannelOrders);
        $externalOrders = $this->getExternalOrders($externalOrderKeys);
        foreach ($externalOrders as $externalOrder) {
            $externalOrderKey = $externalOrder[$this->externalOrderKeyField];
            $this->updateSalesChannelOrder($salesChannelOrders[$externalOrderKey], $externalOrder);
        }
    }

    /**
     * @return array
     * @inheritdoc
     */
    abstract protected function getExternalOrders(array $externalOrderKeys): array;

    /**
     * @param int $createdAtFrom
     * @param int $createdAtTo
     * @throws InvalidConfigException
     * @throws \Throwable
     * @inheritdoc
     */
    public function pullNewSalesOrders(int $createdAtFrom, int $createdAtTo): void
    {
        $externalOrders = $this->getNewExternalOrders($createdAtFrom, $createdAtTo);
        if (empty($externalOrders)) {
            return;
        }
        $externalOrders = ArrayHelper::index($externalOrders, $this->externalOrderKeyField);
        $externalOrderKeys = array_keys($externalOrders);
        $salesChannelOrders = SalesChannelOrder::find()
            ->salesChannelAccountId($this->account->account_id)
            ->externalOrderKey($externalOrderKeys)
            ->indexBy('external_order_key')
            ->all();
        foreach ($externalOrders as $externalOrderKey => $externalOrder) {
            $salesChannelOrder = $salesChannelOrders[$externalOrderKey] ?? new SalesChannelOrder();
            if ($salesChannelOrder->getIsNewRecord()) {
                $salesChannelOrder->sales_channel_account_id = $this->account->account_id;
            }
            $this->updateSalesChannelOrder($salesChannelOrder, $externalOrder);
        }
    }

    /**
     * @param int $createdAtFrom
     * @param int $createdAtTo
     * @return array
     * @inheritdoc
     */
    abstract protected function getNewExternalOrders(int $createdAtFrom, int $createdAtTo): array;

    /**
     * update sales channel order info, like external order_id, order_no, extra...
     * @param SalesChannelOrder $salesChannelOrder
     * @param array $externalOrder
     * @param bool $changeActionStatus
     * @return bool
     * @throws InvalidConfigException
     * @throws \Throwable
     * @inheritdoc
     */
    protected function updateSalesChannelOrder(SalesChannelOrder $salesChannelOrder, array $externalOrder, bool $changeActionStatus = false): bool
    {
        $salesChannelOrder->order_pulled_at = time();
        $salesChannelOrder->external_order_key = $externalOrder[$this->externalOrderKeyField];
        $salesChannelOrder->external_order_status = (string)$externalOrder[$this->externalOrderStatusField];

        $newSalesChannelStatus = $this->getSalesChannelStatus($salesChannelOrder->external_order_status);
        if ($newSalesChannelStatus) {
            $statusTransitions = $this->salesChannelStatusActionTransitions[$salesChannelOrder->sales_channel_status] ?? null;
            if ($statusTransitions === null
                || ($changeActionStatus && in_array((int)$newSalesChannelStatus, $statusTransitions, true))) {
                $salesChannelOrder->sales_channel_status = $newSalesChannelStatus;
            }
        }
        return SalesChannelOrder::getDb()->transaction(function () use ($salesChannelOrder, $externalOrder) {
            if ($salesChannelOrder->save(false)) {
                $this->orderDataStorage?->set($salesChannelOrder, $externalOrder);
                $this->triggerSalesChannelOrderEvent($salesChannelOrder, $externalOrder);
                return true;
            }
            return false;
        });
    }

    /**
     * @param string $externalOrderStatus
     * @return int|null
     * @inheritdoc
     */
    protected function getSalesChannelStatus(string $externalOrderStatus): ?int
    {
        return $this->salesChannelStatusMap[$salesChannelOrder->external_order_status] ?? null;
    }

    /**
     * @param SalesChannelOrder $salesChannelOrder
     * @param array $externalOrder
     * @inheritdoc
     */
    protected function triggerSalesChannelOrderEvent(SalesChannelOrder $salesChannelOrder, array $externalOrder): void
    {
        $event = new SalesChannelOrderEvent();
        $event->salesChannelOrder = $salesChannelOrder;
        $event->externalOrder = $externalOrder;
        $this->trigger(self::EVENT_AFTER_SALES_CHANNEL_ORDER_UPDATED, $event);
    }

    #endregion

    #region Order Push

    /**
     * @param SalesChannelOrder $channelOrder
     * @return bool
     * @throws InvalidConfigException
     * @throws UserException
     * @throws \Throwable
     * @inheritdoc
     * @deprecated
     */
    public function shipSalesOrder(SalesChannelOrder $channelOrder): bool
    {
        return $this->pushSalesOrder($channelOrder);
    }

    /**
     * @param SalesChannelOrder $channelOrder
     * @return bool
     * @throws InvalidConfigException
     * @throws UserException
     * @throws \Throwable
     * @inheritdoc
     * @deprecated
     */
    public function cancelSalesOrder(SalesChannelOrder $channelOrder): bool
    {
        return $this->pushSalesOrder($channelOrder);
    }

    /**
     * @param SalesChannelOrder $salesChannelOrder
     * @return bool
     * @throws InvalidConfigException
     * @throws UserException
     * @throws \Throwable
     * @inheritdoc
     */
    public function pushSalesOrder(SalesChannelOrder $salesChannelOrder): bool
    {
        if (!$this->validateSalesChannelAccount($salesChannelOrder)) {
            return false;
        }

        $externalOrderId = $salesChannelOrder->external_order_key;
        if (empty($externalOrderId)) {
            return false;
        }

        $salesChannelStatus = $salesChannelOrder->sales_channel_status;
        if (!isset($this->salesChannelStatusActionTransitions[$salesChannelStatus])) {
            return false;
        }

        $externalOrder = $this->getExternalOrder($externalOrderId);
        $externalOrderStatus = (string)$externalOrder[$this->externalOrderStatusField];
        $newSalesChannelStatus = $this->getSalesChannelStatus($externalOrderStatus);

        if ($salesChannelStatus === SalesChannelConst::CHANNEL_STATUS_TO_SHIPPED) {
            if ($newSalesChannelStatus === SalesChannelConst::CHANNEL_STATUS_CANCELLED) {
                $this->updateSalesChannelOrder($salesChannelOrder, $externalOrder);
                throw new UserException("Sales order {$externalOrderId} is cancelled, can not be shipped");
            }
        } else if ($salesChannelStatus === SalesChannelConst::CHANNEL_STATUS_TO_CANCELLED) {
            if ($newSalesChannelStatus === SalesChannelConst::CHANNEL_STATUS_SHIPPED) {
                $this->updateSalesChannelOrder($salesChannelOrder, $externalOrder);
                throw new UserException("Sales order {$externalOrderId} is shipped, can not be cancelled");
            }
        } else {
            return false;
        }

        [$transformedExternalOrder] = $this->orderTransformer->transform([$salesChannelOrder]);
        if (empty($transformedExternalOrder)) {
            $message = "Empty transformed external order of channel order {$salesChannelOrder->sales_channel_order_id}";
            $salesChannelOrder->addError('order_id', $message);
            return false;
        }

        if ($externalOrder = $this->saveExternalOrder($transformedExternalOrder, $salesChannelOrder)) {
            Yii::info("Order pushed success, update SalesChannelItem", __METHOD__);
            return $this->updateSalesChannelOrder($salesChannelOrder, $externalOrder, true);
        }
        Yii::warning("Order pushed failed, skip update SalesChannelItem", __METHOD__);
        return false;
    }

    /**
     * @param string $externalOrderId
     * @return array|null
     */
    abstract protected function getExternalOrder(string $externalOrderId): ?array;

    /**
     * @param array $externalOrder
     * @param SalesChannelOrder $salesChannelOrder
     * @return array|null
     * @inheritdoc
     */
    abstract protected function saveExternalOrder(array $externalOrder, SalesChannelOrder $salesChannelOrder): ?array;

    #endregion

    #region Item push

    /**
     * @param SalesChannelItem $salesChannelItem
     * @return bool
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function pushSalesItem(SalesChannelItem $salesChannelItem): bool
    {
        if (!$this->validateSalesChannelAccount($salesChannelItem)) {
            return false;
        }

        [$externalItem] = $this->itemTransformer->transform([$salesChannelItem]);
        if (empty($externalItem)) {
            $message = "Empty transformed external item of channel item: {$salesChannelItem->sales_channel_item_id}";
            $salesChannelItem->addError('item_id', $message);
            return false;
        }
        if (isset($externalItem['errors']) && is_array($externalItem['errors'])) {
            $salesChannelItem->addErrors($externalItem['errors']);
            return false;
        }

        if (empty($salesChannelItem->external_item_key) && $externalExistsItem = $this->getExternalItem($externalItem)) {
            $existExternalItemKey = $externalExistsItem[$this->externalItemKeyField];
            $existSalesChannelItem = SalesChannelItem::find()
                ->salesChannelAccountId($salesChannelItem->sales_channel_account_id)
                ->itemType($salesChannelItem->item_type)
                ->externalItemKey($existExternalItemKey)
                ->one();
            if ($existSalesChannelItem) {
                $message = "External item exist, but already link to item: {$existSalesChannelItem->item_id}";
                $salesChannelItem->addError('item_id', $message);
                return false;
            }
            Yii::info("Item not pushed, but exist in external, update SalesChannelItem", __METHOD__);
            $this->updateSalesChannelItem($salesChannelItem, $externalExistsItem);
            [$externalItem] = $this->itemTransformer->transform([$salesChannelItem]);
            if (empty($externalItem)) {
                $message = 'Empty transformed external item';
                $salesChannelItem->addError('item_id', $message);
                return false;
            }
        }

        if ($externalItem = $this->saveExternalItem($externalItem, $salesChannelItem)) {
            Yii::info("Item pushed success, update SalesChannelItem", __METHOD__);
            return $this->updateSalesChannelItem($salesChannelItem, $externalItem);
        }
        Yii::warning("Item pushed failed, skip update SalesChannelItem", __METHOD__);
        return false;
    }

    /**
     * @param string $itemType
     * @param array $itemIds
     * @param int $salesChannelAccountId
     * @param bool $force
     * @return array|null
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function pushTypeItems(string $itemType, array $itemIds, int $salesChannelAccountId, bool $force = false): ?array
    {
        if ($salesChannelAccountId !== $this->account->account_id) {
            Yii::info("SalesChannelItem account {$salesChannelAccountId} != account {$this->account->account_id}", __METHOD__);
            return null;
        }
        if (empty($itemIds)) {
            return [];
        }
        $pushedSalesChannelItems = SalesChannelItem::find()
            ->salesChannelAccountId($salesChannelAccountId)
            ->itemType($itemType)
            ->itemId($itemIds)
            ->indexByItemId()
            ->all();
        foreach ($pushedSalesChannelItems as $pushedSalesChannelItem) {
            if ($force || $pushedSalesChannelItem->item_pushed_status !== ExecStatusConst::EXEC_STATUS_SUCCESS) {
                if ($this->pushSalesItem($pushedSalesChannelItem)) {
                    $pushedSalesChannelItem->item_pushed_at = time();
                    $pushedSalesChannelItem->item_pushed_status = ExecStatusConst::EXEC_STATUS_SUCCESS;
                    $pushedSalesChannelItem->save(false);
                }
            }
        }
        $notPushedItemIds = array_diff($itemIds, array_keys($pushedSalesChannelItems));
        if (empty($notPushedItemIds)) {
            return $pushedSalesChannelItems;
        }
        $newPushedSalesChannelItems = [];
        foreach ($notPushedItemIds as $itemId) {
            $salesChannelItem = new SalesChannelItem();
            $salesChannelItem->sales_channel_account_id = $salesChannelAccountId;
            $salesChannelItem->item_type = $itemType;
            $salesChannelItem->item_id = $itemId;
            $salesChannelItem->save(false);
            if ($this->pushSalesItem($salesChannelItem)) {
                $salesChannelItem->item_pushed_at = time();
                $salesChannelItem->item_pushed_status = ExecStatusConst::EXEC_STATUS_SUCCESS;
                $salesChannelItem->save(false);
            }
            $newPushedSalesChannelItems[] = $salesChannelItem;
        }
        return array_merge($pushedSalesChannelItems, $newPushedSalesChannelItems);
    }

    /**
     * @param array $externalItem
     * @param SalesChannelItem $salesChannelItem
     * @return array|null
     * @inheritdoc
     */
    abstract protected function getExternalItem(array $externalItem): ?array;

    /**
     * @param array $externalItem
     * @param SalesChannelItem $salesChannelItem
     * @return array|null
     * @inheritdoc
     */
    abstract protected function saveExternalItem(array $externalItem, SalesChannelItem $salesChannelItem): ?array;

    /**
     * @param SalesChannelItem $salesChannelItem
     * @param array $externalItem
     * @return bool
     * @inheritdoc
     */
    protected function updateSalesChannelItem(SalesChannelItem $salesChannelItem, array $externalItem): bool
    {
        $salesChannelItem->external_item_key = $externalItem[$this->externalItemKeyField];
        $pushedParts = $salesChannelItem->item_pushed_parts ?: [];
        $isStockPushed = empty($pushedParts)
            || in_array(SalesChannelConst::ITEM_PUSH_PART_ALL, $pushedParts, true)
            || in_array(SalesChannelConst::ITEM_PUSH_PART_STOCK, $pushedParts, true);
        if ($salesChannelItem->item_pushed_status === ExecStatusConst::EXEC_STATUS_SUCCESS && $isStockPushed) {
            $salesChannelItem->stock_pushed_at = $salesChannelItem->item_pushed_at;
        }
        return $salesChannelItem->save(false);
    }

    /**
     * @param SalesChannelItem $salesChannelItem
     * @return array
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function checkSalesItemUpdated(SalesChannelItem $salesChannelItem): bool
    {
        throw new NotSupportedException();
    }

    #endregion

    #region Item Stock Push

    /**
     * @param array|SalesChannelItem[] $salesChannelItems
     * @return bool
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function pushSalesItemStocks(array $salesChannelItems): bool
    {
        if (!$this->validateSalesChannelItems($salesChannelItems)) {
            return false;
        }
        if (empty($this->itemStockTransformer)) {
            return false;
        }
        $this->itemStockTransformer = Instance::ensure($this->itemStockTransformer, TransformerInterface::class);
        $externalItemStocks = $this->itemStockTransformer->transform($salesChannelItems);
        if (empty($externalItemStocks)) {
            Yii::info("Transformed empty stocks", __METHOD__);
            return false;
        }
        $this->saveExternalItemStocks($externalItemStocks);
        foreach ($salesChannelItems as $salesChannelItem) {
            $salesChannelItem->stock_pushed_at = time();
            $salesChannelItem->save(false);
        }
        return true;
    }

    /**
     * @param array $externalItemStocks
     * @return array|null
     * @inheritdoc
     */
    abstract protected function saveExternalItemStocks(array $externalItemStocks): ?array;

    #endregion

    /**
     * @param SalesChannelItem|SalesChannelOrder $salesChannelItemOrOrder
     * @return bool
     * @inheritdoc
     */
    protected function validateSalesChannelAccount($salesChannelItemOrOrder): bool
    {
        if ($salesChannelItemOrOrder->sales_channel_account_id !== $this->account->account_id) {
            $message = "SalesChannelItemOrOrder {$salesChannelItemOrOrder->sales_channel_order_id} with other account";
            $salesChannelItemOrOrder->addError('sales_channel_account_id', $message);
            return false;
        }
        return true;
    }

    /**
     * @param array $salesChannelItems
     * @return bool
     * @inheritdoc
     */
    protected function validateSalesChannelItems(array $salesChannelItems): bool
    {
        $invalidItems = array_filter($salesChannelItems, function (SalesChannelItem $item) {
            return $item->sales_channel_account_id !== $this->account->account_id;
        });
        if ($invalidItems) {
            $invalidItemIds = implode(',', ArrayHelper::getColumn($invalidItems, 'sales_channel_item_id'));
            Yii::info("SalesChannelItem id:{$invalidItemIds} with other account", __METHOD__);
            return false;
        }
        if (empty($salesChannelItems)) {
            return false;
        }
        return true;
    }
}

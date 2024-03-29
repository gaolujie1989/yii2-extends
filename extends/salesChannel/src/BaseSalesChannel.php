<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel;

use lujie\data\exchange\transformers\TransformerInterface;
use lujie\data\storage\DataStorageInterface;
use lujie\extend\caching\CachingTrait;
use lujie\extend\constants\ExecStatusConst;
use lujie\extend\helpers\ExecuteHelper;
use lujie\extend\helpers\ValueHelper;
use lujie\sales\channel\constants\SalesChannelConst;
use lujie\sales\channel\events\SalesChannelOrderEvent;
use lujie\sales\channel\models\SalesChannelAccount;
use lujie\sales\channel\models\SalesChannelItem;
use lujie\sales\channel\models\SalesChannelOrder;
use Yii;
use yii\authclient\InvalidResponseException;
use yii\base\Component;
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
    use CachingTrait;

    public const EVENT_AFTER_SALES_CHANNEL_ORDER_UPDATED = 'AFTER_SALES_CHANNEL_ORDER_UPDATED';

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
    public $orderDataStorage = [
        'class' => SalesChannelOrderDataStorage::class,
    ];

    /**
     * @var string
     */
    public $useStorageOrderBefore = '-7 days';

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
    public $externalOrderCreatedAtField;

    /**
     * @var string
     */
    public $externalOrderUpdatedAtField;

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
        $transformerProperties = ['itemTransformer', 'itemStockTransformer', 'orderTransformer'];
        foreach ($transformerProperties as $transformerProperty) {
            $transformer = $this->{$transformerProperty};
            if ($transformer) {
                $transformer = Instance::ensure($transformer, TransformerInterface::class);
                if (property_exists($transformer, 'salesChannel')) {
                    $transformer->salesChannel = $this;
                }
                $this->{$transformerProperty} = $transformer;
            }
        }
        if ($this->orderDataStorage) {
            $this->orderDataStorage = array_merge([
                'salesChannelAccountId' => $this->account->account_id,
                'externalOrderKeyField' => $this->externalOrderKeyField,
                'externalOrderCreatedAtField' => $this->externalOrderCreatedAtField,
                'externalOrderUpdatedAtField' => $this->externalOrderUpdatedAtField,
            ], $this->orderDataStorage);
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
        $externalOrders = [];
        if ($this->orderDataStorage && $createdAtTo <= strtotime($this->useStorageOrderBefore)) {
            $externalOrders = $this->orderDataStorage->multiGet([$createdAtFrom, $createdAtTo]);
        }
        if (empty($externalOrders)) {
            $externalOrders = $this->getNewExternalOrders($createdAtFrom, $createdAtTo);
        }
        if (empty($externalOrders)) {
            return;
        }
        $this->orderDataStorage?->multiSet($externalOrders);
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
        if ($this->externalOrderCreatedAtField) {
            $salesChannelOrder->external_created_at = ValueHelper::formatDateTime($externalOrder[$this->externalOrderCreatedAtField]);
        }
        if ($this->externalOrderUpdatedAtField) {
            $salesChannelOrder->external_updated_at = ValueHelper::formatDateTime($externalOrder[$this->externalOrderUpdatedAtField]);
        }

        $newSalesChannelStatus = $this->getSalesChannelStatus($externalOrder);
        if ($newSalesChannelStatus) {
            $statusTransitions = $this->salesChannelStatusActionTransitions[$salesChannelOrder->sales_channel_status] ?? null;
            //如果现在的状态在没有在SalesChannelStatusActionTransitions中，直接更新
            //如果现在的在SalesChannelStatusActionTransitions中，说明系统正在执行动作，只运行动作中的状态更新
            if ($statusTransitions === null
                || ($changeActionStatus && in_array((int)$newSalesChannelStatus, $statusTransitions, true))) {
                $salesChannelOrder->sales_channel_status = $newSalesChannelStatus;
            }
        }
        if ($salesChannelOrder->save(false)) {
            $this->triggerSalesChannelOrderEvent($salesChannelOrder, $externalOrder);
            return true;
        }
        return false;
    }

    /**
     * @param array $externalOrder
     * @return int|null
     * @inheritdoc
     */
    protected function getSalesChannelStatus(array $externalOrder): ?int
    {
        $externalOrderStatus = (string)$externalOrder[$this->externalOrderStatusField];
        return $this->salesChannelStatusMap[$externalOrderStatus] ?? null;
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

        $externalOrderKey = $salesChannelOrder->external_order_key;
        if (empty($externalOrderKey)) {
            return false;
        }

        $salesChannelStatus = $salesChannelOrder->sales_channel_status;
        if (!isset($this->salesChannelStatusActionTransitions[$salesChannelStatus])) {
            return false;
        }

        $externalOrder = $this->getExternalOrder($externalOrderKey);
        $externalOrderStatus = (string)$externalOrder[$this->externalOrderStatusField];
        $newSalesChannelStatus = $this->getSalesChannelStatus($externalOrder);

        if ($salesChannelStatus === SalesChannelConst::CHANNEL_STATUS_TO_SHIPPED) {
            if ($newSalesChannelStatus === SalesChannelConst::CHANNEL_STATUS_CANCELLED) {
                $this->updateSalesChannelOrder($salesChannelOrder, $externalOrder);
                throw new UserException("Sales order {$externalOrderKey} is cancelled, can not be shipped");
            }
        } else if ($salesChannelStatus === SalesChannelConst::CHANNEL_STATUS_TO_CANCELLED) {
            if ($newSalesChannelStatus === SalesChannelConst::CHANNEL_STATUS_SHIPPED) {
                $this->updateSalesChannelOrder($salesChannelOrder, $externalOrder);
                throw new UserException("Sales order {$externalOrderKey} is shipped, can not be cancelled");
            }
        } else {
            return false;
        }

        if (empty($this->orderTransformer)) {
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
     * @param string $externalOrderKey
     * @return array|null
     */
    abstract protected function getExternalOrder(string $externalOrderKey): ?array;

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
     * @throws InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function pushSalesItem(SalesChannelItem $salesChannelItem): bool
    {
        if (!$this->validateSalesChannelAccount($salesChannelItem)) {
            return false;
        }
        if (empty($this->itemTransformer)) {
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

        try {
            if ($externalItem = $this->saveExternalItem($externalItem, $salesChannelItem)) {
                Yii::info("Item pushed success, update SalesChannelItem", __METHOD__);
                return $this->updateSalesChannelItem($salesChannelItem, $externalItem);
            }
            Yii::warning("Item pushed failed, skip update SalesChannelItem", __METHOD__);
            return false;
        } catch (InvalidResponseException $exception) {
            $response = $exception->response;
            $statusCode = (string)$response->getStatusCode();
            if ($statusCode === '422' || $statusCode === '404' || $statusCode[0] === '5') {
                $message = $exception->getMessage();
                $content = $response->getContent();
                $salesChannelItem->addError('item_id', $message);
                $salesChannelItem->addError('item_id', $content);
                Yii::error($message . "\n" . $content, __METHOD__);
                return false;
            }
            throw $exception;
        }
    }

    /**
     * @param string $itemType
     * @param array $itemIds
     * @param int $salesChannelAccountId
     * @param bool $force
     * @return array|null
     * @throws \Throwable
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
        foreach ($pushedSalesChannelItems as $salesChannelItem) {
            if ($force || $salesChannelItem->item_pushed_status !== ExecStatusConst::EXEC_STATUS_SUCCESS) {
                ExecuteHelper::execute(function () use ($salesChannelItem) {
                    if ($salesChannelItem->item_pushed_updated_after_at) {
                        $this->checkSalesItemUpdated($salesChannelItem);
                    } else {
                        $this->pushSalesItem($salesChannelItem);
                    }
                    Yii::info("SalesChannelItem {$salesChannelItem->sales_channel_item_id} pushed success", __METHOD__);
                }, $salesChannelItem, 'item_pushed_at', 'item_pushed_status', 'item_pushed_result');
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
            ExecuteHelper::execute(function () use ($salesChannelItem) {
                if ($salesChannelItem->item_pushed_updated_after_at) {
                    $this->checkSalesItemUpdated($salesChannelItem);
                } else {
                    $this->pushSalesItem($salesChannelItem);
                }
                Yii::info("SalesChannelItem {$salesChannelItem->sales_channel_item_id} pushed success", __METHOD__);
            }, $salesChannelItem, 'item_pushed_at', 'item_pushed_status', 'item_pushed_result');
            $newPushedSalesChannelItems[] = $salesChannelItem;
        }
        return array_merge($pushedSalesChannelItems, $newPushedSalesChannelItems);
    }

    /**
     * @param array $externalItem
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
        return $salesChannelItem->save(false);
    }

    /**
     * @param SalesChannelItem $salesChannelItem
     * @return bool
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

    #region Common Methods

    /**
     * @param SalesChannelItem|SalesChannelOrder $salesChannelItemOrOrder
     * @return bool
     * @inheritdoc
     */
    protected function validateSalesChannelAccount(SalesChannelItem|SalesChannelOrder $salesChannelItemOrOrder): bool
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

    #endregion
}

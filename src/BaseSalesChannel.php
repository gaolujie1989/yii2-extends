<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel;

use lujie\data\exchange\transformers\TransformerInterface;
use lujie\extend\constants\ExecStatusConst;
use lujie\sales\channel\constants\SalesChannelConst;
use lujie\sales\channel\events\SalesChannelOrderEvent;
use lujie\sales\channel\models\SalesChannelAccount;
use lujie\sales\channel\models\SalesChannelItem;
use lujie\sales\channel\models\SalesChannelOrder;
use spec\BackupManager\Procedures\BackupProcedureSpec;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
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
        $salesChannelOrder->external_order_status = $externalOrder[$this->externalOrderStatusField];

        $newSalesChannelStatus = $this->salesChannelStatusMap[$salesChannelOrder->external_order_status] ?? null;
        if ($newSalesChannelStatus) {
            $statusTransitions = $this->salesChannelStatusActionTransitions[$salesChannelOrder->sales_channel_status] ?? null;
            if ($statusTransitions === null
                ||($changeActionStatus && in_array((int)$newSalesChannelStatus, $statusTransitions, true))) {
                $salesChannelOrder->sales_channel_status = $newSalesChannelStatus;
            }
        }
        return SalesChannelOrder::getDb()->transaction(function () use ($salesChannelOrder, $externalOrder) {
            if ($salesChannelOrder->save(false)) {
                $this->triggerSalesChannelOrderEvent($salesChannelOrder, $externalOrder);
                return true;
            }
            return false;
        });
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

    #region Order Push ship/cancel

    /**
     * @param SalesChannelOrder $channelOrder
     * @return bool
     * @inheritdoc
     */
    abstract public function shipSalesOrder(SalesChannelOrder $channelOrder): bool;

    /**
     * @param SalesChannelOrder $channelOrder
     * @return bool
     * @inheritdoc
     */
    abstract public function cancelSalesOrder(SalesChannelOrder $channelOrder): bool;

    #endregion

    #region Item push

    /**
     * @var TransformerInterface
     */
    public $itemTransformer;

    /**
     * @param SalesChannelItem $salesChannelItem
     * @return bool
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function pushSalesItem(SalesChannelItem $salesChannelItem): bool
    {
        if ($salesChannelItem->sales_channel_account_id !== $this->account->account_id) {
            Yii::info("SalesChannelItem account {$salesChannelItem->sales_channel_account_id} != account {$this->account->account_id}", __METHOD__);
            return false;
        }

        $this->itemTransformer = Instance::ensure($this->itemTransformer, TransformerInterface::class);
        if (property_exists($this->itemTransformer, 'salesChannel')) {
            $this->itemTransformer->salesChannel = $this;
        }
        [$externalItem] = $this->itemTransformer->transform([$salesChannelItem]);
        if (empty($externalItem)) {
            Yii::info("Empty transformed external item", __METHOD__);
            return false;
        }

        if (empty($salesChannelItem->external_item_key) && $externalExistsItem = $this->getExternalItem($externalItem)) {
            Yii::info("Item not pushed, but exist in external, update SalesChannelItem", __METHOD__);
            $this->updateSalesChannelItem($salesChannelItem, $externalExistsItem);
            [$externalItem] = $this->itemTransformer->transform([$salesChannelItem]);
            if (empty($externalItem)) {
                Yii::info("Empty transformed external item", __METHOD__);
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
     * @return array|null
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function pushTypeItems(string $itemType, array $itemIds, int $salesChannelAccountId): ?array
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
            if ($pushedSalesChannelItem->item_pushed_status !== ExecStatusConst::EXEC_STATUS_SUCCESS) {
                $this->pushSalesItem($pushedSalesChannelItem);
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
            $this->pushSalesItem($salesChannelItem);
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
     * @var TransformerInterface
     */
    public $itemStockTransformer;

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
        $this->itemStockTransformer = Instance::ensure($this->itemStockTransformer, TransformerInterface::class);
        $externalItemStocks = $this->itemStockTransformer->transform($salesChannelItems);
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

<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel;

use lujie\data\loader\DataLoaderInterface;
use lujie\extend\constants\ExecStatusConst;
use lujie\extend\constants\StatusConst;
use lujie\sales\channel\constants\SalesChannelConst;
use lujie\sales\channel\events\SalesChannelOrderEvent;
use lujie\sales\channel\models\SalesChannelAccount;
use lujie\sales\channel\models\SalesChannelItem;
use lujie\sales\channel\models\SalesChannelOrder;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\db\BaseActiveRecord;
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
     * @var DataLoaderInterface
     */
    public $itemLoader;

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
        if ($this->itemLoader) {
            $this->itemLoader = Instance::ensure($this->itemLoader, DataLoaderInterface::class);
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

    #region Order Action ship/cancel

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
     * @param SalesChannelItem $salesChannelItem
     * @inheritdoc
     */
    public function pushSalesItem(SalesChannelItem $salesChannelItem): bool
    {
        if ($salesChannelItem->sales_channel_account_id !== $this->account->account_id) {
            Yii::info("SalesChannelItem account {$salesChannelItem->sales_channel_account_id} != account {$this->account->account_id}", __METHOD__);
            return false;
        }

        $item = $this->itemLoader->get($salesChannelItem);
        if ($item === null) {
            Yii::info("Empty Item", __METHOD__);
            return false;
        }

        $externalItem = $this->formatExternalItemData($item, $salesChannelItem);
        if (empty($salesChannelItem->external_item_key) && $externalExistsItem = $this->getExternalItem($externalItem)) {
            Yii::info("Item not pushed, but exist in external, update SalesChannelItem", __METHOD__);
            $this->updateSalesChannelItem($salesChannelItem, $externalExistsItem);
            $externalItem = $this->formatExternalItemData($item, $salesChannelItem);
        }

        if ($externalItem = $this->saveExternalItem($externalItem, $salesChannelItem)) {
            Yii::info("Item pushed success, update SalesChannelItem", __METHOD__);
            return $this->updateSalesChannelItem($salesChannelItem, $externalItem);
        }
    }

    /**
     * @param BaseActiveRecord $item
     * @param SalesChannelItem $item
     * @return array
     * @inheritdoc
     */
    abstract protected function formatExternalItemData(BaseActiveRecord $item, SalesChannelItem $salesChannelItem): array;

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
        $isStockPushed = in_array(SalesChannelConst::ITEM_PUSH_PART_ALL, $salesChannelItem->item_pushed_parts, true)
            || in_array(SalesChannelConst::ITEM_PUSH_PART_STOCK, $salesChannelItem->item_pushed_parts, true);
        if ($salesChannelItem->item_pushed_status === ExecStatusConst::EXEC_STATUS_SUCCESS && $isStockPushed) {
            $salesChannelItem->stock_pushed_at = $salesChannelItem->item_pushed_at;
        }
        return $salesChannelItem->save(false);
    }

    #endregion
}

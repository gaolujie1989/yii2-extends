<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel;

use lujie\data\loader\DataLoaderInterface;
use lujie\extend\constants\ExecStatusConst;
use lujie\extend\helpers\ComponentHelper;
use lujie\extend\helpers\ExecuteHelper;
use lujie\sales\channel\constants\SalesChannelConst;
use lujie\sales\channel\forms\SalesChannelItemForm;
use lujie\sales\channel\forms\SalesChannelOrderForm;
use lujie\sales\channel\jobs\PushSalesChannelItemJob;
use lujie\sales\channel\jobs\PushSalesChannelOrderJob;
use lujie\sales\channel\models\SalesChannelItem;
use lujie\sales\channel\models\SalesChannelOrder;
use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\base\Event;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\db\AfterSaveEvent;
use yii\db\BaseActiveRecord;
use yii\di\Instance;
use yii\mutex\Mutex;
use yii\queue\Queue;

/**
 * Class SalesChannelManager
 * @package lujie\sales\channel
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SalesChannelManager extends Component implements BootstrapInterface
{
    /**
     * @var DataLoaderInterface
     */
    public $salesChannelLoader = 'salesChannelLoader';

    /**
     * @var Queue
     */
    public $queue = 'queue';

    /**
     * @var array
     */
    public $salesChannelItemJob = [];

    /**
     * @var array
     */
    public $salesChannelOrderJob = [];

    /**
     * @var Mutex
     */
    public $mutex = 'mutex';

    /**
     * @var string
     */
    public $mutexNamePrefix = 'salesChannel:';

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->salesChannelLoader = Instance::ensure($this->salesChannelLoader, DataLoaderInterface::class);
        $this->queue = Instance::ensure($this->queue, Queue::class);
        $this->mutex = Instance::ensure($this->mutex, Mutex::class);
    }

    /**
     * @param int $channelAccountId
     * @return SalesChannelInterface|object
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function getSalesChannel(int $channelAccountId): SalesChannelInterface
    {
        /** @var ?SalesChannelInterface $salesChannel */
        $salesChannel = $this->salesChannelLoader->get($channelAccountId);
        if ($salesChannel === null) {
            throw new InvalidArgumentException("Null SalesChannel of {$channelAccountId}");
        }
        return Instance::ensure($salesChannel, SalesChannelInterface::class);
    }

    #region event, listen to push item/order

    /**
     * @param Application $app
     * @inheritdoc
     */
    public function bootstrap($app): void
    {
        Event::on(SalesChannelItemForm::class, BaseActiveRecord::EVENT_AFTER_INSERT, [$this, 'afterSalesChannelItemSaved']);
        Event::on(SalesChannelItemForm::class, BaseActiveRecord::EVENT_AFTER_UPDATE, [$this, 'afterSalesChannelItemSaved']);
        Event::on(SalesChannelOrderForm::class, BaseActiveRecord::EVENT_AFTER_UPDATE, [$this, 'afterSalesChannelOrderUpdated']);
    }

    /**
     * @param AfterSaveEvent $event
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function afterSalesChannelItemSaved(AfterSaveEvent $event): void
    {
        /** @var SalesChannelItemForm $channelOrderForm */
        $channelItemForm = $event->sender;
        //SalesChannelItemForm will trigger event, SalesChannelItem use instead
        $channelItem = new SalesChannelItem();
        $channelItem->setAttributes($channelItemForm->attributes, false);
        $channelItem->setIsNewRecord(false);
        if ($channelItem->item_pushed_at > $channelItem->item_updated_at) {
            Yii::info("SalesChannelItem {$channelItem->sales_channel_item_id} not updated, skip", __METHOD__);
            return;
        }
        $this->pushSalesChannelItemJob($channelItem);
    }

    /**
     * @param SalesChannelItem $salesChannelItem
     * @param int $delay
     * @return bool
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function pushSalesChannelItemJob(SalesChannelItem $salesChannelItem, int $delay = 0): bool
    {
        /** @var PushSalesChannelItemJob $job */
        $job = Instance::ensure($this->salesChannelItemJob, PushSalesChannelItemJob::class);
        $job->salesChannelManager = ComponentHelper::getName($this);
        $job->salesChannelItemId = $salesChannelItem->sales_channel_item_id;
        return ExecuteHelper::pushJob(
            $this->queue,
            $job,
            $salesChannelItem,
            'item_pushed_status',
            'item_pushed_result',
            'updated_at',
            3600,
            $delay
        );
    }

    /**
     * @param AfterSaveEvent $event
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function afterSalesChannelOrderUpdated(AfterSaveEvent $event): void
    {
        /** @var SalesChannelOrderForm $channelOrderForm */
        $channelOrderForm = $event->sender;
        //SalesChannelOrderForm will trigger event, SalesChannelOrder use instead
        $channelOrder = new SalesChannelOrder();
        $channelOrder->setAttributes($channelOrderForm->attributes, false);
        $channelOrder->setIsNewRecord(false);
        if (empty(SalesChannelConst::CHANNEL_ORDER_PUSHING_STATUS[$channelOrder->sales_channel_status])) {
            Yii::info("SalesChannelItem {$channelOrder->sales_channel_order_id} not in action status, skip", __METHOD__);
            return;
        }
        $this->pushSalesChannelOrderJob($channelOrder);
    }

    /**
     * @param SalesChannelOrder $channelOrder
     * @param array $jobConfig
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function pushSalesChannelOrderJob(SalesChannelOrder $channelOrder, array $jobConfig = []): bool
    {
        /** @var PushSalesChannelOrderJob $job */
        $job = Instance::ensure(array_merge($this->salesChannelOrderJob, $jobConfig), PushSalesChannelOrderJob::class);
        $job->salesChannelManager = ComponentHelper::getName($this);
        $job->salesChannelOrderId = $channelOrder->sales_channel_order_id;
        //always push job because order may be change multi times with different data, so need to push different job
        return ExecuteHelper::pushJob(
            $this->queue,
            $job,
            $channelOrder,
            'order_pushed_status',
            'order_pushed_result',
            'updated_at',
            0
        );
    }

    #endregion

    #region Order Pull

    /**
     * @param int $accountId
     * @param int $limit
     * @param int $batchSize
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function pullSalesChannelOrders(int $accountId, int $limit = 100, int $batchSize = 20): void
    {
        $query = SalesChannelOrder::find()
            ->salesChannelOrderId($accountId)
            ->needPull()
            ->orderByOrderPulledAt()
            ->limit($limit);
        if (!$query->exists()) {
            return;
        }
        $salesChannel = $this->getSalesChannel($accountId);
        foreach ($query->batch($batchSize) as $batch) {
            $salesChannel->pullSalesOrders($batch);
        }
    }

    /**
     * @param int $accountId
     * @param int|null $createdAtFrom
     * @param int|null $createdAtTo
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function pullNewSalesChannelOrders(int $accountId, ?int $createdAtFrom = null, ?int $createdAtTo = null): void
    {
        $salesChannel = $this->getSalesChannel($accountId);
        if ($createdAtFrom === null) {
            $createdAtFrom = SalesChannelOrder::find()
                ->salesChannelAccountId($accountId)
                ->maxExternalCreatedAt() ?: 0;
        }
        if ($createdAtTo === null) {
            $createdAtTo = time();
        }
        $salesChannel->pullNewSalesOrders($createdAtFrom, $createdAtTo);
    }

    #endregion

    #region Order Push ship/cancel

    /**
     * @param SalesChannelOrder $channelOrder
     * @return bool
     * @throws InvalidConfigException
     * @throws \Throwable
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function pushSalesChannelOrder(SalesChannelOrder $channelOrder): bool
    {
        if (empty(SalesChannelConst::CHANNEL_ORDER_PUSHING_STATUS[$channelOrder->sales_channel_status])) {
            Yii::info("SalesChannelOrder {$channelOrder->sales_channel_order_id} not in action status, skip", __METHOD__);
            $channelOrder->order_pushed_status = ExecStatusConst::EXEC_STATUS_SKIPPED;
            $channelOrder->mustSave(false);
            return false;
        }

        $salesChannel = $this->getSalesChannel($channelOrder->sales_channel_account_id);
        $lockName = $this->mutexNamePrefix . 'pushSalesChannelOrder:' . $channelOrder->sales_channel_order_id;
        if ($this->mutex->acquire($lockName)) {
            try {
                return ExecuteHelper::execute(static function () use ($salesChannel, $channelOrder) {
                    if ($channelOrder->sales_channel_status === SalesChannelConst::CHANNEL_STATUS_TO_SHIPPED) {
                        $salesChannel->shipSalesOrder($channelOrder);
                        Yii::info("SalesChannelOrder {$channelOrder->sales_channel_order_id} to shipped success", __METHOD__);
                    } else if ($channelOrder->sales_channel_status === SalesChannelConst::CHANNEL_STATUS_TO_CANCELLED) {
                        $salesChannel->cancelSalesOrder($channelOrder);
                        Yii::info("SalesChannelOrder {$channelOrder->sales_channel_order_id} to cancelled success", __METHOD__);
                    }
                }, $channelOrder, 'order_pushed_at', 'order_pushed_status', 'order_pushed_result');
            } finally {
                $this->mutex->release($lockName);
            }
        }
        return false;
    }

    #endregion

    #region Item Push

    /**
     * @param SalesChannelItem $salesChannelItem
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function pushSalesChannelItem(SalesChannelItem $salesChannelItem): bool
    {
        if ($salesChannelItem->external_updated_at > $salesChannelItem->item_updated_at) {
            Yii::info("SalesChannelItem {$salesChannelItem->sales_channel_item_id} not new updated, skip", __METHOD__);
            $salesChannelItem->item_pushed_status = ExecStatusConst::EXEC_STATUS_SKIPPED;
            $salesChannelItem->mustSave(false);
            return false;
        }

        $salesChannel = $this->getSalesChannel($salesChannelItem->sales_channel_account_id);
        $lockName = $this->mutexNamePrefix . 'pushSalesChannelItem:' . $salesChannelItem->sales_channel_item_id;
        if ($this->mutex->acquire($lockName)) {
            try {
                $execute = ExecuteHelper::execute(static function () use ($salesChannel, $salesChannelItem) {
                    if ($salesChannelItem->item_pushed_updated_after_at) {
                        $salesChannel->checkSalesItemUpdated($salesChannelItem);
                    } else {
                        $salesChannel->pushSalesItem($salesChannelItem);
                    }
                    Yii::info("SalesChannelItem {$salesChannelItem->sales_channel_item_id} pushed success", __METHOD__);
                }, $salesChannelItem, 'item_pushed_at', 'item_pushed_status', 'item_pushed_result');
                if ($salesChannelItem->item_pushed_updated_after_at) {
                    $delay = $salesChannelItem->item_pushed_updated_after_at - time();
                    $this->pushSalesChannelItemJob($salesChannelItem, max(0, $delay));
                }
                return $execute;
            } finally {
                $this->mutex->release($lockName);
            }
        }
        return false;
    }

    /**
     * @param int $accountId
     * @param int $timePeriod
     * @param int $limit
     * @param int $batchSize
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function pushSalesChannelItemStocks(int $accountId, int $timePeriod = 3600, int $limit = 100, int $batchSize = 20): void
    {
        $query = SalesChannelItem::find()
            ->salesChannelAccountId($accountId)
            ->stockPushedAtBetween(0, time() - $timePeriod)
            ->orderByStockPushedAt()
            ->limit($limit);
        if (!$query->exists()) {
            return;
        }
        $salesChannel = $this->getSalesChannel($accountId);
        foreach ($query->batch($batchSize) as $batch) {
            $salesChannel->pushSalesItemStocks($batch);
        }
    }

    #endregion
}

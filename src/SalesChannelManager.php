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
use lujie\sales\channel\jobs\BaseSalesChannelOrderJob;
use lujie\sales\channel\jobs\CancelSalesChannelOrderJob;
use lujie\sales\channel\jobs\PushSalesChannelItemJob;
use lujie\sales\channel\jobs\ShipSalesChannelOrderJob;
use lujie\sales\channel\models\SalesChannelAccount;
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
     * @var int
     */
    public $pullOrderLimit = 100;

    /**
     * @var int
     */
    public $pullOrderBatchSize = 20;

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
     * @return SalesChannelInterface
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

    #region event, listen to update marketplace order

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
     * @return bool
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function pushSalesChannelItemJob(SalesChannelItem $salesChannelItem): bool
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
            'item_pushed_result'
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
        $name = "SalesChannelOrder {$channelOrder->sales_channel_order_id} of order {$channelOrder->order_id}";
        switch ($channelOrder->sales_channel_status) {
            case SalesChannelConst::CHANNEL_STATUS_TO_SHIPPED:
                $this->pushShipSalesChannelOrderJob($channelOrder);
                Yii::info("{$name} push ship job", __METHOD__);
                break;
            case SalesChannelConst::CHANNEL_STATUS_TO_CANCELLED:
                $this->pushCancelSalesChannelOrderJob($channelOrder);
                Yii::info("{$name} push cancel job", __METHOD__);
                break;
        }
    }

    /**
     * @param SalesChannelOrder $salesChannelOrder
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function pushShipSalesChannelOrderJob(SalesChannelOrder $salesChannelOrder): void
    {
        $this->pushSalesChannelOrderActionJob($salesChannelOrder, ['class' => ShipSalesChannelOrderJob::class]);
    }

    /**
     * @param SalesChannelOrder $salesChannelOrder
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function pushCancelSalesChannelOrderJob(SalesChannelOrder $salesChannelOrder): void
    {
        $this->pushSalesChannelOrderActionJob($salesChannelOrder, ['class' => CancelSalesChannelOrderJob::class]);
    }

    /**
     * @param SalesChannelOrder $channelOrder
     * @param array $jobConfig
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function pushSalesChannelOrderActionJob(SalesChannelOrder $channelOrder, array $jobConfig = []): bool
    {
        /** @var BaseSalesChannelOrderJob $job */
        $job = Instance::ensure(array_merge($this->salesChannelOrderJob, $jobConfig), BaseSalesChannelOrderJob::class);
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

    #region Order Action ship/cancel

    /**
     * @param SalesChannelOrder $channelOrder
     * @return bool
     * @throws InvalidConfigException
     * @throws \Throwable
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function shipSalesChannelOrder(SalesChannelOrder $channelOrder): bool
    {
        $name = "SalesChannelOrder {$channelOrder->sales_channel_order_id} of order {$channelOrder->order_id}";
        if ($channelOrder->sales_channel_status !== SalesChannelConst::CHANNEL_STATUS_TO_SHIPPED) {
            Yii::info("{$name} not to shipped, skip", __METHOD__);
            $channelOrder->order_pushed_status = ExecStatusConst::EXEC_STATUS_SKIPPED;
            $channelOrder->mustSave(false);
            return false;
        }

        $salesChannel = $this->getSalesChannel($channelOrder->sales_channel_account_id);
        $lockName = $this->mutexNamePrefix . 'shipSalesChannelOrder:' . $channelOrder->sales_channel_order_id;
        if ($this->mutex->acquire($lockName)) {
            try {
                return ExecuteHelper::execute(static function () use ($salesChannel, $channelOrder, $name) {
                    $salesChannel->shipSalesOrder($channelOrder);
                    Yii::info("{$name} to shipped success", __METHOD__);
                }, $channelOrder, 'order_pushed_at', 'order_pushed_status', 'order_pushed_result');
            } finally {
                $this->mutex->release($lockName);
            }
        }
        return false;
    }

    /**
     * @param SalesChannelOrder $channelOrder
     * @return bool
     * @throws InvalidConfigException
     * @throws \Throwable
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function cancelSalesChannelOrder(SalesChannelOrder $channelOrder): bool
    {
        $name = "SalesChannelOrder {$channelOrder->sales_channel_order_id} of order {$channelOrder->order_id}";
        if ($channelOrder->sales_channel_status !== SalesChannelConst::CHANNEL_STATUS_TO_CANCELLED) {
            Yii::info("{$name} not to cancelling, skip", __METHOD__);
            $channelOrder->order_pushed_status = ExecStatusConst::EXEC_STATUS_SKIPPED;
            $channelOrder->mustSave(false);
            return false;
        }

        $salesChannel = $this->getSalesChannel($channelOrder->sales_channel_account_id);
        $lockName = $this->mutexNamePrefix . 'cancelSalesChannelOrder:' . $channelOrder->sales_channel_order_id;
        if ($this->mutex->acquire($lockName)) {
            try {
                return ExecuteHelper::execute(static function () use ($salesChannel, $channelOrder, $name) {
                    $salesChannel->cancelSalesOrder($channelOrder);
                    Yii::info("{$name} cancelled success", __METHOD__);
                }, $channelOrder, 'order_pushed_at', 'order_pushed_status', 'order_pushed_result');
            } finally {
                $this->mutex->release($lockName);
            }
        }
        return false;
    }

    #endregion

    #region Order Pull

    /**
     * @param int $accountId
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function pullSalesChannelOrders(int $accountId): void
    {
        $query = SalesChannelOrder::find()
            ->salesChannelOrderId($accountId)
            ->pendingOrProcessing()
            ->orderByOrderPulledAt()
            ->limit($this->pullOrderLimit);
        if (!$query->exists()) {
            return;
        }
        $salesChannel = $this->getSalesChannel($accountId);
        foreach ($query->batch($this->pullOrderBatchSize) as $batch) {
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

    #region Recheck and Retry To Push

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function pushSalesChannelOrders(): void
    {
        $accountIds = SalesChannelAccount::find()->active()->column();

        $query = SalesChannelOrder::find()
            ->salesChannelAccountId($accountIds)
            ->toShipped()
            ->notQueuedOrQueuedButNotExecuted();
        foreach ($query->each() as $salesChannelOrder) {
            $this->pushShipSalesChannelOrderJob($salesChannelOrder);
        }

        $query = SalesChannelOrder::find()
            ->salesChannelAccountId($accountIds)
            ->toCancelled()
            ->notQueuedOrQueuedButNotExecuted();
        foreach ($query->each() as $salesChannelOrder) {
            $this->pushCancelSalesChannelOrderJob($salesChannelOrder);
        }
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
        $name = "SalesChannelItem {$salesChannelItem->sales_channel_item_id} of item {$salesChannelItem->item_id}";
        if ($salesChannelItem->external_updated_at > $salesChannelItem->item_updated_at) {
            Yii::info("{$name} already pushed, skip", __METHOD__);
            $salesChannelItem->item_pushed_status = ExecStatusConst::EXEC_STATUS_SKIPPED;
            $salesChannelItem->mustSave(false);
            return false;
        }

        $salesChannel = $this->getSalesChannel($salesChannelItem->sales_channel_account_id);
        $lockName = $this->mutexNamePrefix . 'pushSalesChannelItem:' . $salesChannelItem->sales_channel_item_id;
        if ($this->mutex->acquire($lockName)) {
            try {
                return ExecuteHelper::execute(static function () use ($salesChannel, $salesChannelItem, $name) {
                    $salesChannel->pushSalesItem($salesChannelItem);
                    Yii::info("{$name} pushed success", __METHOD__);
                }, $salesChannelItem, 'item_pushed_at', 'item_pushed_status', 'item_pushed_result');
            } finally {
                $this->mutex->release($lockName);
            }
        }
        return false;
    }

    #endregion
}

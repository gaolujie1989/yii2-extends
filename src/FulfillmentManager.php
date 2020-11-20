<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment;

use lujie\data\loader\DataLoaderInterface;
use lujie\extend\constants\ExecStatusConst;
use lujie\extend\helpers\ComponentHelper;
use lujie\extend\helpers\ExecuteHelper;
use lujie\fulfillment\constants\FulfillmentConst;
use lujie\fulfillment\forms\FulfillmentItemForm;
use lujie\fulfillment\forms\FulfillmentOrderForm;
use lujie\fulfillment\jobs\BaseFulfillmentOrderJob;
use lujie\fulfillment\jobs\CancelFulfillmentOrderJob;
use lujie\fulfillment\jobs\HoldFulfillmentOrderJob;
use lujie\fulfillment\jobs\PushFulfillmentItemJob;
use lujie\fulfillment\jobs\PushFulfillmentOrderJob;
use lujie\fulfillment\jobs\ShipFulfillmentOrderJob;
use lujie\fulfillment\models\FulfillmentAccount;
use lujie\fulfillment\models\FulfillmentItem;
use lujie\fulfillment\models\FulfillmentOrder;
use lujie\fulfillment\models\FulfillmentWarehouse;
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
 * Class FulfillmentManager
 * @package lujie\fulfillment
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FulfillmentManager extends Component implements BootstrapInterface
{
    /**
     * @var DataLoaderInterface
     */
    public $fulfillmentServiceLoader = 'fulfillmentServiceLoader';

    /**
     * @var Queue
     */
    public $queue = 'queue';

    /**
     * @var Mutex
     */
    public $mutex = 'mutex';

    /**
     * @var string
     */
    public $mutexNamePrefix = 'fulfillment:';

    /**
     * @var int
     */
    public $batchLimit = 100;

    /**
     * @var int
     */
    public $batchSize = 20;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->fulfillmentServiceLoader = Instance::ensure($this->fulfillmentServiceLoader, DataLoaderInterface::class);
        $this->queue = Instance::ensure($this->queue, Queue::class);
        $this->mutex = Instance::ensure($this->mutex, Mutex::class);
    }

    /**
     * @param int $fulfillmentAccountId
     * @return FulfillmentServiceInterface
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function getFulfillmentService(int $fulfillmentAccountId): FulfillmentServiceInterface
    {
        /** @var ?FulfillmentServiceInterface $fulfillmentService */
        $fulfillmentService = $this->fulfillmentServiceLoader->get($fulfillmentAccountId);
        if ($fulfillmentService === null) {
            throw new InvalidArgumentException("Null fulfillmentService of {$fulfillmentAccountId}");
        }
        $fulfillmentService = Instance::ensure($fulfillmentService, FulfillmentServiceInterface::class);
        return $fulfillmentService;
    }

    #region event, listen to fulfillment

    /**
     * @param Application $app
     * @inheritdoc
     */
    public function bootstrap($app): void
    {
        Event::on(FulfillmentItemForm::class, BaseActiveRecord::EVENT_AFTER_INSERT, [$this, 'afterFulfillmentItemSaved']);
        Event::on(FulfillmentItemForm::class, BaseActiveRecord::EVENT_AFTER_UPDATE, [$this, 'afterFulfillmentItemSaved']);
        Event::on(FulfillmentOrderForm::class, BaseActiveRecord::EVENT_AFTER_INSERT, [$this, 'afterFulfillmentOrderCreated']);
        Event::on(FulfillmentOrderForm::class, BaseActiveRecord::EVENT_AFTER_UPDATE, [$this, 'afterFulfillmentOrderUpdated']);
    }

    /**
     * @param AfterSaveEvent $event
     * @inheritdoc
     */
    public function afterFulfillmentItemSaved(AfterSaveEvent $event): void
    {
        /** @var FulfillmentItemForm $fulfillmentItemForm */
        $fulfillmentItemForm = $event->sender;
        //FulfillmentItem will trigger event, FulfillmentItem use instead
        $fulfillmentItem = new FulfillmentItem();
        $fulfillmentItem->setAttributes($fulfillmentItemForm->attributes, false);
        $fulfillmentItem->setIsNewRecord(false);
        if ($fulfillmentItem->item_pushed_at > $fulfillmentItem->item_updated_at) {
            Yii::info("FulfillmentItem {$fulfillmentItem->fulfillment_item_id} not updated, skip", __METHOD__);
            return;
        }
        $this->pushFulfillmentItemJob($fulfillmentItem);
    }

    /**
     * @param FulfillmentItem $fulfillmentItem
     * @inheritdoc
     */
    protected function pushFulfillmentItemJob(FulfillmentItem $fulfillmentItem): bool
    {
        $job = new PushFulfillmentItemJob();
        $job->fulfillmentManager = ComponentHelper::getName($this);
        $job->fulfillmentItemId = $fulfillmentItem->fulfillment_item_id;
        return ExecuteHelper::pushJob($this->queue, $job, $fulfillmentItem,
            'updated_at', 'item_pushed_status', 'item_pushed_result');
    }

    /**
     * @param AfterSaveEvent $event
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function afterFulfillmentOrderCreated(AfterSaveEvent $event): void
    {
        /** @var FulfillmentOrderForm $fulfillmentOrderForm */
        $fulfillmentOrderForm = $event->sender;
        $fulfillmentOrder = new FulfillmentOrder();
        $fulfillmentOrder->setAttributes($fulfillmentOrderForm->attributes, false);
        $fulfillmentOrder->setIsNewRecord(false);
        if ($fulfillmentOrder->order_pushed_at) {
            Yii::info("FulfillmentOrder {$fulfillmentOrder->fulfillment_order_id} already pushed, skip", __METHOD__);
            return;
        }
        $this->pushFulfillmentOrderJob($fulfillmentOrder);
    }

    /**
     * @param AfterSaveEvent $event
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function afterFulfillmentOrderUpdated(AfterSaveEvent $event): void
    {
        /** @var FulfillmentOrderForm $fulfillmentOrderForm */
        $fulfillmentOrderForm = $event->sender;
        $fulfillmentOrder = new FulfillmentOrder();
        $fulfillmentOrder->setAttributes($fulfillmentOrderForm->attributes, false);
        $fulfillmentOrder->setIsNewRecord(false);
        $name = "FulfillmentOrder {$fulfillmentOrder->fulfillment_order_id} of order {$fulfillmentOrder->order_id}";
        if (empty($fulfillmentOrder->external_order_key)) {
            $this->pushFulfillmentOrderJob($fulfillmentOrder);
            Yii::info("{$name} push update job", __METHOD__);
            return;
        }

        switch ($fulfillmentOrder->fulfillment_status) {
            case FulfillmentConst::FULFILLMENT_STATUS_TO_HOLDING:
                $this->pushHoldFulfillmentOrderJob($fulfillmentOrder);
                Yii::info("{$name} push hold job", __METHOD__);
                break;
            case FulfillmentConst::FULFILLMENT_STATUS_TO_SHIPPING:
                $this->pushShipFulfillmentOrderJob($fulfillmentOrder);
                Yii::info("{$name} push ship job", __METHOD__);
                break;
            case FulfillmentConst::FULFILLMENT_STATUS_TO_CANCELLING:
                $this->pushCancelFulfillmentOrderJob($fulfillmentOrder);
                Yii::info("{$name} push cancel job", __METHOD__);
                break;
            default;
                $this->pushFulfillmentOrderJob($fulfillmentOrder);
                Yii::info("{$name} push update job", __METHOD__);
                break;
        }
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function pushFulfillmentOrderJob(FulfillmentOrder $fulfillmentOrder): void
    {
        $this->pushFulfillmentOrderActionJob($fulfillmentOrder, ['class' => PushFulfillmentOrderJob::class]);
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function pushHoldFulfillmentOrderJob(FulfillmentOrder $fulfillmentOrder): void
    {
        $this->pushFulfillmentOrderActionJob($fulfillmentOrder, ['class' => HoldFulfillmentOrderJob::class]);
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function pushShipFulfillmentOrderJob(FulfillmentOrder $fulfillmentOrder): void
    {
        $this->pushFulfillmentOrderActionJob($fulfillmentOrder, ['class' => ShipFulfillmentOrderJob::class]);
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function pushCancelFulfillmentOrderJob(FulfillmentOrder $fulfillmentOrder): void
    {
        $this->pushFulfillmentOrderActionJob($fulfillmentOrder, ['class' => CancelFulfillmentOrderJob::class]);
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @param array $jobConfig
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function pushFulfillmentOrderActionJob(FulfillmentOrder $fulfillmentOrder, array $jobConfig = []): bool
    {
        /** @var BaseFulfillmentOrderJob $job */
        $job = Instance::ensure($jobConfig, BaseFulfillmentOrderJob::class);
        $job->fulfillmentManager = ComponentHelper::getName($this);
        $job->fulfillmentOrderId = $fulfillmentOrder->fulfillment_order_id;
        //always push job because order may be change multi times with different data, so need to push different job
        return ExecuteHelper::pushJob($this->queue, $job, $fulfillmentOrder,
            'updated_at', 'order_pushed_status', 'order_pushed_result', -1);
    }

    #endregion

    #region Item/Order Push

    /**
     * @param FulfillmentItem $fulfillmentItem
     * @return bool
     * @throws InvalidConfigException
     * @throws \Throwable
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function pushFulfillmentItem(FulfillmentItem $fulfillmentItem): bool
    {
        $name = "FulfillmentItem {$fulfillmentItem->fulfillment_item_id} of item {$fulfillmentItem->item_id}";
        if ($fulfillmentItem->item_pushed_at > $fulfillmentItem->item_updated_at) {
            Yii::info("{$name} already pushed, skip", __METHOD__);
            $fulfillmentItem->item_pushed_status = ExecStatusConst::EXEC_STATUS_SKIPPED;
            $fulfillmentItem->mustSave(false);
            return false;
        }

        $fulfillmentService = $this->getFulfillmentService($fulfillmentItem->fulfillment_account_id);
        $lockName = $this->mutexNamePrefix . 'pushFulfillmentItem:' . $fulfillmentItem->fulfillment_item_id;
        if ($this->mutex->acquire($lockName)) {
            try {
                return ExecuteHelper::execute(static function () use ($fulfillmentService, $fulfillmentItem, $name) {
                    $fulfillmentService->pushItem($fulfillmentItem);
                    Yii::info("{$name} pushed success", __METHOD__);
                }, $fulfillmentItem, 'item_pushed_at', 'item_pushed_status', 'item_pushed_result');
            } finally {
                $this->mutex->release($lockName);
            }
        }
        return false;
    }


    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool
     * @throws InvalidConfigException
     * @throws \Throwable
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function pushFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        $name = "FulfillmentOrder {$fulfillmentOrder->fulfillment_order_id} of order {$fulfillmentOrder->order_id}";
        if ($fulfillmentOrder->order_pushed_at > $fulfillmentOrder->order_updated_at) {
            Yii::info("{$name} already pushed, skip", __METHOD__);
            $fulfillmentOrder->order_pushed_at = ExecStatusConst::EXEC_STATUS_SKIPPED;
            $fulfillmentOrder->mustSave(false);
            return false;
        }

        $fulfillmentService = $this->getFulfillmentService($fulfillmentOrder->fulfillment_account_id);
        $lockName = $this->mutexNamePrefix . 'pushFulfillmentOrder:' . $fulfillmentOrder->fulfillment_order_id;
        if ($this->mutex->acquire($lockName)) {
            try {
                return ExecuteHelper::execute(static function () use ($fulfillmentService, $fulfillmentOrder, $name) {
                    $fulfillmentService->pushFulfillmentOrder($fulfillmentOrder);
                    Yii::info("{$name} pushed success", __METHOD__);
                }, $fulfillmentOrder, 'order_pushed_at', 'order_pushed_status', 'order_pushed_result');
            } finally {
                $this->mutex->release($lockName);
            }
        }
        return false;
    }

    #endregion

    #region Order Action hold/ship/cancel

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool
     * @throws InvalidConfigException
     * @throws \Throwable
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function holdFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        $name = "FulfillmentOrder {$fulfillmentOrder->fulfillment_order_id} of order {$fulfillmentOrder->order_id}";
        if ($fulfillmentOrder->fulfillment_status !== FulfillmentConst::FULFILLMENT_STATUS_TO_HOLDING) {
            Yii::info("{$name} not to holding, skip", __METHOD__);
            $fulfillmentOrder->order_pushed_status = ExecStatusConst::EXEC_STATUS_SKIPPED;
            $fulfillmentOrder->mustSave(false);
            return false;
        }

        $fulfillmentService = $this->getFulfillmentService($fulfillmentOrder->fulfillment_account_id);
        $lockName = $this->mutexNamePrefix . 'holdFulfillmentOrder:' . $fulfillmentOrder->fulfillment_order_id;
        if ($this->mutex->acquire($lockName)) {
            try {
                return ExecuteHelper::execute(static function () use ($fulfillmentService, $fulfillmentOrder, $name) {
                    $fulfillmentService->holdFulfillmentOrder($fulfillmentOrder);
                    Yii::info("{$name} to holding success", __METHOD__);
                }, $fulfillmentOrder, 'order_pushed_at', 'order_pushed_status', 'order_pushed_result');
            } finally {
                $this->mutex->release($lockName);
            }
        }
        return false;
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool
     * @throws InvalidConfigException
     * @throws \Throwable
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function shipFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        $name = "FulfillmentOrder {$fulfillmentOrder->fulfillment_order_id} of order {$fulfillmentOrder->order_id}";
        if ($fulfillmentOrder->fulfillment_status !== FulfillmentConst::FULFILLMENT_STATUS_TO_SHIPPING) {
            Yii::info("{$name} not to shipping, skip", __METHOD__);
            $fulfillmentOrder->order_pushed_status = ExecStatusConst::EXEC_STATUS_SKIPPED;
            $fulfillmentOrder->mustSave(false);
            return false;
        }

        $fulfillmentService = $this->getFulfillmentService($fulfillmentOrder->fulfillment_account_id);
        $lockName = $this->mutexNamePrefix . 'shipFulfillmentOrder:' . $fulfillmentOrder->fulfillment_order_id;
        if ($this->mutex->acquire($lockName)) {
            try {
                return ExecuteHelper::execute(static function () use ($fulfillmentService, $fulfillmentOrder, $name) {
                    $fulfillmentService->shipFulfillmentOrder($fulfillmentOrder);
                    Yii::info("{$name} to shipping success", __METHOD__);
                }, $fulfillmentOrder, 'order_pushed_at', 'order_pushed_status', 'order_pushed_result');
            } finally {
                $this->mutex->release($lockName);
            }
        }
        return false;
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool
     * @throws InvalidConfigException
     * @throws \Throwable
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function cancelFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        $name = "FulfillmentOrder {$fulfillmentOrder->fulfillment_order_id} of order {$fulfillmentOrder->order_id}";
        if ($fulfillmentOrder->fulfillment_status !== FulfillmentConst::FULFILLMENT_STATUS_TO_CANCELLING) {
            Yii::info("{$name} not to cancelling, skip", __METHOD__);
            $fulfillmentOrder->order_pushed_status = ExecStatusConst::EXEC_STATUS_SKIPPED;
            $fulfillmentOrder->mustSave(false);
            return false;
        }

        $fulfillmentService = $this->getFulfillmentService($fulfillmentOrder->fulfillment_account_id);
        $lockName = $this->mutexNamePrefix . 'cancelFulfillmentOrder:' . $fulfillmentOrder->fulfillment_order_id;
        if ($this->mutex->acquire($lockName)) {
            try {
                return ExecuteHelper::execute(static function () use ($fulfillmentService, $fulfillmentOrder, $name) {
                    $fulfillmentService->cancelFulfillmentOrder($fulfillmentOrder);
                    Yii::info("{$name} cancelled success", __METHOD__);
                }, $fulfillmentOrder, 'order_pushed_at', 'order_pushed_status', 'order_pushed_result');
            } finally {
                $this->mutex->release($lockName);
            }
        }
        return false;
    }

    #endregion

    #region Order/Warehouse/Stock/Movement Pull

    /**
     * @param int $accountId
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function pullFulfillmentOrders(int $accountId): void
    {
        $query = FulfillmentOrder::find()
            ->fulfillmentAccountId($accountId)
            ->processing()
            ->orderByOrderPulledAt()
            ->limit($this->batchLimit);
        if (!$query->exists()) {
            return;
        }

        $fulfillmentService = $this->getFulfillmentService($accountId);
        foreach ($query->batch($this->batchSize) as $batch) {
            $fulfillmentService->pullFulfillmentOrders($batch);
        }
    }

    /**
     * @param int $accountId
     * @param array $condition
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function pullFulfillmentWarehouses(int $accountId, array $condition = []): void
    {
        $fulfillmentService = $this->getFulfillmentService($accountId);
        $fulfillmentService->pullWarehouses($condition);
    }

    /**
     * @param int $accountId
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function pullFulfillmentWarehouseStocks(int $accountId): void
    {
        $query = FulfillmentItem::find()
            ->fulfillmentAccountId($accountId)
            ->itemPushed()
            ->orderByStockPulledAt()
            ->limit($this->batchLimit);
        if (!$query->exists()) {
            return;
        }

        $fulfillmentService = $this->getFulfillmentService($accountId);
        foreach ($query->batch($this->batchSize) as $batch) {
            $fulfillmentService->pullWarehouseStocks($batch);
        }
    }

    /**
     * @param int $accountId
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function pullFulfillmentWarehouseStockMovements(int $accountId): void
    {
        $fulfillmentWarehouses = FulfillmentWarehouse::find()->fulfillmentAccountId($accountId)->all();
        $fulfillmentService = $this->getFulfillmentService($accountId);
        foreach ($fulfillmentWarehouses as $fulfillmentWarehouse) {
            $fulfillmentService->pullWarehouseStockMovements($fulfillmentWarehouse);
        }
    }

    #endregion

    #region Recheck and Retry To Push

    /**
     * @inheritdoc
     */
    public function pushFulfillmentItems(): void
    {
        $accountIds = FulfillmentAccount::find()->active()->column();
        $query = FulfillmentItem::find()
            ->fulfillmentAccountId($accountIds)
            ->newUpdatedItems()
            ->notQueued();
        foreach ($query->each() as $fulfillmentItem) {
            $this->pushFulfillmentItemJob($fulfillmentItem);
        }
        $query = FulfillmentItem::find()
            ->fulfillmentAccountId($accountIds)
            ->queuedButNotExecuted();
        foreach ($query->each() as $fulfillmentItem) {
            $this->pushFulfillmentItemJob($fulfillmentItem);
        }
    }

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function pushFulfillmentOrders(): void
    {
        $accountIds = FulfillmentAccount::find()->active()->column();
        $query = FulfillmentOrder::find()
            ->fulfillmentAccountId($accountIds)
            ->pending()
            ->notQueued();
        foreach ($query->each() as $fulfillmentOrder) {
            $this->pushFulfillmentOrderJob($fulfillmentOrder);
        }

        $query = FulfillmentOrder::find()
            ->fulfillmentAccountId($accountIds)
            ->toHolding()
            ->notQueued();
        foreach ($query->each() as $fulfillmentOrder) {
            $this->pushHoldFulfillmentOrderJob($fulfillmentOrder);
        }

        $query = FulfillmentOrder::find()
            ->fulfillmentAccountId($accountIds)
            ->toShipping()
            ->notQueued();
        foreach ($query->each() as $fulfillmentOrder) {
            $this->pushShipFulfillmentOrderJob($fulfillmentOrder);
        }

        $query = FulfillmentOrder::find()
            ->fulfillmentAccountId($accountIds)
            ->toCancelling()
            ->notQueued();
        foreach ($query->each() as $fulfillmentOrder) {
            $this->pushCancelFulfillmentOrderJob($fulfillmentOrder);
        }
    }

    #endregion
}

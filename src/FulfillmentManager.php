<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment;

use lujie\data\loader\DataLoaderInterface;
use lujie\extend\constants\ExecStatusConst;
use lujie\extend\helpers\ComponentHelper;
use lujie\fulfillment\constants\FulfillmentConst;
use lujie\fulfillment\jobs\CancelFulfillmentOrderJob;
use lujie\fulfillment\jobs\PushFulfillmentItemJob;
use lujie\fulfillment\jobs\PushFulfillmentOrderJob;
use lujie\fulfillment\models\FulfillmentAccount;
use lujie\fulfillment\models\FulfillmentItem;
use lujie\fulfillment\models\FulfillmentOrder;
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
     * @var DataLoaderInterface|mixed
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
        /** @var FulfillmentServiceInterface $fulfillmentService */
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
        Event::on(FulfillmentItem::class, BaseActiveRecord::EVENT_AFTER_INSERT, [$this, 'afterFulfillmentItemSaved']);
        Event::on(FulfillmentItem::class, BaseActiveRecord::EVENT_AFTER_UPDATE, [$this, 'afterFulfillmentItemSaved']);
        Event::on(FulfillmentOrder::class, BaseActiveRecord::EVENT_AFTER_INSERT, [$this, 'afterFulfillmentOrderCreated']);
        Event::on(FulfillmentOrder::class, BaseActiveRecord::EVENT_AFTER_UPDATE, [$this, 'afterFulfillmentOrderUpdated']);
    }

    /**
     * @param AfterSaveEvent $event
     * @inheritdoc
     */
    public function afterFulfillmentItemSaved(AfterSaveEvent $event): void
    {
        /** @var FulfillmentItem $fulfillmentItem */
        $fulfillmentItem = $event->sender;
        if ($fulfillmentItem->external_item_id && $fulfillmentItem->item_pushed_at >= $fulfillmentItem->item_updated_at) {
            Yii::info("FulfillmentItem {$fulfillmentItem->fulfillment_item_id} not updated, skip", __METHOD__);
            return;
        }
        $this->pushFulfillmentItemJob($fulfillmentItem);
    }

    /**
     * @param FulfillmentItem $fulfillmentItem
     * @inheritdoc
     */
    protected function pushFulfillmentItemJob(FulfillmentItem $fulfillmentItem)
    {
        if ($fulfillmentItem->item_pushed_status === ExecStatusConst::EXEC_STATUS_QUEUED) {
            return;
        }
        $job = new PushFulfillmentItemJob();
        $job->fulfillmentManager = ComponentHelper::getName($this);
        $job->fulfillmentItemId = $fulfillmentItem->fulfillment_item_id;
        $this->queue->push($job);
        $fulfillmentItem->item_pushed_status = ExecStatusConst::EXEC_STATUS_QUEUED;
        $fulfillmentItem->save(false);
    }

    /**
     * @param AfterSaveEvent $event
     * @inheritdoc
     */
    public function afterFulfillmentOrderCreated(AfterSaveEvent $event): void
    {
        /** @var FulfillmentOrder $fulfillmentOrder */
        $fulfillmentOrder = $event->sender;
        if ($fulfillmentOrder->order_pushed_at) {
            Yii::info("FulfillmentOrder {$fulfillmentOrder->fulfillment_order_id} already pushed, skip", __METHOD__);
            return;
        }
        $this->pushFulfillmentOrderJob($fulfillmentOrder);
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @inheritdoc
     */
    protected function pushFulfillmentOrderJob(FulfillmentOrder $fulfillmentOrder)
    {
        if ($fulfillmentOrder->order_pushed_status === ExecStatusConst::EXEC_STATUS_QUEUED) {
            return;
        }
        $job = new PushFulfillmentOrderJob();
        $job->fulfillmentManager = ComponentHelper::getName($this);
        $job->fulfillmentOrderId = $fulfillmentOrder->fulfillment_order_id;
        $this->queue->push($job);
        $fulfillmentOrder->order_pushed_status = ExecStatusConst::EXEC_STATUS_QUEUED;
        $fulfillmentOrder->save(false);
    }

    /**
     * @param AfterSaveEvent $event
     * @inheritdoc
     */
    public function afterFulfillmentOrderUpdated(AfterSaveEvent $event): void
    {
        /** @var FulfillmentOrder $fulfillmentOrder */
        $fulfillmentOrder = $event->sender;
        if ($fulfillmentOrder->external_order_id
            && $fulfillmentOrder->fulfillment_status === FulfillmentConst::FULFILLMENT_STATUS_PICKING_CANCELLING) {
            $this->pushCancelFulfillmentOrderJob($fulfillmentOrder);
        } else {
            Yii::info("Order not pushed or not cancelling status, orderId: {$fulfillmentOrder->order_id}", __METHOD__);
        }
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @inheritdoc
     */
    protected function pushCancelFulfillmentOrderJob(FulfillmentOrder $fulfillmentOrder)
    {
        if ($fulfillmentOrder->order_pushed_status === ExecStatusConst::EXEC_STATUS_QUEUED) {
            return;
        }
        $job = new CancelFulfillmentOrderJob();
        $job->fulfillmentManager = ComponentHelper::getName($this);
        $job->fulfillmentOrderId = $fulfillmentOrder->fulfillment_order_id;
        $this->queue->push($job);
        $fulfillmentOrder->order_pushed_status = ExecStatusConst::EXEC_STATUS_QUEUED;
        $fulfillmentOrder->save(false);
    }

    #endregion

    #region push and cancelling

    /**
     * @param FulfillmentItem $fulfillmentItem
     * @return bool
     * @throws InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function pushFulfillmentItem(FulfillmentItem $fulfillmentItem): bool
    {
        $fulfillmentService = $this->getFulfillmentService($fulfillmentItem->fulfillment_account_id);
        $lockName = $this->mutexNamePrefix . 'pushFulfillmentItem:' . $fulfillmentItem->fulfillment_item_id;
        if ($this->mutex->acquire($lockName)) {
            try {
                if ($fulfillmentItem->item_pushed_at > $fulfillmentItem->item_updated_at) {
                    Yii::info("FulfillmentItem {$fulfillmentItem->fulfillment_item_id} already pushed, skip", __METHOD__);
                    $fulfillmentItem->item_pushed_status = ExecStatusConst::EXEC_STATUS_SKIPPED;
                    $fulfillmentItem->mustSave(false);
                    return false;
                }
                $fulfillmentService->pushItem($fulfillmentItem);
                Yii::info("FulfillmentItem {$fulfillmentItem->fulfillment_item_id} pushed success", __METHOD__);
                $fulfillmentItem->item_pushed_status = ExecStatusConst::EXEC_STATUS_SUCCESS;
                $fulfillmentItem->item_pushed_at = time();
                $fulfillmentItem->item_pushed_errors = [];
                $fulfillmentItem->mustSave(false);
                return true;
            } catch (\Throwable $ex) {
                Yii::error("FulfillmentItem {$fulfillmentItem->fulfillment_item_id} pushed error: {$ex->getMessage()}", __METHOD__);
                $fulfillmentItem->item_pushed_status = ExecStatusConst::EXEC_STATUS_FAILED;
                $fulfillmentItem->item_pushed_errors = ['ex' => $ex->getMessage()];
                $fulfillmentItem->mustSave(false);
                return false;
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
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function pushFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        $fulfillmentService = $this->getFulfillmentService($fulfillmentOrder->fulfillment_account_id);
        $lockName = $this->mutexNamePrefix . 'pushFulfillmentOrder:' . $fulfillmentOrder->fulfillment_order_id;
        if ($this->mutex->acquire($lockName)) {
            try {
                if ($fulfillmentOrder->order_pushed_at) {
                    Yii::info("FulfillmentOrder {$fulfillmentOrder->fulfillment_order_id} already pushed, skip", __METHOD__);
                    $fulfillmentOrder->order_pushed_status = ExecStatusConst::EXEC_STATUS_SKIPPED;
                    $fulfillmentOrder->mustSave(false);
                    return false;
                }
                $fulfillmentService->pushFulfillmentOrder($fulfillmentOrder);
                Yii::info("FulfillmentOrder {$fulfillmentOrder->fulfillment_order_id} pushed success", __METHOD__);
                $fulfillmentOrder->order_pushed_status = ExecStatusConst::EXEC_STATUS_SUCCESS;
                $fulfillmentOrder->order_pushed_at = time();
                $fulfillmentOrder->order_pushed_errors = [];
                $fulfillmentOrder->mustSave(false);
                return true;
            } catch (\Throwable $ex) {
                Yii::error("FulfillmentOrder {$fulfillmentOrder->fulfillment_order_id} pushed error: {$ex->getMessage()}", __METHOD__);
                $fulfillmentOrder->order_pushed_status = ExecStatusConst::EXEC_STATUS_FAILED;
                $fulfillmentOrder->order_pushed_errors = ['ex' => $ex->getMessage()];
                $fulfillmentOrder->mustSave(false);
                return false;
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
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function cancelFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        $fulfillmentService = $fulfillmentService = $this->getFulfillmentService($fulfillmentOrder->fulfillment_account_id);
        $lockName = $this->mutexNamePrefix . 'cancelFulfillmentOrder:' . $fulfillmentOrder->fulfillment_order_id;
        if ($this->mutex->acquire($lockName)) {
            try {
                if ($fulfillmentOrder->fulfillment_status !== FulfillmentConst::FULFILLMENT_STATUS_PICKING_CANCELLING) {
                    Yii::info("FulfillmentOrder {$fulfillmentOrder->fulfillment_order_id} not cancelling, skip", __METHOD__);
                    $fulfillmentOrder->order_pushed_status = ExecStatusConst::EXEC_STATUS_SKIPPED;
                    $fulfillmentOrder->mustSave(false);
                    return false;
                }
                $fulfillmentService->cancelFulfillmentOrder($fulfillmentOrder);
                Yii::info("FulfillmentOrder {$fulfillmentOrder->fulfillment_order_id} cancelled success", __METHOD__);
                $fulfillmentOrder->order_pushed_status = ExecStatusConst::EXEC_STATUS_SUCCESS;
                $fulfillmentOrder->order_pushed_at = time();
                $fulfillmentOrder->order_pushed_errors = [];
                $fulfillmentOrder->mustSave(false);
                return false;
            } catch (\Throwable $ex) {
                Yii::error("FulfillmentOrder {$fulfillmentOrder->fulfillment_order_id} cancelled error: {$ex->getMessage()}", __METHOD__);
                $fulfillmentOrder->order_pushed_status = ExecStatusConst::EXEC_STATUS_FAILED;
                $fulfillmentOrder->order_pushed_errors = ['ex' => $ex->getMessage()];
                $fulfillmentOrder->mustSave(false);
                return false;
            } finally {
                $this->mutex->release($lockName);
            }
        }
        return false;
    }

    #endregion

    #region pull and update

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
    public function pullFulfillmentOrders(int $accountId): void
    {
        $query = FulfillmentOrder::find()
            ->accountId($accountId)
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
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function pullFulfillmentWarehouseStocks(int $accountId): void
    {
        $query = FulfillmentItem::find()
            ->accountId($accountId)
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

    #endregion

    #region Recheck and Push

    /**
     * @inheritdoc
     */
    public function pushFulfillmentItems(): void
    {
        $accountIds = FulfillmentAccount::find()->active()->column();
        $fulfillmentItems = FulfillmentItem::find()
            ->accountId($accountIds)
            ->newUpdatedItems()
            ->notQueued()
            ->all();
        foreach ($fulfillmentItems as $fulfillmentItem) {
            $this->pushFulfillmentItemJob($fulfillmentItem);
        }
    }

    /**
     * @inheritdoc
     */
    public function pushFulfillmentOrders(): void
    {
        $accountIds = FulfillmentAccount::find()->active()->column();
        $fulfillmentOrders = FulfillmentOrder::find()
            ->accountId($accountIds)
            ->pending()
            ->notQueued()
            ->all();
        foreach ($fulfillmentOrders as $fulfillmentOrder) {
            $this->pushFulfillmentOrderJob($fulfillmentOrder);
        }

        $fulfillmentOrders = FulfillmentOrder::find()
            ->accountId($accountIds)
            ->pickingCancelling()
            ->notQueued()
            ->all();
        foreach ($fulfillmentOrders as $fulfillmentOrder) {
            $this->pushCancelFulfillmentOrderJob($fulfillmentOrder);
        }
    }

    #endregion
}

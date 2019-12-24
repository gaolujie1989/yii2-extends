<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment;

use lujie\data\loader\DataLoaderInterface;
use lujie\extend\helpers\ComponentHelper;
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
    public $fulfillmentServiceLoader;

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
     * @var
     */
    public $orderCancellingStatus;

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
            Yii::info("Fulfillment item not updated, skip to push, itemId: {$fulfillmentItem->item_id}", __METHOD__);
            return;
        }
        $job = new PushFulfillmentItemJob();
        $job->fulfillmentManager = ComponentHelper::getName($this);
        $job->fulfillmentItemId = $fulfillmentItem->fulfillment_item_id;
        $this->queue->push($job);
    }

    /**
     * @param AfterSaveEvent $event
     * @inheritdoc
     */
    public function afterFulfillmentOrderCreated(AfterSaveEvent $event): void
    {
        /** @var FulfillmentOrder $fulfillmentOrder */
        $fulfillmentOrder = $event->sender;
        if ($fulfillmentOrder->external_order_id) {
            Yii::info("Fulfillment order already pushed, orderId: {$fulfillmentOrder->order_id}", __METHOD__);
            return;
        }
        $job = new PushFulfillmentOrderJob();
        $job->fulfillmentManager = ComponentHelper::getName($this);
        $job->fulfillmentOrderId = $fulfillmentOrder->fulfillment_order_id;
        $this->queue->push($job);
    }

    /**
     * @param AfterSaveEvent $event
     * @inheritdoc
     */
    public function afterFulfillmentOrderUpdated(AfterSaveEvent $event): void
    {
        /** @var FulfillmentOrder $fulfillmentOrder */
        $fulfillmentOrder = $event->sender;
        if ($fulfillmentOrder->external_order_id && $this->orderCancellingStatus &&
            $this->orderCancellingStatus === $fulfillmentOrder->order_status) {
            $job = new CancelFulfillmentOrderJob();
            $job->fulfillmentManager = ComponentHelper::getName($this);
            $job->fulfillmentOrderId = $fulfillmentOrder->fulfillment_order_id;
            $this->queue->push($job);
        } else {
            Yii::info("Order not pushed or not cancelling status, orderId: {$fulfillmentOrder->order_id}", __METHOD__);
        }
    }

    #endregion

    #region push and cancelling

    /**
     * @param FulfillmentItem $fulfillmentItem
     * @return bool
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function pushFulfillmentItem(FulfillmentItem $fulfillmentItem): bool
    {
        /** @var FulfillmentServiceInterface $fulfillmentService */
        $fulfillmentService = $this->fulfillmentServiceLoader->get($fulfillmentItem->fulfillment_account_id);
        $lockName = $this->mutexNamePrefix . 'item:' . $fulfillmentItem->fulfillment_item_id;
        if ($this->mutex->acquire($lockName)) {
            try {
                $fulfillmentItem->item_pushed_at = time();
                if ($fulfillmentService->pushItem($fulfillmentItem)) {
                    $fulfillmentItem->item_pushed_errors = [];
                    return $fulfillmentItem->mustSave(false);
                }
            } catch (\Throwable $ex) {
                $fulfillmentItem->item_pushed_errors = ['ex' => $ex->getMessage()];
                return $fulfillmentItem->mustSave(false);
            } finally {
                $this->mutex->release($lockName);
            }
        }
        return false;
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function pushFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        /** @var FulfillmentServiceInterface $fulfillmentService */
        $fulfillmentService = $this->fulfillmentServiceLoader->get($fulfillmentOrder->fulfillment_account_id);
        $lockName = $this->mutexNamePrefix . 'order:' . $fulfillmentOrder->fulfillment_order_id;
        if ($this->mutex->acquire($lockName)) {
            try {
                $fulfillmentOrder->order_pushed_at = time();
                if ($fulfillmentService->pushFulfillmentOrder($fulfillmentOrder)) {
                    $fulfillmentOrder->order_pushed_errors = [];
                    return $fulfillmentOrder->mustSave(false);
                }
            } catch (\Throwable $ex) {
                $fulfillmentOrder->order_pushed_errors = ['ex' => $ex->getMessage()];
                return $fulfillmentOrder->mustSave(false);
            } finally {
                $this->mutex->release($lockName);
            }
        }
        return false;
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @inheritdoc
     */
    public function cancelFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        /** @var FulfillmentServiceInterface $fulfillmentService */
        $fulfillmentService = $this->fulfillmentServiceLoader->get($fulfillmentOrder->fulfillment_account_id);
        return $fulfillmentService->cancelFulfillmentOrder($fulfillmentOrder);
    }

    #endregion

    #region pull and update

    /**
     * @param $accountId
     * @inheritdoc
     */
    public function pullFulfillmentWarehouses(int $accountId, array $condition = []): void
    {
        /** @var FulfillmentServiceInterface $fulfillmentService */
        $fulfillmentService = $this->fulfillmentServiceLoader->get($accountId);
        $fulfillmentService->pullWarehouses($condition);
    }

    /**
     * @param int $accountId
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

        /** @var FulfillmentServiceInterface $fulfillmentService */
        $fulfillmentService = $this->fulfillmentServiceLoader->get($accountId);
        foreach ($query->batch($this->batchSize) as $batch) {
            $fulfillmentService->pullFulfillmentOrders($batch);
        }
    }

    /**
     * @param int $accountId
     * @inheritdoc
     */
    public function pullFulfillmentWarehouseStocks(int $accountId): void
    {
        $query = FulfillmentItem::find()
            ->accountId($accountId)
            ->hasExternalItemId()
            ->orderByStockPulledAt()
            ->limit($this->batchLimit);
        if (!$query->exists()) {
            return;
        }

        /** @var FulfillmentServiceInterface $fulfillmentService */
        $fulfillmentService = $this->fulfillmentServiceLoader->get($accountId);
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
            ->externalItemId(0)
            ->all();
        foreach ($fulfillmentItems as $fulfillmentItem) {
            $this->pushFulfillmentItem($fulfillmentItem);
        }

        $fulfillmentItems = FulfillmentItem::find()
            ->accountId($accountIds)
            ->hasExternalItemId()
            ->newUpdatedItems()
            ->all();
        foreach ($fulfillmentItems as $fulfillmentItem) {
            $this->pushFulfillmentItem($fulfillmentItem);
        }
    }

    /**
     * @return bool
     * @inheritdoc
     */
    public function pushFulfillmentOrders(): void
    {
        $accountIds = FulfillmentAccount::find()->active()->column();
        $fulfillmentOrders = FulfillmentOrder::find()
            ->accountId($accountIds)
            ->externalOrderId(0)
            ->all();
        foreach ($fulfillmentOrders as $fulfillmentOrder) {
            $this->pushFulfillmentOrder($fulfillmentOrder);
        }

        $fulfillmentOrders = FulfillmentOrder::find()
            ->accountId($accountIds)
            ->orderStatus($this->orderCancellingStatus)
            ->processing()
            ->all();
        foreach ($fulfillmentOrders as $fulfillmentOrder) {
            $this->pushFulfillmentOrder($fulfillmentOrder);
        }
    }

    #endregion
}

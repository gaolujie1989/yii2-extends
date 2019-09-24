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
use lujie\fulfillment\models\FulfillmentItem;
use lujie\fulfillment\models\FulfillmentOrder;
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

    public $orderCancellingStatus;

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
     * @param Application $app
     * @inheritdoc
     */
    public function bootstrap($app): void
    {
        Event::on(FulfillmentItem::class, BaseActiveRecord::EVENT_AFTER_INSERT, [$this, 'afterFulfillmentItemCreated']);
        Event::on(FulfillmentOrder::class, BaseActiveRecord::EVENT_AFTER_INSERT, [$this, 'afterFulfillmentOrderCreated']);
        Event::on(FulfillmentOrder::class, BaseActiveRecord::EVENT_AFTER_UPDATE, [$this, 'afterFulfillmentOrderUpdated']);
    }

    /**
     * @param AfterSaveEvent $event
     * @inheritdoc
     */
    public function afterFulfillmentItemCreated(AfterSaveEvent $event): void
    {
        /** @var FulfillmentItem $fulfillmentItem */
        $fulfillmentItem = $event->sender;
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
        $job = new PushFulfillmentOrderJob();
        $job->fulfillmentManager = ComponentHelper::getName($this);
        $job->fulfillmentOrderId = $fulfillmentOrder->fulfillment_order_id;
        $this->queue->push($job);
    }

    public function afterFulfillmentOrderUpdated(AfterSaveEvent $event): void
    {
        /** @var FulfillmentOrder $fulfillmentOrder */
        $fulfillmentOrder = $event->sender;
        if ($fulfillmentOrder->order_status === $this->orderCancellingStatus) {
            $job = new CancelFulfillmentOrderJob();
            $job->fulfillmentManager = ComponentHelper::getName($this);
            $job->fulfillmentOrderId = $fulfillmentOrder->fulfillment_order_id;
            $this->queue->push($job);
        }
    }

    /**、
     * @param FulfillmentItem $fulfillmentItem
     * @return bool|mixed
     * @inheritdoc
     */
    public function pushFulfillmentItem(FulfillmentItem $fulfillmentItem): bool
    {
        /** @var FulfillmentServiceInterface $fulfillmentService */
        $fulfillmentService = $this->fulfillmentServiceLoader->get($fulfillmentItem->fulfillment_account_id);
        $lockName = $this->mutexNamePrefix . 'item:' . $fulfillmentItem->fulfillment_item_id;
        if ($this->mutex->acquire($lockName)) {
            try {
                return $fulfillmentService->pushItem($fulfillmentItem);
            } finally {
                $this->mutex->release($lockName);
            }
        }
        return false;
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @return bool|mixed
     * @inheritdoc
     */
    public function pushFulfillmentOrder(FulfillmentOrder $fulfillmentOrder): bool
    {
        /** @var FulfillmentServiceInterface $fulfillmentService */
        $fulfillmentService = $this->fulfillmentServiceLoader->get($fulfillmentOrder->fulfillment_account_id);
        $lockName = $this->mutexNamePrefix . 'order:' . $fulfillmentOrder->fulfillment_order_id;
        if ($this->mutex->acquire($lockName)) {
            try {
                return $fulfillmentService->pushFulfillmentOrder($fulfillmentOrder);
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
     * @param $accountId
     * @inheritdoc
     */
    public function pullFulfillmentOrders(int $accountId): void
    {
        /** @var FulfillmentServiceInterface $fulfillmentService */
        $fulfillmentService = $this->fulfillmentServiceLoader->get($accountId);
        $fulfillmentService->pullFulfillmentOrders();
    }

    public function pullFulfillmentWarehouseStocks(int $accountId): void
    {
        /** @var FulfillmentServiceInterface $fulfillmentService */
        $fulfillmentService = $this->fulfillmentServiceLoader->get($accountId);
        $fulfillmentService->pullWarehouseStocks();
    }

    /**
     * @param int $accountId
     * @return bool
     * @inheritdoc
     */
    public function pushFulfillmentItems(int $accountId): bool
    {
        $fulfillmentItems = FulfillmentItem::find()->externalItemId(0)->all();
        foreach ($fulfillmentItems as $fulfillmentItem) {
            $this->pushFulfillmentItem($fulfillmentItem);
        }
    }

    /**
     * @return bool
     * @inheritdoc
     */
    public function pushFulfillmentOrders(): bool
    {
        $fulfillmentOrders = FulfillmentOrder::find()->externalOrderId(0)->all();
        foreach ($fulfillmentOrders as $fulfillmentOrder) {
            $this->pushFulfillmentOrder($fulfillmentOrder);
        }
        $fulfillmentOrders = FulfillmentOrder::find()->orderStatus($this->orderCancellingStatus)->processing()->all();
        foreach ($fulfillmentOrders as $fulfillmentOrder) {
            $this->pushFulfillmentOrder($fulfillmentOrder);
        }
    }
}

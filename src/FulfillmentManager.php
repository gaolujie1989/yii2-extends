<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment;

use lujie\data\loader\DataLoaderInterface;
use lujie\extend\helpers\ComponentHelper;
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

    /**
     * @param FulfillmentItem $fulfillmentItem
     * @inheritdoc
     */
    public function pushFulfillmentItem(FulfillmentItem $fulfillmentItem)
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
    }

    /**
     * @param FulfillmentOrder $fulfillmentOrder
     * @inheritdoc
     */
    public function pushFulfillmentOrder(FulfillmentOrder $fulfillmentOrder)
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
    }

    /**
     * @param $accountId
     * @inheritdoc
     */
    public function pullFulfillmentItems($accountId)
    {
        /** @var FulfillmentServiceInterface $fulfillmentService */
        $fulfillmentService = $this->fulfillmentServiceLoader->get($accountId);
        $fulfillmentService->pullExistItems();
    }

    /**
     * @param $accountId
     * @inheritdoc
     */
    public function pullFulfillmentOrders($accountId)
    {
        /** @var FulfillmentServiceInterface $fulfillmentService */
        $fulfillmentService = $this->fulfillmentServiceLoader->get($accountId);
        $fulfillmentService->pullFulfillmentOrders();
    }
}

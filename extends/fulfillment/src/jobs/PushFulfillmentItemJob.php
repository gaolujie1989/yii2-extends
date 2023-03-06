<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\jobs;

use lujie\extend\queue\RateLimitDelayJobInterface;
use lujie\fulfillment\FulfillmentManager;
use lujie\fulfillment\models\FulfillmentItem;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\queue\JobInterface;
use yii\queue\Queue;

/**
 * Class FulfillmentExecutable
 * @package lujie\fulfillment
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PushFulfillmentItemJob extends BaseObject implements JobInterface, RateLimitDelayJobInterface
{
    /**
     * @var FulfillmentManager
     */
    public $fulfillmentManager = 'fulfillmentManager';

    /**
     * @var int
     */
    public $fulfillmentItemId;

    /**
     * @var int
     */
    public $rateLimitDelay = 2;

    /**
     * @param Queue $queue
     * @throws InvalidConfigException
     * @throws \Throwable
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function execute($queue): void
    {
        $this->fulfillmentManager = Instance::ensure($this->fulfillmentManager, FulfillmentManager::class);
        $this->fulfillmentManager->pushFulfillmentItem($this->getFulfillmentItem());
    }

    /**
     * @return FulfillmentItem
     * @inheritdoc
     */
    protected function getFulfillmentItem(): FulfillmentItem
    {
        /** @var ?FulfillmentItem $fulfillmentItem */
        $fulfillmentItem = FulfillmentItem::findOne($this->fulfillmentItemId);
        if ($fulfillmentItem === null) {
            throw new InvalidArgumentException("Invalid fulfillmentItemId {$this->fulfillmentItemId}");
        }
        return $fulfillmentItem;
    }

    /**
     * @return string
     * @inheritdoc
     */
    public function getRateLimitKey(): string
    {
        $fulfillmentItem = $this->getFulfillmentItem();
        return 'FulfillmentItemAccount:' . $fulfillmentItem->fulfillment_account_id;
    }

    /**
     * @return int
     * @inheritdoc
     */
    public function getRateLimitDelay(): int
    {
        return $this->rateLimitDelay;
    }
}

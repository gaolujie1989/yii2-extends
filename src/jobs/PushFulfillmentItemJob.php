<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\jobs;

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
class PushFulfillmentItemJob extends BaseObject implements JobInterface
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
     * @param Queue $queue
     * @throws InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function execute($queue): void
    {
        /** @var FulfillmentItem $fulfillmentItem */
        $fulfillmentItem = FulfillmentItem::findOne($this->fulfillmentItemId);
        if ($fulfillmentItem === null) {
            throw new InvalidArgumentException('Invalid fulfillmentItemId');
        }

        $this->fulfillmentManager = Instance::ensure($this->fulfillmentManager, FulfillmentManager::class);
        $this->fulfillmentManager->pushFulfillmentItem($fulfillmentItem);
    }
}

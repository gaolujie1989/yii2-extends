<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\jobs;

use lujie\extend\queue\RetryableJobTrait;
use lujie\fulfillment\FulfillmentManager;
use lujie\fulfillment\models\FulfillmentOrder;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\queue\JobInterface;
use yii\queue\Queue;
use yii\queue\RetryableJobInterface;

/**
 * Class CancelFulfillmentOrderJob
 * @package lujie\fulfillment\jobs
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CancelFulfillmentOrderJob extends BaseObject implements JobInterface, RetryableJobInterface
{
    use RetryableJobTrait;

    /**
     * @var FulfillmentManager
     */
    public $fulfillmentManager = 'fulfillmentManager';

    /**
     * @var int
     */
    public $fulfillmentOrderId;

    /**
     * @param Queue $queue
     * @throws InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function execute($queue): void
    {
        /** @var FulfillmentOrder $fulfillmentOrder */
        $fulfillmentOrder = FulfillmentOrder::findOne($this->fulfillmentOrderId);
        if ($fulfillmentOrder === null) {
            throw new InvalidArgumentException('Invalid fulfillmentItemId');
        }

        $this->fulfillmentManager = Instance::ensure($this->fulfillmentManager, FulfillmentManager::class);
        $this->fulfillmentManager->cancelFulfillmentOrder($fulfillmentOrder);
    }
}

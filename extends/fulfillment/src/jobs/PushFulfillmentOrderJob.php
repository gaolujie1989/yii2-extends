<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\jobs;

use lujie\fulfillment\FulfillmentManager;
use lujie\fulfillment\models\FulfillmentOrder;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\queue\JobInterface;
use yii\queue\Queue;

/**
 * Class PushFulfillmentOrderJob
 * @package lujie\fulfillment\jobs
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PushFulfillmentOrderJob extends BaseFulfillmentOrderJob
{
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
        $this->fulfillmentManager->pushFulfillmentOrder($this->getFulfillmentOrder());
    }
}

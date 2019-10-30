<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tasks;

use lujie\fulfillment\FulfillmentManager;
use lujie\scheduling\CronTask;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\queue\Queue;

/**
 * Class PushPendingFulfillmentOrderTask
 * @package lujie\fulfillment\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PushFulfillmentOrderTask extends CronTask
{
    /**
     * @var FulfillmentManager
     */
    public $fulfillmentManager = 'fulfillmentManager';

    /**
     * @param Queue $queue
     * @return mixed|void
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function execute(): bool
    {
        $this->fulfillmentManager = Instance::ensure($this->fulfillmentManager, FulfillmentManager::class);
        return $this->fulfillmentManager->pushFulfillmentOrders();
    }
}

<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\jobs;

use lujie\fulfillment\FulfillmentManager;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\queue\Queue;

/**
 * Class CancelFulfillmentOrderJob
 * @package lujie\fulfillment\jobs
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CancelFulfillmentOrderJob extends BaseFulfillmentOrderJob
{
    /**
     * @param Queue $queue
     * @throws InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function execute($queue): void
    {
        $this->fulfillmentManager = Instance::ensure($this->fulfillmentManager, FulfillmentManager::class);
        $this->fulfillmentManager->cancelFulfillmentOrder($this->getFulfillmentOrder());
    }
}

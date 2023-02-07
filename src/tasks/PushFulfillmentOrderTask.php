<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tasks;

use lujie\fulfillment\FulfillmentManager;
use lujie\scheduling\CronTask;
use yii\base\InvalidConfigException;
use yii\di\Instance;

/**
 * Class PushPendingFulfillmentOrderTask
 * @package lujie\fulfillment\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PushFulfillmentOrderTask extends BaseFulfillmentTask
{
    /**
     * @return bool
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function execute(): bool
    {
        $this->fulfillmentManager = Instance::ensure($this->fulfillmentManager, FulfillmentManager::class);
        $accountIds = $this->getAccountIds();
        foreach ($accountIds as $accountId) {
            $this->fulfillmentManager->pushFulfillmentOrders($accountId);
        }
        return true;
    }
}

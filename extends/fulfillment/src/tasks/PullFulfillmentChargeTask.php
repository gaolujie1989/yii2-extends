<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tasks;

use lujie\fulfillment\FulfillmentManager;
use lujie\fulfillment\models\FulfillmentAccount;
use lujie\scheduling\CronTask;
use yii\base\InvalidConfigException;
use yii\di\Instance;

/**
 * Class PullFulfillmentChargeTask
 * @package lujie\fulfillment\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PullFulfillmentChargeTask extends BaseFulfillmentTask
{
    /**
     * @var int
     */
    public $pullLimit = 100;

    /**
     * @var int
     */
    public $pullBatchSize = 20;

    /**
     * @return bool
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function execute(): bool
    {
        $this->fulfillmentManager = Instance::ensure($this->fulfillmentManager, FulfillmentManager::class);
        $accountQuery = $this->getAccountQuery();
        foreach ($accountQuery->each() as $account) {
            $accountId = $account->account_id;
            $additional = $account->additional ?? [];
            $this->fulfillmentManager->pullFulfillmentCharges(
                $accountId,
                $additional['ChargePullLimit'] ?? $this->pullLimit,
                $additional['ChargePullBatchSize'] ?? $this->pullBatchSize
            );
        }
        return true;
    }
}

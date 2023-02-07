<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tasks;

use lujie\executing\ProgressInterface;
use lujie\executing\TimeStepProgressTrait;
use lujie\fulfillment\FulfillmentManager;
use lujie\fulfillment\models\FulfillmentAccount;
use lujie\scheduling\CronTask;
use yii\base\InvalidConfigException;
use yii\di\Instance;

/**
 * Class PullWarehouseStockTask
 * @package lujie\fulfillment\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PullFulfillmentOrderTask extends BaseFulfillmentTask implements ProgressInterface
{
    use TimeStepProgressTrait;

    /**
     * @var int|string
     */
    public $timeFrom = '-1 days';

    /**
     * @var int|string
     */
    public $timeTo = 'now';

    /**
     * @var int
     */
    public $timeStep = 43200;

    /**
     * @var int
     */
    public $pullLimit = 100;

    /**
     * @var int
     */
    public $pullBatchSize = 20;

    /**
     * @return \Generator
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function execute(): \Generator
    {
        $this->fulfillmentManager = Instance::ensure($this->fulfillmentManager, FulfillmentManager::class);
        $accountQuery = $this->getAccountQuery();
        $accountCount = $accountQuery->count();
        foreach ($accountQuery->each() as $account) {
            $accountId = $account->account_id;
            yield from $this->executeProgress([$accountId], $accountCount);

            $additional = $account->additional ?? [];
            $this->fulfillmentManager->pullFulfillmentOrders(
                $accountId,
                $additional['OrderPullLimit'] ?? $this->pullLimit,
                $additional['OrderPullBatchSize'] ?? $this->pullBatchSize
            );
            yield $accountId;
        }
        return true;
    }

    /**
     * @param int $timeAtFrom
     * @param int $timeAtTo
     * @param array $params
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function executeTimeStep(int $timeAtFrom, int $timeAtTo, array $params = []): void
    {
        [$accountId] = $params;
        $this->fulfillmentManager->pullShippedFulfillmentOrders($accountId, $timeAtFrom, $timeAtTo);
    }
}

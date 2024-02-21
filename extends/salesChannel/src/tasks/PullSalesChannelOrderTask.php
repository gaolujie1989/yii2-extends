<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\tasks;

use lujie\executing\ProgressInterface;
use lujie\executing\TimeStepProgressTrait;
use lujie\sales\channel\SalesChannelManager;
use yii\base\InvalidConfigException;
use yii\di\Instance;

/**
 * Class PullSalesChannelOrderTask
 * @package lujie\sales\channel\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PullSalesChannelOrderTask extends BaseSalesChannelTask implements ProgressInterface
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
     * @return array
     * @inheritdoc
     */
    public function getParams(): array
    {
        return array_merge(
            $this->getTimeStepProgressParams(),
            ['pullLimit', 'pullBatchSize'],
            parent::getParams()
        );
    }

    /**
     * @return \Generator
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function execute(): \Generator
    {
        $this->salesChannelManager = Instance::ensure($this->salesChannelManager, SalesChannelManager::class);
        $accountQuery = $this->getAccountQuery();
        $accountCount = $accountQuery->count();
        foreach ($accountQuery->each() as $account) {
            $accountId = $account->account_id;
            yield from $this->executeProgress([$accountId], $accountCount);

            $additional = $account->additional ?? [];
            $this->salesChannelManager->pullSalesChannelOrders(
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
        $this->salesChannelManager->pullNewSalesChannelOrders($accountId, $timeAtFrom, $timeAtTo);
    }
}

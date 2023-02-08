<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\tasks;

use lujie\sales\channel\SalesChannelManager;
use lujie\scheduling\CronTask;
use yii\base\InvalidConfigException;
use yii\di\Instance;

/**
 * Class PushSalesChannelOrderTask
 * @package lujie\sales\channel\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PushSalesChannelStockTask extends BaseSalesChannelTask
{
    public $timePeriod = 3600;

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
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function execute(): \Generator
    {
        $this->salesChannelManager = Instance::ensure($this->salesChannelManager, SalesChannelManager::class);
        $accountQuery = $this->getAccountQuery();
        foreach ($accountQuery->each() as $account) {
            $accountId = $account->account_id;
            $additional = $account->additional ?? [];
            $this->salesChannelManager->pushSalesChannelItemStocks(
                $accountId,
                $additional['StockPushTimePeriod'] ?? $this->timePeriod,
                $additional['StockPushLimit'] ?? $this->pullLimit,
                $additional['StockPushBatchSize'] ?? $this->pullBatchSize
            );
            yield $accountId;
        }
        return true;
    }
}

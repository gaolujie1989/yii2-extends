<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tasks;

use lujie\fulfillment\FulfillmentManager;
use yii\base\InvalidConfigException;
use yii\di\Instance;

/**
 * Class PullWarehouseStockTask
 * @package lujie\fulfillment\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PullFulfillmentWarehouseStockMovementTask extends BaseFulfillmentTask
{
    public $timePeriod = 3600;

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
            $this->fulfillmentManager->pullFulfillmentWarehouseStockMovements(
                $accountId,
                $additional['MovementPullTimePeriod'] ?? $this->timePeriod,
            );
        }
        return true;
    }
}

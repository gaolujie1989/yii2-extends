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
class PullFulfillmentOrderTask extends CronTask implements ProgressInterface
{
    use TimeStepProgressTrait;

    /**
     * @var FulfillmentManager
     */
    public $fulfillmentManager = 'fulfillmentManager';

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
     * @return \Generator
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function execute(): \Generator
    {
        $this->fulfillmentManager = Instance::ensure($this->fulfillmentManager, FulfillmentManager::class);
        $accountIds = FulfillmentAccount::find()->active()->column();
        foreach ($accountIds as $accountId) {
            yield from $this->executeProgress([$accountId], count($accountIds));
            $this->fulfillmentManager->pullFulfillmentOrders($accountId);
            yield true;
        }
        return true;
    }

    /**
     * @param int $timeAtFrom
     * @param int $timeAtTo
     * @param array $params
     * @return mixed
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function executeTimeStep(int $timeAtFrom, int $timeAtTo, array $params = []): mixed
    {
        [$accountId] = $params;
        $this->fulfillmentManager->pullShippedFulfillmentOrders($accountId, $timeFrom, $timeTo);
    }
}

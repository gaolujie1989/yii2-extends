<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\tasks;

use lujie\executing\ProgressInterface;
use lujie\executing\ProgressTrait;
use lujie\executing\TimeStepProgressTrait;
use lujie\sales\channel\models\SalesChannelAccount;
use lujie\sales\channel\SalesChannelManager;
use lujie\scheduling\CronTask;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use function MongoDB\BSON\fromJSON;

/**
 * Class PullSalesChannelOrderTask
 * @package lujie\sales\channel\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PullSalesChannelOrderTask extends CronTask implements ProgressInterface
{
    use TimeStepProgressTrait;

    /**
     * @var SalesChannelManager
     */
    public $salesChannelManager = 'salesChannelManager';

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
        $this->salesChannelManager = Instance::ensure($this->salesChannelManager, SalesChannelManager::class);
        $accountIds = SalesChannelAccount::find()->active()->column();
        foreach ($accountIds as $accountId) {
            yield from $this->executeProgress([$accountId], count($accountIds));
            $this->salesChannelManager->pullSalesChannelOrders($accountId);
            yield $accountId;
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
        $this->salesChannelManager->pullNewSalesChannelOrders($accountId, $timeAtFrom, $timeAtTo);
    }
}

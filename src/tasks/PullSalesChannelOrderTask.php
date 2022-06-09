<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\tasks;

use lujie\executing\ProgressInterface;
use lujie\executing\ProgressTrait;
use lujie\sales\channel\models\SalesChannelAccount;
use lujie\sales\channel\SalesChannelManager;
use lujie\scheduling\CronTask;
use yii\base\InvalidConfigException;
use yii\di\Instance;

/**
 * Class PullSalesChannelOrderTask
 * @package lujie\sales\channel\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PullSalesChannelOrderTask extends CronTask implements ProgressInterface
{
    use ProgressTrait;

    /**
     * @var SalesChannelManager
     */
    public $salesChannelManager = 'salesChannelManager';

    /**
     * @var int|string
     */
    public $createdAtFrom = '-1 days';

    /**
     * @var int|string
     */
    public $createdAtTo = 'now';

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
        $this->createdAtFrom = is_numeric($this->createdAtFrom) ? $this->createdAtFrom : strtotime($this->createdAtFrom);
        $this->createdAtTo = is_numeric($this->createdAtTo) ? $this->createdAtTo : strtotime($this->createdAtTo);
        $this->salesChannelManager = Instance::ensure($this->salesChannelManager, SalesChannelManager::class);
        $accountIds = SalesChannelAccount::find()->active()->column();
        $total = ceil(($this->createdAtTo - $this->createdAtFrom) / $this->timeStep + 1) * count($accountIds);
        $progress = $this->getProgress($total);
        foreach ($accountIds as $accountId) {
            for ($timeFrom = $this->createdAtFrom; $timeFrom <= $this->createdAtTo; $timeFrom += $this->timeStep) {
                $progress->message = date('Y-m-d H:i', $timeFrom);
                $timeTo = min($timeFrom + $this->timeStep, $this->createdAtTo);
                $this->salesChannelManager->pullNewSalesChannelOrders($accountId, $timeFrom, $timeTo);
                $progress->done++;
                yield true;
            }
            $this->salesChannelManager->pullSalesChannelOrders($accountId);
            $progress->done++;
            yield true;
        }
    }
}

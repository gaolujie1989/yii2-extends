<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\tasks;

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
class PullSalesChannelOrderTask extends CronTask
{
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
     * @return bool
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function execute(): bool
    {
        $this->createdAtFrom = is_string($this->createdAtFrom) ? strtotime($this->createdAtFrom) : $this->createdAtFrom;
        $this->createdAtTo = is_string($this->createdAtTo) ? strtotime($this->createdAtTo) : $this->createdAtTo;
        $this->salesChannelManager = Instance::ensure($this->salesChannelManager, SalesChannelManager::class);
        $accountIds = SalesChannelAccount::find()->active()->column();
        foreach ($accountIds as $accountId) {
            for ($timeFrom = $this->createdAtFrom; $timeFrom <= $this->createdAtTo; $timeFrom += $this->timeStep) {
                $timeTo = min($timeFrom + $this->timeStep, $this->createdAtTo);
                $this->salesChannelManager->pullNewSalesChannelOrders($accountId, $timeFrom, $timeTo);
            }

            $this->salesChannelManager->pullSalesChannelOrders($accountId);
        }
        return true;
    }
}

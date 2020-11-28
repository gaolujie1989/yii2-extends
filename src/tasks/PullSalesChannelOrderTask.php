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
     * @var ?string
     */
    public $createdAtFrom;

    /**
     * @var ?string
     */
    public $createdAtTo;

    /**
     * @return bool
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function execute(): bool
    {
        $this->createdAtFrom = $this->createdAtFrom ? strtotime($this->createdAtFrom) : $this->createdAtFrom;
        $this->createdAtTo = $this->createdAtTo ? strtotime($this->createdAtTo) : $this->createdAtTo;
        $this->salesChannelManager = Instance::ensure($this->salesChannelManager, SalesChannelManager::class);
        $accountIds = SalesChannelAccount::find()->active()->column();
        foreach ($accountIds as $accountId) {
            $this->salesChannelManager->pullNewSalesChannelOrders($accountId, $this->createdAtFrom, $this->createdAtTo);
            $this->salesChannelManager->pullSalesChannelOrders($accountId);
        }
        return true;
    }
}

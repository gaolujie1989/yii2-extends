<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\jobs;


use lujie\sales\channel\SalesChannelManager;
use yii\di\Instance;
use yii\queue\Queue;

/**
 * Class ShipSalesChannelOrderJob
 * @package lujie\sales\channel\jobs
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShipSalesChannelOrderJob extends BaseSalesChannelOrderJob
{
    /**
     * @param Queue $queue
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function execute($queue): void
    {
        $this->salesChannelManager = Instance::ensure($this->salesChannelManager, SalesChannelManager::class);
        $this->salesChannelManager->shipSalesChannelOrder($this->getSalesChannelOrder());
    }
}
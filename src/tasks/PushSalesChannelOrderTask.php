<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\tasks;

use lujie\sales\channel\models\SalesChannelOrder;
use lujie\sales\channel\SalesChannelManager;
use yii\base\InvalidConfigException;
use yii\di\Instance;

/**
 * Class PushSalesChannelOrderTask
 * @package lujie\sales\channel\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PushSalesChannelOrderTask extends BaseSalesChannelTask
{
    public $salesChannelOrderIds = [];

    /**
     * @return array
     * @inheritdoc
     */
    public function getParams(): array
    {
        return array_merge(['salesChannelOrderIds'], parent::getParams());
    }

    /**
     * @return bool
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function execute(): bool
    {
        $this->salesChannelManager = Instance::ensure($this->salesChannelManager, SalesChannelManager::class);
        $accountIds = $this->getAccountIds();
        foreach ($accountIds as $accountId) {
            $query = SalesChannelOrder::find()
                ->salesChannelAccountId($accountId)
                ->needPush()
                ->notQueuedOrQueuedButNotExecuted();
            if ($this->salesChannelOrderIds) {
                $query->salesChannelOrderId($this->salesChannelOrderIds);
            }
            foreach ($query->each() as $salesChannelOrder) {
                $this->salesChannelManager->pushSalesChannelOrderJob($salesChannelOrder);
            }
        }
        return true;
    }
}

<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\tasks;

use lujie\sales\channel\models\SalesChannelItem;
use lujie\sales\channel\SalesChannelManager;
use yii\base\InvalidConfigException;
use yii\di\Instance;

/**
 * Class PushSalesChannelOrderTask
 * @package lujie\sales\channel\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PushSalesChannelItemTask extends BaseSalesChannelTask
{
    public $pushLimit = 15;

    /**
     * @return bool
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function execute(): bool
    {
        $this->salesChannelManager = Instance::ensure($this->salesChannelManager, SalesChannelManager::class);
        $accountIds = $this->getAccountIds();
        $now = time();
        foreach ($accountIds as $accountId) {
            $query = SalesChannelItem::find()
                ->salesChannelAccountId($accountId)
                ->newUpdatedItems()
                ->notQueuedOrQueuedButNotExecuted()
                ->limit($this->pushLimit);
            foreach ($query->each() as $salesChannelItem) {
                $this->salesChannelManager->pushSalesChannelItemJob($salesChannelItem);
            }

            $query = SalesChannelItem::find()
                ->salesChannelAccountId($accountId)
                ->itemPushedUpdatedAfterAtBetween($now - 864000, $now - 60)
                ->notQueuedOrQueuedButNotExecuted();
            foreach ($query->each() as $salesChannelItem) {
                $this->salesChannelManager->pushSalesChannelItemJob($salesChannelItem);
            }
        }
        return true;
    }
}

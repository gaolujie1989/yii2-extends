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
    /**
     * @var int
     */
    public $pushLimit = 50;

    /**
     * @var int
     */
    public $pushDelay = 5;

    /**
     * @var array
     */
    public $salesChannelItemIds = [];

    /**
     * @return array
     * @inheritdoc
     */
    public function getParams(): array
    {
        return array_merge(['salesChannelItemIds', 'pushLimit', 'pushDelay'], parent::getParams());
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
        $now = time();
        foreach ($accountIds as $accountId) {
            $delay = 0;
            $query = SalesChannelItem::find()
                ->salesChannelAccountId($accountId)
                ->newUpdatedItems()
                ->notQueuedOrQueuedButNotExecuted()
                ->limit($this->pushLimit);
            if ($this->salesChannelItemIds) {
                $query->salesChannelItemId($this->salesChannelItemIds);
            }
            foreach ($query->each() as $salesChannelItem) {
                $this->salesChannelManager->pushSalesChannelItemJob($salesChannelItem, $delay);
                $delay += $this->pushDelay;
            }

            $query = SalesChannelItem::find()
                ->salesChannelAccountId($accountId)
                ->itemPushedUpdatedAfterAtBetween($now - 864000, $now - 60)
                ->notQueuedOrQueuedButNotExecuted();
            if ($this->salesChannelItemIds) {
                $query->salesChannelItemId($this->salesChannelItemIds);
            }
            foreach ($query->each() as $salesChannelItem) {
                $this->salesChannelManager->pushSalesChannelItemJob($salesChannelItem, $delay);
            }
        }
        return true;
    }
}

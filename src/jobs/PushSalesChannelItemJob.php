<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\jobs;

use lujie\sales\channel\models\SalesChannelItem;
use lujie\sales\channel\SalesChannelManager;
use yii\base\InvalidArgumentException;
use yii\di\Instance;
use yii\queue\Queue;

/**
 * Class PushSalesChannelItemJob
 * @package lujie\sales\channel\jobs
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PushSalesChannelItemJob extends BaseSalesChannelJob
{
    /**
     * @var int
     */
    public $salesChannelItemId;

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
        $this->salesChannelManager->pushSalesChannelItem($this->getSalesChannelItem());
    }

    /**
     * @return SalesChannelItem
     * @inheritdoc
     */
    protected function getSalesChannelItem(): SalesChannelItem
    {
        /** @var ?SalesChannelItem $salesChannelItem */
        $salesChannelItem = SalesChannelItem::findOne($this->salesChannelItemId);
        if ($salesChannelItem === null) {
            throw new InvalidArgumentException("Invalid salesChannelItemId {$this->salesChannelItemId}");
        }
        return $salesChannelItem;
    }

    /**
     * @return string
     * @inheritdoc
     */
    public function getRateLimitKey(): string
    {
        $salesChannelItem = $this->getSalesChannelItem();
        return 'SalesChannelItemAccount:' . $salesChannelItem->sales_channel_account_id;
    }
}
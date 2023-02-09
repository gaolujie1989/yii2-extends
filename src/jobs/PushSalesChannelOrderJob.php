<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\jobs;

use lujie\sales\channel\models\SalesChannelOrder;
use lujie\sales\channel\SalesChannelManager;
use yii\base\InvalidArgumentException;
use yii\di\Instance;
use yii\queue\Queue;

/**
 * Class PushSalesChannelOrderJob
 * @package lujie\sales\channel\jobs
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PushSalesChannelOrderJob extends BaseSalesChannelJob
{
    /**
     * @var int
     */
    public $salesChannelOrderId;

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
        $this->salesChannelManager->pushSalesChannelOrder($this->getSalesChannelOrder());
    }

    /**
     * @return SalesChannelOrder
     * @inheritdoc
     */
    protected function getSalesChannelOrder(): SalesChannelOrder
    {
        /** @var ?SalesChannelOrder $salesChannelOrder */
        $salesChannelOrder = SalesChannelOrder::findOne($this->salesChannelOrderId);
        if ($salesChannelOrder === null) {
            throw new InvalidArgumentException("Invalid salesChannelOrderId {$this->salesChannelOrderId}");
        }
        return $salesChannelOrder;
    }

    /**
     * @return string
     * @inheritdoc
     */
    public function getRateLimitKey(): string
    {
        $salesChannelOrder = $this->getSalesChannelOrder();
        return 'SalesChannelOrderAccount:' . $salesChannelOrder->sales_channel_account_id;
    }
}

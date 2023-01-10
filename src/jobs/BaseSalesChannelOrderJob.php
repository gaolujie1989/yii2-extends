<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\jobs;

use lujie\extend\queue\RateLimitDelayJobInterface;
use lujie\sales\channel\models\SalesChannelOrder;
use lujie\sales\channel\SalesChannelManager;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii\queue\JobInterface;

/**
 * Class BaseSalesChannelOrderJob
 * @package lujie\sales\channel\jobs
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseSalesChannelOrderJob extends BaseObject implements JobInterface, RateLimitDelayJobInterface
{
    /**
     * @var SalesChannelManager
     */
    public $salesChannelManager = 'salesChannelManager';

    /**
     * @var int
     */
    public $salesChannelOrderId;

    /**
     * @var int
     */
    public $rateLimitDelay = 2;

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

    /**
     * @return int
     * @inheritdoc
     */
    public function getRateLimitDelay(): int
    {
        return $this->rateLimitDelay;
    }
}

<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\jobs;

use lujie\extend\queue\RateLimitDelayJobInterface;
use lujie\sales\channel\SalesChannelManager;
use yii\base\BaseObject;
use yii\queue\JobInterface;

/**
 * Class BaseSalesChannelOrderJob
 * @package lujie\sales\channel\jobs
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseSalesChannelJob extends BaseObject implements JobInterface, RateLimitDelayJobInterface
{
    /**
     * @var SalesChannelManager
     */
    public $salesChannelManager = 'salesChannelManager';

    /**
     * @var int
     */
    public $rateLimitDelay = 2;

    /**
     * @return int
     * @inheritdoc
     */
    public function getRateLimitDelay(): int
    {
        return $this->rateLimitDelay;
    }
}

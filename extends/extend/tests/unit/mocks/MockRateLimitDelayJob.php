<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\mocks;

use lujie\extend\queue\RateLimitDelayJobInterface;
use lujie\extend\queue\RateLimitDelayJobTrait;
use yii\base\BaseObject;
use yii\queue\JobInterface;

/**
 * Class MockJob
 * @package lujie\extend\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockRateLimitDelayJob extends BaseObject implements JobInterface, RateLimitDelayJobInterface
{
    use RateLimitDelayJobTrait;

    public $accountId;

    public $rateLimitDelay = 2;

    /**
     * @param \yii\queue\Queue $queue
     * @return mixed|void
     * @inheritdoc
     */
    public function execute($queue)
    {
        // TODO: Implement execute() method.
    }

    /**
     * @return string
     * @inheritdoc
     */
    public function getRateLimitKey(): string
    {
        return 'Account:' . $this->accountId;
    }
}

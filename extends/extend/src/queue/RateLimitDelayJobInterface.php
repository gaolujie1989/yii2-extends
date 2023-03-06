<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\queue;

/**
 * Interface RateLimitDelayJobInterface
 * @package lujie\extend\queue
 */
interface RateLimitDelayJobInterface
{
    /**
     * @return string
     * @inheritdoc
     */
    public function getRateLimitKey(): string;

    /**
     * @return int
     * @inheritdoc
     */
    public function getRateLimitDelay(): int;
}

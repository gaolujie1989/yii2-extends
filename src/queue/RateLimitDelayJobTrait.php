<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\queue;

/**
 * Trait RateLimitDelayJobTrait
 *
 * @property string $rateLimitKey;
 * @property int $rateLimitDelay;
 *
 * @package lujie\extend\queue
 */
trait RateLimitDelayJobTrait
{
    /**
     * @return string
     * @inheritdoc
     */
    public function getRateLimitKey(): string
    {
        return $this->rateLimitKey ?? static::class;
    }

    /**
     * @return int
     * @inheritdoc
     */
    public function getRateLimitDelay(): int
    {
        return $this->rateLimitDelay ?? 1;
    }
}

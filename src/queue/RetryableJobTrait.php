<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\queue;

/**
 * Trait RetryableJobTrait
 *
 * @property int $ttr
 * @property int $attempts
 *
 * @package lujie\extend\queue
 */
trait RetryableJobTrait
{
    /**
     * @return int
     * @inheritdoc
     */
    public function getTtr(): int
    {
        return $this->ttr ?? 300;
    }

    /**
     * @param int $attempt
     * @param \Exception|\Throwable $error
     * @return bool
     * @inheritdoc
     */
    public function canRetry($attempt, $error): bool
    {
        return isset($this->attempts) ? $attempt < $this->attempts : false;
    }
}

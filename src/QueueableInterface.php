<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing;

use yii\queue\Queue;

/**
 * Interface QueueableInterface
 * @package lujie\executing
 */
interface QueueableInterface
{
    /**
     * @return bool
     * @inheritdoc
     */
    public function shouldQueued(): bool;

    /**
     * @return Queue|null
     * @inheritdoc
     */
    public function getQueue(): ?Queue;

    /**
     * @return int
     * @inheritdoc
     */
    public function getTtr(): int;

    /**
     * @return int
     * @inheritdoc
     */
    public function getAttempts(): int;
}

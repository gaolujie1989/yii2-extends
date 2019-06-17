<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\scheduling;

use yii\queue\Queue;

/**
 * Interface QueuedTaskInterface
 * @package lujie\scheduling
 */
interface QueuedTaskInterface extends TaskInterface
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

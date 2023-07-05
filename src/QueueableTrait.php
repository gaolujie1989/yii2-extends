<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing;

use yii\di\Instance;
use yii\queue\Queue;

/**
 * Trait QueueableTrait
 * @package lujie\executing
 */
trait QueueableTrait
{
    /**
     * @var bool
     */
    public $shouldQueued = true;

    /**
     * @var string
     */
    public $queue;

    /**
     * @var int
     */
    public $ttr = 0;

    /**
     * @var int
     */
    public $attempts = 0;

    /**
     * @return bool
     * @inheritdoc
     */
    public function shouldQueued(): bool
    {
        return $this->shouldQueued;
    }

    /**
     * @return Queue|null|object
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function getQueue(): ?Queue
    {
        return $this->queue ? Instance::ensure($this->queue, Queue::class) : null;
    }

    /**
     * @return int
     * @inheritdoc
     */
    public function getTtr(): int
    {
        return $this->ttr;
    }

    /**
     * @return int
     * @inheritdoc
     */
    public function getAttempts(): int
    {
        return $this->attempts;
    }
}

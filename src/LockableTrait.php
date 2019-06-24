<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing;

use yii\di\Instance;
use yii\mutex\Mutex;

/**
 * Trait LockableTrait
 * @package lujie\executing
 */
trait LockableTrait
{
    /**
     * @var bool
     */
    public $shouldLock = false;

    /**
     * @var string
     */
    public $mutex;

    /**
     * @var int
     */
    public $timeout = 0;

    /**
     * @return bool
     * @inheritdoc
     */
    public function shouldLock(): bool
    {
        return $this->shouldLock;
    }

    /**
     * @return Mutex|null|object
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function getMutex(): ?Mutex
    {
        return $this->mutex ? Instance::ensure($this->mutex, Mutex::class) : null;
    }

    /**
     * @return string
     * @inheritdoc
     */
    public function getLockKey(): string
    {
        return '';
    }

    /**
     * @return int
     * @inheritdoc
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }
}

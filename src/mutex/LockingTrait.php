<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\mutex;

use yii\di\Instance;
use yii\mutex\Mutex;

/**
 * Trait LockingTrait
 *
 * @property string lockKeyPrefix;
 * @property int lockTimeout;
 *
 * @package lujie\extend\mutex
 */
trait LockingTrait
{
    /**
     * @var Mutex
     */
    public $mutex = 'mutex';

    /**
     * @var int
     */
    private $timeout = 0;

    /**
     * @var string
     */
    private $keyPrefix = '';

    /**
     * @var bool
     */
    private $initialized = false;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function initMutex(): void
    {
        if ($this->initialized) {
            return;
        }

        if ($this->mutex) {
            $this->mutex = Instance::ensure($this->mutex, Mutex::class);
        }
        if (isset($this->lockTimeout) && is_int($this->lockTimeout)) {
            $this->timeout = $this->lockTimeout;
        }
        if (isset($this->lockKeyPrefix) && is_string($this->lockKeyPrefix)) {
            $this->keyPrefix = $this->lockKeyPrefix;
        }
        $this->initialized = true;
    }

    /**
     * @param $name
     * @param $onSuccess
     * @param $onFailure
     * @return mixed|void
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function lockingRun($name, $onSuccess, $onFailure)
    {
        $this->initMutex();
        if ($this->mutex->acquire($name, $this->timeout)) {
            try {
                if ($onSuccess && is_callable($onSuccess)) {
                    return $onSuccess();
                }
            } catch (\Throwable $e) {
                throw $e;
            } finally {
                $this->mutex->release($name);
            }
        } else if ($onFailure && is_callable($onFailure)) {
            return $onFailure();
        }
    }

    /**
     * @param int $timeout
     * @inheritdoc
     */
    public function setLockTimeout(int $timeout = 5): void
    {
        if (isset($this->lockTimeout)) {
            $this->lockTimeout = $timeout;
        }
        $this->timeout = $timeout;
    }
}

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
 * @property string lockKeyPrefix = '';
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
        if (isset($this->lockKeyPrefix) && is_string($this->lockKeyPrefix)) {
            $this->keyPrefix = $this->lockKeyPrefix;
        }
        $this->initialized = true;
    }

    /**
     * @param $name
     * @param $onSuccess
     * @param $onFailure
     * @param int $timeout
     * @return mixed
     * @throws \Throwable
     * @inheritdoc
     */
    public function lockingRun($name, $onSuccess, $onFailure, $timeout = 0)
    {
        $this->initMutex();
        if ($this->mutex->acquire($name, $timeout)) {
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
}

<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\mutex;

use lujie\extend\helpers\ClassHelper;
use lujie\extend\helpers\ExceptionHelper;
use Yii;
use yii\authclient\InvalidResponseException;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\httpclient\Exception;
use yii\mutex\Mutex;

/**
 * Trait LockingTrait
 *
 * @property string $lockKeyPrefix;
 * @property int $lockTimeout;
 *
 * @package lujie\extend\mutex
 */
trait LockingTrait
{
    /**
     * @var ?Mutex
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
    private $mutexInitialized = false;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function initMutex(): void
    {
        if ($this->mutexInitialized) {
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
        } else {
            $this->keyPrefix = ClassHelper::getClassShortName($this) . ':';
        }
        $this->mutexInitialized = true;
    }

    /**
     * @param string $name
     * @return bool
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function acquireLock(string $name): bool
    {
        if ($this->mutex) {
            $this->initMutex();
            $name = $this->keyPrefix . $name;
            return $this->mutex->acquire($name, $this->timeout);
        }
        throw new InvalidConfigException('Mutex not set');
    }

    /**
     * @param string $name
     * @return bool
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function releaseLock(string $name): bool
    {
        if ($this->mutex) {
            $this->initMutex();
            $name = $this->keyPrefix . $name;
            return $this->mutex->release($name);
        }
        throw new InvalidConfigException('Mutex not set');
    }

    /**
     * @param string $name
     * @param callable $onSuccess
     * @param bool $logException
     * @param array $throwExceptions
     * @return mixed
     * @throws InvalidConfigException
     * @throws \Throwable
     * @inheritdoc
     */
    public function lockingRun(string $name, callable $onSuccess, bool $logException = true)
    {
        if ($this->mutex) {
            $this->initMutex();
            $lockName = $this->keyPrefix . $name;
            if ($this->mutex->acquire($lockName, $this->timeout)) {
                try {
                    return $onSuccess();
                } catch (\Throwable $e) {
                    if ($logException) {
                        if ($e instanceof InvalidResponseException) {
                            $statusCode = (string)$e->response->getStatusCode();
                            if ($statusCode === '429') {
                                throw $e;
                            }
                        }
                        $error = $e->getMessage();
                        if ($e instanceof Exception && str_contains($error, 'Curl error')) {
                            Yii::warning($e, $name);
                        } else {
                            Yii::error($e, $name);
                        }
                        return false;
                    }
                    throw $e;
                } finally {
                    $this->mutex->release($lockName);
                }
            } else {
                return false;
            }
        } else {
            return $onSuccess();
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

<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing;


use yii\base\BaseObject;
use yii\di\Instance;
use yii\queue\JobInterface;
use yii\queue\RetryableJobInterface;

class ExecutableJob extends BaseObject implements JobInterface, RetryableJobInterface
{
    /**
     * @var ExecutableInterface
     */
    public $executable;

    /**
     * @var ExecuteManager
     */
    public $executeManager;

    /**
     * @var int
     */
    public $ttr = 300;

    /**
     * @var int
     */
    public $attempts = 1;

    /**
     * @param \yii\queue\Queue $queue
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function execute($queue): void
    {
        $this->executeManager = Instance::ensure($this->executeManager, ExecuteManager::class);
        $this->executeManager->execute($this->executable);
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
     * @param int $attempt
     * @param \Exception|\Throwable $error
     * @return bool
     * @inheritdoc
     */
    public function canRetry($attempt, $error): bool
    {
        return $attempt < $this->attempts;
    }
}

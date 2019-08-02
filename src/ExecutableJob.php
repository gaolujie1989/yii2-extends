<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing;

use yii\base\BaseObject;
use yii\di\Instance;
use yii\queue\JobInterface;
use yii\queue\RetryableJobInterface;

/**
 * Class ExecutableJob
 * @package lujie\executing
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExecutableJob extends BaseObject implements JobInterface, RetryableJobInterface
{
    /**
     * @var ExecutableInterface
     */
    public $executable;

    /**
     * @var Executor
     */
    public $executor;

    /**
     * @var int
     */
    public $ttr = 300;

    /**
     * @var int
     */
    public $attempts = 1;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->executable = Instance::ensure($this->executable, ExecutableInterface::class);
        $this->executable->getExecUid();
    }

    /**
     * @param \yii\queue\Queue $queue
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function execute($queue): void
    {
        $this->executor = Instance::ensure($this->executor, Executor::class);
        $this->executor->execute($this->executable);
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

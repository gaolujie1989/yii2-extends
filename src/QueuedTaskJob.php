<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\scheduling;

use Yii;
use yii\base\BaseObject;
use yii\di\Instance;
use yii\queue\JobInterface;
use yii\queue\Queue;
use yii\queue\RetryableJobInterface;

/**
 * Class QueueTaskJob
 * @package lujie\scheduling\components
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class QueuedTaskJob extends BaseObject implements JobInterface, RetryableJobInterface
{
    /**
     * @var Scheduler
     */
    public $scheduler;

    /**
     * @var string
     */
    public $taskCode;

    /**
     * @var int
     */
    public $ttr = 3600;

    /**
     * @var int
     */
    public $attempts = 3;

    /**
     * @param Queue $queue
     * @throws \Throwable
     * @inheritdoc
     */
    public function execute($queue): void
    {
        try {
            $this->scheduler = Instance::ensure($this->scheduler, Scheduler::class);
            $task = $this->scheduler->getTask($this->taskCode);
            $this->scheduler->executeTask($task);
            Yii::info("Queued task job {$this->taskCode} executed success.", __METHOD__);
        } catch (\Throwable $e) {
            Yii::info("Queued task {$this->taskCode} executed failed. message: {$e->getMessage()}", __METHOD__);
            Yii::error($e, __METHOD__);
            throw $e;
        }
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

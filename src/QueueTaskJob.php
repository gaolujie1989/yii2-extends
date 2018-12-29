<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\scheduling;


use lujie\core\queue\retry\ErrorRetryJobInterface;
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
class QueueTaskJob extends BaseObject implements JobInterface, RetryableJobInterface, ErrorRetryJobInterface
{
    /**
     * @var Scheduler
     */
    public $scheduler;

    /**
     * @var string
     */
    public $taskCode;

    public $ttr = 300;

    public $attempts = 3;

    public $attemptDelay = 5;

    public function execute($queue)
    {
        try {
            $this->scheduler = Instance::ensure($this->scheduler);
            $task = $this->scheduler->getTask($this->taskCode);
            $this->scheduler->executeTask($task);
            Yii::info("Queue task job {$this->taskCode} execute success.", __METHOD__);
        } catch (\Throwable $e) {
            Yii::info("Queue task job {$this->taskCode} execute failed.", __METHOD__);
            Yii::error(strval($e), __METHOD__);
            throw $e;
        }
    }

    public function getTtr()
    {
        return $this->ttr;
    }

    public function canRetry($attempt, $error)
    {
        return $attempt < $this->attempts;
    }

    public function getAttemptDelay($attempt)
    {
        return $this->attemptDelay * $attempt;
    }
}
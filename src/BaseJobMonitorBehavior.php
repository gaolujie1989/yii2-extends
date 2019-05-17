<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\queuing\monitor;


use lujie\extend\helpers\ComponentHelper;
use yii\base\Behavior;
use yii\queue\ErrorEvent;
use yii\queue\ExecEvent;
use yii\queue\PushEvent;
use yii\queue\Queue;

/**
 * Class BaseJobMonitorBehavior
 * @package lujie\queuing\monitor
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseJobMonitorBehavior extends Behavior
{
    const EXEC_STATUS_RUNNING = 1;
    const EXEC_STATUS_SUCCESS = 10;
    const EXEC_STATUS_FAILED = 11;

    /**
     * @var int the probability (parts per million) that clean the expired exec records
     * when log success record. Defaults to 10, meaning 0.1% chance.
     * This number should be between 0 and 10000. A value 0 means no clean will be performed at all.
     */
    public $cleanProbability = 10;

    /**
     * @var array
     */
    public $timeToClean = [
        self::EXEC_STATUS_RUNNING => '-7 days',
        self::EXEC_STATUS_SUCCESS => '-3 day',
        self::EXEC_STATUS_FAILED => '-7 days',
    ];

    public $workerMonitor = 'workerMonitor';

    /**
     * @return array
     * @inheritdoc
     */
    public function events()
    {
        return [
            Queue::EVENT_AFTER_PUSH => 'afterPush',
            Queue::EVENT_BEFORE_EXEC => 'beforeExec',
            Queue::EVENT_AFTER_EXEC => 'afterExec',
            Queue::EVENT_AFTER_ERROR => 'afterError',
        ];
    }

    public function afterPush(PushEvent $event)
    {
        $data = [
            'job_id' => $event->id,
            'queue' => ComponentHelper::getName($event->sender),
            'job' => $event->sender->serializer->serialize($event->job),
            'ttr' => $event->ttr,
            'delay' => $event->delay,
            'pushed_at' => time(),
            'memory_usage' => memory_get_peak_usage(),
        ];
        $this->saveJobRecord($data);
    }

    public function beforeExec(ExecEvent $event)
    {
        $now = time();
        $queueName = ComponentHelper::getName($event->sender);
        $data = [
            'job_id' => $event->id,
            'queue' => $queueName,
            'worker_pid' => $event->sender->getWorkerPid(),
            'attempt' => $event->attempt,
            'started_at' => $now,
            'memory_usage' => memory_get_peak_usage(),
            'status' => self::EXEC_STATUS_RUNNING,
        ];
        $this->saveJobExecRecord($data);

        $jobData = [
            'job_id' => $event->id,
            'queue' => $queueName,
            'last_exec_at' => $now,
            'last_exec_status' => self::EXEC_STATUS_RUNNING,
        ];
        $this->saveJobRecord($jobData);
    }

    public function afterExec(ExecEvent $event)
    {
        $now = time();
        $queueName = ComponentHelper::getName($event->sender);
        $data = [
            'job_id' => $event->id,
            'queue' => $queueName,
            'worker_pid' => $event->sender->getWorkerPid(),
            'attempt' => $event->attempt,
            'finished_at' => $now,
            'memory_usage' => memory_get_peak_usage(),
            'status' => self::EXEC_STATUS_SUCCESS,
        ];
        $this->saveJobExecRecord($data);
        $jobData = [
            'job_id' => $event->id,
            'queue' => $queueName,
            'last_exec_at' => $now,
            'last_exec_status' => self::EXEC_STATUS_SUCCESS,
        ];
        $this->saveJobRecord($jobData);
        $this->updateWorkerExecCount($event, true);
        $this->cleanJobAndExec();
    }

    public function afterError(ErrorEvent $event)
    {
        $now = time();
        $queueName = ComponentHelper::getName($event->sender);
        $data = [
            'job_id' => $event->id,
            'queue' => $queueName,
            'worker_pid' => $event->sender->getWorkerPid(),
            'attempt' => $event->attempt,
            'finished_at' => $now,
            'memory_usage' => memory_get_peak_usage(),
            'error' => $event->error->getMessage() . "\n" . $event->error->getTraceAsString(),
            'retry' => $event->retry,
            'status' => self::EXEC_STATUS_FAILED,
        ];
        $this->saveJobExecRecord($data);
        $jobData = [
            'job_id' => $event->id,
            'queue' => $queueName,
            'last_exec_at' => $now,
            'last_exec_status' => self::EXEC_STATUS_FAILED,
        ];
        $this->saveJobRecord($jobData);
        $this->updateWorkerExecCount($event, false);
    }

    protected abstract function saveJobRecord($data);

    protected abstract function saveJobExecRecord($data);

    /**
     * @param ExecEvent $event
     * @param bool $isSuccess
     * @inheritdoc
     */
    private function updateWorkerExecCount(ExecEvent $event, $isSuccess = true)
    {
        /** @var BaseWorkerMonitorBehavior $workerMonitor */
        $workerMonitor = $event->sender->getBehavior($this->workerMonitor);
        $workerPid = $event->sender->getWorkerPid();
        if ($workerMonitor && $workerPid) {
            $workerMonitor->updateCount($workerPid, $isSuccess);
        }
    }


    /**
     * @param bool $force
     * @inheritdoc
     */
    public function cleanJobAndExec($force = false)
    {
        if ($force || mt_rand(0, 10000) < $this->cleanProbability) {
            $jobCondition = ['OR'];
            $execCondition = ['OR'];
            foreach ($this->timeToClean as $status => $expire) {
                $jobCondition[] = ['AND', ['last_exec_status' => $status], ['<', 'last_exec_at', strtotime($expire)]];
                $execCondition[] = ['AND', ['status' => $status], ['<', 'started_at', strtotime($expire)]];
            }
            $this->deleteJob($jobCondition);
            $this->deleteJobExec($jobCondition);
        }
    }

    /**
     * @param $condition
     * @return mixed
     * @inheritdoc
     */
    abstract protected function deleteJob($condition);

    /**
     * @param $condition
     * @return mixed
     * @inheritdoc
     */
    abstract protected function deleteJobExec($condition);
}

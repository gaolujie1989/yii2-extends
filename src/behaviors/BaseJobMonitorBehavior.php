<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\queuing\monitor\behaviors;

use lujie\extend\constants\ExecStatusConst;
use lujie\extend\helpers\ComponentHelper;
use Yii;
use yii\base\Behavior;
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
        ExecStatusConst::EXEC_STATUS_PENDING => '-7 days',
        ExecStatusConst::EXEC_STATUS_RUNNING => '-7 days',
        ExecStatusConst::EXEC_STATUS_SUCCESS => '-3 day',
        ExecStatusConst::EXEC_STATUS_FAILED => '-7 days',
        ExecStatusConst::EXEC_STATUS_SKIPPED => '-3 days',
        ExecStatusConst::EXEC_STATUS_QUEUED => '-7 days',
    ];

    /**
     * @var string
     */
    public $workerMonitorBehavior = 'workerMonitor';

    /**
     * @return array
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            Queue::EVENT_AFTER_PUSH => 'afterPush',
            Queue::EVENT_BEFORE_EXEC => 'beforeExec',
            Queue::EVENT_AFTER_EXEC => 'afterExec',
            Queue::EVENT_AFTER_ERROR => 'afterError',
        ];
    }

    /**
     * @param PushEvent $event
     * @inheritdoc
     */
    public function afterPush(PushEvent $event): void
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

    /**
     * @param ExecEvent $event
     * @inheritdoc
     */
    public function beforeExec(ExecEvent $event): void
    {
        $now = time();
        $queueName = ComponentHelper::getName($event->sender);
        $data = [
            'job_id' => $event->id,
            'queue' => $queueName,
            'worker_pid' => $event->sender->getWorkerPid() ?: 0,
            'attempt' => $event->attempt,
            'started_at' => $now,
            'memory_usage' => memory_get_peak_usage(),
            'status' => ExecStatusConst::EXEC_STATUS_RUNNING,
        ];
        $this->saveJobExecRecord($data);

        $jobData = [
            'job_id' => $event->id,
            'queue' => ComponentHelper::getName($event->sender),
            'job' => $event->sender->serializer->serialize($event->job),
            'ttr' => $event->ttr,
            'memory_usage' => memory_get_peak_usage(),
            'last_exec_at' => $now,
            'last_exec_status' => ExecStatusConst::EXEC_STATUS_RUNNING,
        ];
        $this->saveJobRecord($jobData);
    }

    /**
     * @param ExecEvent $event
     * @throws \Exception
     * @inheritdoc
     */
    public function afterExec(ExecEvent $event): void
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
            'status' => ExecStatusConst::EXEC_STATUS_SUCCESS,
        ];
        $this->saveJobExecRecord($data);

        $jobData = [
            'job_id' => $event->id,
            'queue' => $queueName,
            'last_exec_at' => $now,
            'last_exec_status' => ExecStatusConst::EXEC_STATUS_SUCCESS,
        ];
        $this->saveJobRecord($jobData);
        $this->updateWorkerExecCount($event, true);
        $this->cleanJobAndExec();
    }

    /**
     * @param ExecEvent $event
     * @inheritdoc
     */
    public function afterError(ExecEvent $event): void
    {
        $now = time();
        $queueName = ComponentHelper::getName($event->sender);
        $error = $event->error->getMessage() . "\n" . $event->error->getTraceAsString();
        $data = [
            'job_id' => $event->id,
            'queue' => $queueName,
            'worker_pid' => $event->sender->getWorkerPid(),
            'attempt' => $event->attempt,
            'finished_at' => $now,
            'memory_usage' => memory_get_peak_usage(),
            'error' => mb_substr($error, 0, 1000),
            'retry' => $event->retry,
            'status' => ExecStatusConst::EXEC_STATUS_FAILED,
        ];
        $this->saveJobExecRecord($data);

        $jobData = [
            'job_id' => $event->id,
            'queue' => $queueName,
            'last_exec_at' => $now,
            'last_exec_status' => ExecStatusConst::EXEC_STATUS_FAILED,
        ];
        $this->saveJobRecord($jobData);
        $this->updateWorkerExecCount($event, false);
    }

    /**
     * @param array $data
     * @inheritdoc
     */
    abstract protected function saveJobRecord(array $data): void;

    /**
     * @param array $data
     * @inheritdoc
     */
    abstract protected function saveJobExecRecord(array $data): void;

    /**
     * @param ExecEvent $event
     * @param bool $success
     * @inheritdoc
     */
    private function updateWorkerExecCount(ExecEvent $event, bool $success): void
    {
        /** @var ?BaseWorkerMonitorBehavior $workerMonitor */
        $workerMonitor = $event->sender->getBehavior($this->workerMonitorBehavior);
        if ($workerMonitor === null) {
            Yii::info('WorkerMonitor is null', __METHOD__);
            return;
        }
        $workerPid = $event->sender->getWorkerPid();
        if ($workerPid === null) {
            Yii::info('WorkerPid is empty', __METHOD__);
            return;
        }
        $workerMonitor->updateCount($workerPid, $success);
    }

    /**
     * @param bool $force
     * @throws \Exception
     * @inheritdoc
     */
    public function cleanJobAndExec($force = false): void
    {
        if ($force || random_int(0, 10000) < $this->cleanProbability) {
            $queueName = ComponentHelper::getName($this->owner);
            $jobCondition = ['OR'];
            $execCondition = ['OR'];
            foreach ($this->timeToClean as $status => $expire) {
                $jobCondition[] = ['AND', ['queue' => $queueName], ['last_exec_status' => $status], ['<', 'last_exec_at', strtotime($expire)]];
                $execCondition[] = ['AND', ['queue' => $queueName], ['status' => $status], ['<', 'started_at', strtotime($expire)]];
            }
            $this->deleteJob($jobCondition);
            $this->deleteJobExec($execCondition);
        }
    }

    /**
     * @param string|array $condition
     * @inheritdoc
     */
    abstract protected function deleteJob($condition): void;

    /**
     * @param string|array $condition
     * @inheritdoc
     */
    abstract protected function deleteJobExec($condition): void;
}

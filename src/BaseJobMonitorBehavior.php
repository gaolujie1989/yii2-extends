<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\queuing\monitor;


use lujie\core\helpers\ComponentHelper;
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
        $data = [
            'job_id' => $event->id,
            'worker_pid' => $event->sender->getWorkerPid(),
            'attempt' => $event->attempt,
            'started_at' => time(),
            'memory_usage' => memory_get_peak_usage(),
        ];
        $this->saveJobExecRecord($data);
    }

    public function afterExec(ExecEvent $event)
    {
        $data = [
            'job_id' => $event->id,
            'worker_pid' => $event->sender->getWorkerPid(),
            'attempt' => $event->attempt,
            'finished_at' => time(),
            'memory_usage' => memory_get_peak_usage(),
        ];
        $this->saveJobExecRecord($data);
        $this->updateWorkerExecCount($event, true);
    }

    public function afterError(ErrorEvent $event)
    {
        $data = [
            'job_id' => $event->id,
            'worker_pid' => $event->sender->getWorkerPid(),
            'attempt' => $event->attempt,
            'finished_at' => time(),
            'memory_usage' => memory_get_peak_usage(),
            'error' => $event->error->getMessage() . "\n" . $event->error->getTraceAsString(),
            'retry' => $event->retry,
        ];
        $this->saveJobExecRecord($data);
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
        if ($workerMonitor) {
            $workerMonitor->updateCount($isSuccess);
        }
    }
}

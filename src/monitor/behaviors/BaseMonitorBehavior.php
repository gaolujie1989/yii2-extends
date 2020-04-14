<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing\monitor\behaviors;

use lujie\executing\ExecutableInterface;
use lujie\executing\ExecuteEvent;
use lujie\executing\Executor;
use lujie\executing\QueuedEvent;
use lujie\extend\constants\ExecStatusConst;
use lujie\extend\helpers\ComponentHelper;
use yii\base\Behavior;
use yii\helpers\Json;

/**
 * Class BaseMonitorBehavior
 *
 * @property Executor $owner;
 *
 * @package lujie\executing\monitor
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseMonitorBehavior extends Behavior
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
        ExecStatusConst::EXEC_STATUS_SUCCESS => '-3 day',
        ExecStatusConst::EXEC_STATUS_RUNNING => '-7 days',
        ExecStatusConst::EXEC_STATUS_FAILED => '-7 days',
        ExecStatusConst::EXEC_STATUS_SKIPPED => '-3 day',
        ExecStatusConst::EXEC_STATUS_QUEUED => '-7 days',
    ];

    /**
     * @var array
     */
    public $queues;

    /**
     * @var callable
     */
    public $additionalCallback;

    /**
     * @return array
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            Executor::EVENT_AFTER_QUEUED => 'afterQueued',
            Executor::EVENT_BEFORE_EXEC => 'beforeExec',
            Executor::EVENT_AFTER_EXEC => 'afterExec',
            Executor::EVENT_AFTER_SKIP => 'afterSkip',
        ];
    }

    /**
     * @param ExecutableInterface $executable
     * @return array
     * @inheritdoc
     */
    public function getExecutableAdditional(ExecutableInterface $executable): array
    {
        if ($this->additionalCallback && is_callable($this->additionalCallback)) {
            return call_user_func($this->additionalCallback, $executable);
        }
        return [];
    }

    /**
     * @param QueuedEvent $event
     * @inheritdoc
     */
    public function afterQueued(QueuedEvent $event): void
    {
        $job = $event->job;
        $data = [
            'queued_at' => time(),
            'status' => ExecStatusConst::EXEC_STATUS_QUEUED,
            'memory_usage' => memory_get_peak_usage(),
            'executable' => Json::encode($job->executable),
            'additional' => $this->getExecutableAdditional($job->executable),
        ];
        $executeManagerName = $job->executor;
        $this->saveExec($job->executable, $executeManagerName, $data);
    }

    /**
     * @param ExecuteEvent $event
     * @inheritdoc
     */
    public function beforeExec(ExecuteEvent $event): void
    {
        $data = [
            'started_at' => time(),
            'status' => ExecStatusConst::EXEC_STATUS_RUNNING,
            'memory_usage' => memory_get_peak_usage(),
            'executable' =>  Json::encode($event->executable),
            'additional' => $this->getExecutableAdditional($event->executable),
        ];
        $executeManagerName = ComponentHelper::getName($event->sender);
        $this->saveExec($event->executable, $executeManagerName, $data);
    }

    /**
     * @param ExecuteEvent $event
     * @throws \Exception
     * @inheritdoc
     */
    public function afterExec(ExecuteEvent $event): void
    {
        $data = [
            'finished_at' => time(),
            'status' => $event->error ? ExecStatusConst::EXEC_STATUS_FAILED : ExecStatusConst::EXEC_STATUS_SUCCESS,
            'memory_usage' => memory_get_peak_usage(),
            'additional' => $this->getExecutableAdditional($event->executable),
        ];
        if ($event->error) {
            $error = $event->error->getMessage() . "\n" . $event->error->getTraceAsString();
            $data['error'] = mb_substr($error, 0, 1000);
        }
        $executeManagerName = ComponentHelper::getName($event->sender);
        $this->saveExec($event->executable, $executeManagerName, $data);
        $this->cleanExec();
    }

    /**
     * @param ExecuteEvent $event
     * @inheritdoc
     */
    public function afterSkip(ExecuteEvent $event): void
    {
        $data = [
            'skipped_at' => time(),
            'status' => ExecStatusConst::EXEC_STATUS_SKIPPED,
            'memory_usage' => memory_get_peak_usage(),
            'additional' => $this->getExecutableAdditional($event->executable),
        ];
        $executeManagerName = ComponentHelper::getName($event->sender);
        $this->saveExec($event->executable, $executeManagerName, $data);
    }

    /**
     * @param ExecutableInterface $executable
     * @param string $executeManagerName
     * @param array $data
     * @inheritdoc
     */
    abstract protected function saveExec(ExecutableInterface $executable, string $executeManagerName, array $data): void;

    /**
     * @param bool $force
     * @throws \Exception
     * @inheritdoc
     */
    public function cleanExec($force = false): void
    {
        if ($force || random_int(0, 10000) < $this->cleanProbability) {
            $condition = ['OR'];
            foreach ($this->timeToClean as $status => $expire) {
                $condition[] = ['AND', ['status' => $status], ['<', 'started_at', strtotime($expire)]];
            }
            $executeManagerName = ComponentHelper::getName($this->owner);
            $condition = ['AND', ['executor' => $executeManagerName], $condition];
            $this->deleteExec($condition);
        }
    }

    /**
     * @param array $condition
     * @inheritdoc
     */
    abstract protected function deleteExec(array $condition): void;

}

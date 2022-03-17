<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing;

use lujie\extend\helpers\ComponentHelper;
use yii\base\Component;
use yii\di\Instance;
use yii\mutex\Mutex;
use yii\queue\Queue;

/**
 * Class ExecuteManager
 * @package lujie\execute
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Executor extends Component
{
    public const EVENT_BEFORE_QUEUED = 'beforeQueued';
    public const EVENT_AFTER_QUEUED = 'afterQueued';

    public const EVENT_BEFORE_EXEC = 'beforeExec';
    public const EVENT_AFTER_EXEC = 'afterExec';
    public const EVENT_AFTER_SKIP = 'afterSkip';
    public const EVENT_UPDATE_PROGRESS = 'updateProgress';

    /**
     * @var Queue
     */
    public $queue = 'queue';

    /**
     * @var array
     */
    public $jobConfig = [];

    /**
     * @var Mutex
     */
    public $mutex = 'mutex';

    /**
     * @var string
     */
    public $mutexNamePrefix = 'execute:';

    /**
     * @param ExecutableInterface $executable
     * @return bool|string|null
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function handle(ExecutableInterface $executable)
    {
        if ($executable instanceof QueueableInterface && $executable->shouldQueued()) {
            return $this->handleQueueable($executable);
        }
        return $this->execute($executable);
    }

    /**
     * @param QueueableInterface $queueable
     * @return string|null
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    private function handleQueueable(QueueableInterface $queueable): ?string
    {
        /** @var Queue $queue */
        $queue = Instance::ensure($queueable->getQueue() ?: $this->queue);
        $job = $this->createQueueJob($queueable);

        $event = new QueuedEvent(['job' => $job, 'queue' => $queue]);
        $this->trigger(self::EVENT_BEFORE_QUEUED, $event);
        if ($event->handled) {
            return null;
        }

        $event->jobId = $queue->push($job);

        $this->trigger(self::EVENT_AFTER_QUEUED, $event);
        return (string)$event->jobId;
    }

    /**
     * @param QueueableInterface $queueable
     * @return ExecutableJob
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    private function createQueueJob(QueueableInterface $queueable): ExecutableJob
    {
        $jobConfig = $this->jobConfig;
        if (empty($jobConfig['class'])) {
            $jobConfig['class'] = ExecutableJob::class;
        }
        $jobConfig['executable'] = $queueable;
        /** @var ExecutableJob $job */
        $job = Instance::ensure($jobConfig, ExecutableJob::class);
        $job->executor = ComponentHelper::getName($this);

        if ($queueable->getTtr()) {
            $job->ttr = $queueable->getTtr();
        }
        if ($queueable->getAttempts()) {
            $job->attempts = $queueable->getAttempts();
        }
        return $job;
    }

    /**
     * @param ExecutableInterface $executable
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function execute(ExecutableInterface $executable): bool
    {
        $event = new ExecuteEvent(['executable' => $executable]);

        if ($executable instanceof LockableInterface && $executable->shouldLocked()) {
            $mutexName = $this->mutexNamePrefix . ($executable->getLockKey() ?: $executable->getId());
            /** @var Mutex $mutex */
            $mutex = Instance::ensure($executable->getMutex() ?: $this->mutex, Mutex::class);
            if (!$mutex->acquire($mutexName, $executable->getTimeout())) {
                $this->trigger(self::EVENT_AFTER_SKIP, $event);
                return false;
            }
        }

        try {
            $this->trigger(self::EVENT_BEFORE_EXEC, $event);
            if ($event->executed) {
                $this->trigger(self::EVENT_AFTER_SKIP, $event);
                return false;
            }

            $result = $event->executable->execute();
            if ($event->executable instanceof ProgressInterface && $result instanceof \Generator) {
                foreach ($result as $item) {
                    $event->result = $item;
                    $event->progress = $event->executable->getProgress();
                    $this->trigger(self::EVENT_UPDATE_PROGRESS, $event);
                    if ($event->progress->break) {
                        $event->error = 'User Break';
                        break;
                    }
                }
            } else {
                $event->result = $result;
            }

            $this->trigger(self::EVENT_AFTER_EXEC, $event);
            return true;
        } catch (\Throwable $e) {
            $event->error = $e;
            $this->trigger(self::EVENT_AFTER_EXEC, $event);

            return false;
        } finally {
            if (isset($mutex, $mutexName)
                && $executable instanceof LockableInterface && $executable->shouldLocked()) {
                $mutex->release($mutexName);
            }
        }
    }
}

<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing;

use lujie\extend\helpers\ComponentHelper;
use lujie\extend\helpers\MemoryHelper;
use yii\base\Component;
use yii\base\Model;
use yii\base\UserException;
use yii\di\Instance;
use yii\helpers\Json;
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
    public const EVENT_AFTER_FOLLOW_TASK = 'afterSubTask';
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

        $event->jobId = $queue->delay($queueable->getDelay())->push($job);

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

        if ($memoryLimit = $executable->getMemoryLimit()) {
            $oldMemoryLimit = ini_get('memory_limit');
            ini_set('memory_limit', $memoryLimit);
        }
        try {
            $this->trigger(self::EVENT_BEFORE_EXEC, $event);
            if ($event->executed) {
                $this->trigger(self::EVENT_AFTER_SKIP, $event);
                return false;
            }

            $newExecutable = $event->executable;
            if (($newExecutable instanceof Model) && !$newExecutable->validate()) {
                $event->isValid = false;
                $event->error = new UserException(Json::encode($newExecutable->getErrors()));
                $this->trigger(self::EVENT_AFTER_EXEC, $event);
                return false;
            }

            if ($newExecutable instanceof SubTaskInterface && $newExecutable->shouldSubTask()) {
                if ($newExecutable instanceof ProgressInterface) {
                    $event->progress = $newExecutable->getProgress();
                }
                $subTasks = $newExecutable->createSubTasks();
                foreach ($subTasks as $subTask) {
                    $this->handle($subTask);

                    if ($event->progress) {
                        $this->trigger(self::EVENT_UPDATE_PROGRESS, $event);
                        if ($event->progress->break) {
                            $event->error = new UserException('User Break');
                            break;
                        }
                    }
                }
                $this->trigger(self::EVENT_AFTER_EXEC, $event);
                return true;
            }

            $result = $newExecutable->execute();
            if ($newExecutable instanceof ProgressInterface && $result instanceof \Generator) {
                $event->progress = $newExecutable->getProgress();
                $startTime = time();
                $timeLimit = 0;
                if ($newExecutable instanceof QueueableInterface) {
                    $timeLimit = $newExecutable->getTtr() - 30;
                }
                foreach ($result as $item) {
                    $event->result = $item;
                    $this->trigger(self::EVENT_UPDATE_PROGRESS, $event);
                    if ($event->progress->break) {
                        $event->error = new UserException('User Break');
                        break;
                    }
                    if ($timeLimit > 0 && time() - $startTime >= $timeLimit) {
                        $event->error = new UserException('Time Limit');
                        break;
                    }
                }
                if (empty($event->error)) {
                    $event->result = $result->getReturn();
                }
            } else {
                $event->result = $result;
            }

            if ($newExecutable instanceof FollowTaskInterface && $newExecutable->shouldFollowTask()) {
                $followTasks = $newExecutable->createFollowTasks();
                foreach ($followTasks as $subTask) {
                    $this->handle($subTask);
                }
                $this->trigger(self::EVENT_AFTER_FOLLOW_TASK, $event);
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
            if (isset($oldMemoryLimit)) {
                $memoryUsage = memory_get_usage(true);
                if (MemoryHelper::getMemory($oldMemoryLimit) >= $memoryUsage) {
                    ini_set('memory_limit', $oldMemoryLimit);
                } else {
                    ini_set('memory_limit', MemoryHelper::getAllowedMemoryLimit($memoryUsage));
                }
            }
        }
    }
}

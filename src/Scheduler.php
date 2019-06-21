<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\scheduling;

use lujie\extend\helpers\ComponentHelper;
use lujie\data\loader\DataLoaderInterface;
use Yii;
use yii\base\Component;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\mutex\Mutex;
use yii\queue\Queue;

/**
 * Class Scheduler
 * @package lujie\scheduling\components
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Scheduler extends Component
{
    public const EVENT_BEFORE_EXEC = 'beforeExec';
    public const EVENT_AFTER_EXEC = 'afterExec';
    public const EVENT_AFTER_ERROR = 'afterError';
    public const EVENT_AFTER_SKIP = 'afterSkip';

    /**
     * @var Queue
     */
    public $queue = 'queue';

    /**
     * @var Mutex
     */
    public $mutex = 'mutex';

    /**
     * @var string
     */
    public $mutexPrefix = 'scheduler:';

    /**
     * @var DataLoaderInterface
     */
    public $taskLoader;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->taskLoader = Instance::ensure($this->taskLoader, DataLoaderInterface::class);
    }

    /**
     * @return array|TaskInterface[]
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function getTasks(): array
    {
        $tasks = $this->taskLoader->all();
        foreach ($tasks as $taskCode => $task) {
            if (!($task instanceof TaskInterface)) {
                $taskConfig = array_merge(ArrayHelper::toArray($task, [], false), ['taskCode' => $taskCode]);
                if (empty($taskConfig['class'])) {
                    $taskConfig['class'] = CronTask::class;
                }
                $tasks[$taskCode] = Instance::ensure($taskConfig, TaskInterface::class);
            }
        }
        return $tasks;
    }

    /**
     * @param string $taskCode
     * @return TaskInterface|QueuedTaskInterface
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function getTask(string $taskCode) : TaskInterface
    {
        $task = $this->taskLoader->get($taskCode);
        if (empty($task)) {
            throw new InvalidArgumentException("Task code {$taskCode} not found.");
        }
        if (!($task instanceof TaskInterface)) {
            $taskConfig = array_merge(ArrayHelper::toArray($task, [], false), ['taskCode' => $taskCode]);
            if (empty($taskConfig['class'])) {
                $taskConfig['class'] = CronTask::class;
            }
            $task = Instance::ensure($taskConfig, TaskInterface::class);
        }
        return $task;
    }

    /**
     * @return array
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function getDueTasks(): array
    {
        $dueTasks = [];
        foreach ($this->getTasks() as $task) {
            if ($task->isDue()) {
                $dueTasks[] = $task;
            }
        }
        return $dueTasks;
    }

    /**
     * @throws \Throwable
     * @inheritdoc
     */
    public function run(): void
    {
        $dueTasks = $this->getDueTasks();
        foreach ($dueTasks as $task) {
            $this->handleTask($task);
        }
    }

    /**
     * @param TaskInterface $task
     * @return bool|string|null
     * @throws InvalidConfigException
     * @throws \Throwable
     * @inheritdoc
     */
    public function handleTask(TaskInterface $task)
    {
        if ($task instanceof QueuedTaskInterface && $task->shouldQueued()) {
            return $this->handleQueuedTask($task);
        }
        return $this->executeTask($task);
    }

    /**
     * @param QueuedTaskInterface $task
     * @return string|null
     * @throws InvalidConfigException
     * @inheritdoc
     */
    private function handleQueuedTask(QueuedTaskInterface $task): ?string
    {
        /** @var Queue $queue */
        $queue = Instance::ensure($task->getQueue() ?: $this->queue);
        $job = new QueuedTaskJob([
            'scheduler' => ComponentHelper::getName($this),
            'taskCode' => $task->getTaskCode(),
        ]);
        if ($task->getTtr()) {
            $job->ttr = $task->getTtr();
        }
        if ($task->getAttempts()) {
            $job->attempts = $task->getAttempts();
        }
        $jobId = $queue->push($job);

        $queueName = ComponentHelper::getName($queue);
        Yii::info("Push task {$task->getTaskCode()} to {$queueName}", __METHOD__);
        return $jobId;
    }

    /**
     * @param TaskInterface $task
     * @return bool
     * @throws InvalidConfigException
     * @throws \Throwable
     * @inheritdoc
     */
    public function executeTask(TaskInterface $task): bool
    {
        $event = new TaskEvent(['task' => $task]);
        $taskCode = $task->getTaskCode();
        $mutexName = ($this->mutexPrefix ?: ComponentHelper::getName($this)) . $taskCode;

        if ($task instanceof WithoutOverlappingTaskInterface && $task->isWithoutOverlapping()) {
            /** @var Mutex $mutex */
            $mutex = Instance::ensure($task->getMutex() ?: $this->mutex, Mutex::class);
            if (!$mutex->acquire($mutexName, $task->getExpiresAt())) {
                Yii::info("Task {$taskCode} is running in another scheduler, skip.", __METHOD__);
                $this->trigger(self::EVENT_AFTER_SKIP, $event);
                return false;
            }
        }

        try {
            $this->trigger(self::EVENT_BEFORE_EXEC, $event);
            if ($event->executed) {
                Yii::info("Task {$taskCode} executed in events, skip.", __METHOD__);
                $this->trigger(self::EVENT_AFTER_SKIP, $event);
                return false;
            }

            $event->task->execute();
            Yii::info("Task {$taskCode} executed success.", __METHOD__);

            $this->trigger(self::EVENT_AFTER_EXEC, $event);
            return true;
        } catch (\Throwable $e) {
            Yii::info("Task {$taskCode} executed failed.", __METHOD__);
            Yii::error($e, __METHOD__);

            $event->error = $e;
            $this->trigger(self::EVENT_AFTER_ERROR, $event);

            return false;
        } finally {
            if (isset($mutex) && $task instanceof WithoutOverlappingTaskInterface && $task->isWithoutOverlapping()) {
                $mutex->release($mutexName);
            }
        }
    }
}

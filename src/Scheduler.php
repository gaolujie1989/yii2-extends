<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\scheduling;

use lujie\core\helpers\ComponentHelper;
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
    const EVENT_BEFORE_EXEC = 'beforeExec';
    const EVENT_AFTER_EXEC = 'afterExec';
    const EVENT_AFTER_ERROR = 'afterError';
    const EVENT_AFTER_SKIP = 'afterSkip';

    /**
     * @var string
     */
    public $queue = 'queue';

    public $mutex = 'mutex';

    public $mutexPrefix = 'scheduler:';

    /**
     * @var DataLoaderInterface
     */
    public $taskLoader;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->taskLoader = Instance::ensure($this->taskLoader, DataLoaderInterface::class);
    }

    /**
     * @return TaskInterface[]
     * @inheritdoc
     */
    public function getTasks()
    {
        $tasks = $this->taskLoader->loadAll();
        foreach ($tasks as $taskCode => $task) {
            if (!($task instanceof TaskInterface)) {
                $taskData = array_merge(ArrayHelper::toArray($task), ['taskCode' => $taskCode]);
                $tasks[$taskCode] = new CronTask(['data' => $taskData]);
            }
        }
        return $tasks;
    }

    /**
     * @param $taskCode
     * @return TaskInterface
     * @inheritdoc
     */
    public function getTask($taskCode) : TaskInterface
    {
        $task = $this->taskLoader->loadByKey($taskCode);
        if (empty($task)) {
            throw new InvalidArgumentException("Task code {$taskCode} not found.");
        }
        if (!($task instanceof TaskInterface)) {
            $taskData = array_merge(ArrayHelper::toArray($task), ['taskCode' => $taskCode]);
            $task = new CronTask(['data' => $taskData]);
        }
        return $task;
    }

    /**
     * @return TaskInterface[]
     * @inheritdoc
     */
    public function getDueTasks()
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
    public function run()
    {
        $dueTasks = $this->getDueTasks();
        foreach ($dueTasks as $task) {
            $this->handleTask($task);
        }
    }

    /**
     * @param TaskInterface $task
     * @param null $pool
     * @throws \Throwable
     * @inheritdoc
     */
    public function handleTask(TaskInterface $task)
    {
        if ($task instanceof QueuedTaskInterface && $task->shouldQueued()) {
            $this->handleQueuedTask($task);
        } else {
            $this->executeTask($task);
        }
    }

    /**
     * @param TaskInterface|QueuedTaskInterface $task
     * @throws InvalidConfigException
     * @inheritdoc
     */
    private function handleQueuedTask(QueuedTaskInterface $task)
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
     * @throws \Throwable
     * @inheritdoc
     */
    public function executeTask(TaskInterface $task)
    {
        $event = new TaskEvent(['task' => $task]);
        $taskCode = $task->getTaskCode();
        $mutexName = ($this->mutexPrefix ?: ComponentHelper::getName($this)) . $taskCode;

        if ($task instanceof WithoutOverlappingTaskInterface && $task->isWithoutOverlapping()) {
            /** @var Mutex $mutex */
            $mutex = Instance::ensure($task->getMutex() ?: $this->mutex);
            if (!$mutex->acquire($mutexName, $task->getExpiresAt())) {
                Yii::info("Task {$taskCode} is running in another scheduler, skip.", __METHOD__);
                $this->trigger(self::EVENT_AFTER_SKIP, $event);
                return;
            }
        }

        try {
            $this->trigger(self::EVENT_BEFORE_EXEC, $event);
            if ($event->executed) {
                Yii::info("Task {$taskCode} executed in events, skip.", __METHOD__);
                $this->trigger(self::EVENT_AFTER_SKIP, $event);
                return;
            }

            $task->execute();
            Yii::info("Task {$taskCode} executed success.", __METHOD__);

            $this->trigger(self::EVENT_AFTER_EXEC, $event);
        } catch (\Throwable $e) {
            Yii::info("Task {$taskCode} executed failed.", __METHOD__);
            Yii::error($e, __METHOD__);

            $errorEvent = new TaskErrorEvent(['task' => $task, 'error' => $e]);
            $this->trigger(self::EVENT_AFTER_ERROR, $errorEvent);

            throw $e;
        } finally {
            if ($task instanceof WithoutOverlappingTaskInterface && $task->isWithoutOverlapping() && isset($mutex)) {
                $mutex->release($mutexName);
            }
        }
    }
}

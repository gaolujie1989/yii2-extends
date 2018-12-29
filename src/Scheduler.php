<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\scheduling;


use Jenner\SimpleFork\FixedPool;
use Jenner\SimpleFork\Process;
use Workerman\Connection\AsyncTcpConnection;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\di\Instance;
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

    protected $tasks;

    public $queue = 'queue';

    public $mutex = 'mutex';

    /**
     * @return TaskInterface[]
     * @inheritdoc
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * @param $taskCode
     * @return TaskInterface
     * @inheritdoc
     */
    public function getTask($taskCode)
    {
        $tasks = $this->getTasks();
        return $tasks[$taskCode];
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        /** @var TaskInterface[] $dueTasks */
        $dueTasks = array_filter($this->getTasks(), function ($task) {
            /** @var TaskInterface $task */
            return $task->isDue();
        });

        $pool = new FixedPool(20);
        foreach ($dueTasks as $task) {
            $this->handleTask($task, $pool);
        }
        $pool->wait(true, 1000000);
    }

    /**
     * @param TaskInterface $task
     * @param null $pool
     * @throws \Throwable
     * @inheritdoc
     */
    public function handleTask(TaskInterface $task, $pool = null)
    {
        $pool = $pool ?: new FixedPool(20);
        if ($task instanceof QueueTaskInterface && $task->shouldQueue()) {
            /** @var Queue $queue */
            $queue = Instance::ensure($task->getQueue() ?: $this->queue);
            $job = new QueueTaskJob([
                'scheduler' => $this->getName(),
                'taskCode' => $task->getTaskCode(),
            ]);
            if ($task->getTtr()) {
                $job->ttr = $task->getTtr();
            }
            if ($task->getAttempts()) {
                $job->attempts = $task->getAttempts();
            }
            $queue->push($job);
            Yii::info("Push task {$task->getTaskCode()} to queue", __METHOD__);
        } else if ($task instanceof AsyncTaskInterface && $task->shouldAsync()) { //only for workerman
            $taskConnection = new AsyncTcpConnection($task->getAsyncAddress());
            $data = [[$this, 'executeTask'], [$task]];
            $taskConnection->send(serialize($data));
            $taskConnection->onMessage = function ($conn) {
                $conn->close();
            };
            $taskConnection->connect();
            Yii::info("Send async task {$task->getTaskCode()}", __METHOD__);
        } else if ($task instanceof ForkTaskInterface && $task->shouldFork() && strpos(PHP_SAPI, 'cli') !== false) {
            $processName = 'Scheduler execute ' . $task->getTaskCode();
            $process = new Process(function () use ($task) {
                $this->executeTask($task);
            }, $processName);
            $pool->execute($process);
            Yii::info("Fork to execute task {$task->getTaskCode()}", __METHOD__);
        } else {
            $this->executeTask($task);
            Yii::info("Execute task {$task->getTaskCode()}", __METHOD__);
        }
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
        $mutexName = __METHOD__ . $taskCode;
        if ($task instanceof WithoutOverlappingTaskInterface && $task->isWithoutOverlapping()) {
            /** @var Mutex $mutex */
            $mutex = Instance::ensure($task->getMutex() ?: $this->mutex);
            if (!$mutex->acquire($mutexName, $task->getExpiresAt())) {
                Yii::info("Task {$taskCode} is running in another scheduler, skip.", __METHOD__);
                $this->trigger(self::EVENT_AFTER_SKIP, $event);
                return;
            }
        }

        $this->trigger(self::EVENT_BEFORE_EXEC, $event);
        if ($event->executed) {
            Yii::info("Task {$taskCode} executed in events, skip.", __METHOD__);
            $this->trigger(self::EVENT_AFTER_SKIP, $event);
            return;
        }

        try {
            $task->execute();
            Yii::info("Task {$taskCode} executed success.", __METHOD__);
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
        $this->trigger(self::EVENT_AFTER_EXEC, $event);
    }

    /**
     * @return int|string
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function getName()
    {
        foreach (Yii::$app->getComponents(false) as $id => $component) {
            if ($component === $this) {
                return $id;
            }
        }
        throw new InvalidConfigException('Scheduler must be an application component.');
    }
}
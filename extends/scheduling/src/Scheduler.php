<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\scheduling;

use lujie\data\loader\DataLoaderInterface;
use lujie\executing\Executor;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\di\Instance;

/**
 * Class Scheduler
 * @package lujie\scheduling\components
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Scheduler extends Executor
{
    /**
     * @var string
     */
    public $mutexNamePrefix = 'scheduler:';

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
     * @param int|string $taskId
     * @param array|mixed $taskConfig
     * @return ScheduleTaskInterface|object
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function createTask($taskId, $taskConfig): ScheduleTaskInterface
    {
        if (empty($taskConfig['id'])) {
            $taskConfig['id'] = $taskId;
        }
        return Instance::ensure($taskConfig, CronTask::class);
    }

    /**
     * @return array|ScheduleTaskInterface[]
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function getTasks(): array
    {
        $tasks = $this->taskLoader->all();
        foreach ($tasks as $taskId => $task) {
            if (!($task instanceof ScheduleTaskInterface)) {
                $tasks[$taskId] = $this->createTask($taskId, $task);
            }
        }
        return $tasks;
    }

    /**
     * @param string|int $taskId
     * @param array $params
     * @return ScheduleTaskInterface
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function getTask($taskId, array $params = []): ScheduleTaskInterface
    {
        $task = $this->taskLoader->get($taskId);
        if (empty($task)) {
            throw new InvalidArgumentException("Task ID {$taskId} not found.");
        }
        if (!($task instanceof ScheduleTaskInterface)) {
            $task = $this->createTask($taskId, $task);
        }
        $task->setParams($params);
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
            $this->handle($task);
        }
    }
}

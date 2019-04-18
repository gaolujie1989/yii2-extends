<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling;


use yii\console\Controller;
use yii\di\Instance;
use yii\helpers\VarDumper;

/**
 * Class SchedulerCommand
 * @package lujie\scheduling
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SchedulerCommand extends Controller
{
    /**
     * @var Scheduler
     */
    public $scheduler = 'scheduler';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->scheduler = Instance::ensure($this->scheduler, Scheduler::class);
    }

    /**
     * @return mixed|void
     * @throws \Throwable
     * @inheritdoc
     */
    public function actionRun()
    {
        $this->scheduler->run();
    }

    /**
     * @param $taskCode
     * @throws \Throwable
     * @inheritdoc
     */
    public function actionHandle($taskCode)
    {
        $task = $this->scheduler->getTask($taskCode);
        $this->scheduler->handleTask($task);
    }

    /**
     * @param $taskCode
     * @throws \Throwable
     * @inheritdoc
     */
    public function actionExecute($taskCode)
    {
        $task = $this->scheduler->getTask($taskCode);
        $this->scheduler->executeTask($task);
    }

    /**
     * @inheritdoc
     */
    public function actionTasks()
    {
        $tasks = $this->scheduler->getTasks();
        VarDumper::dump($tasks);
    }
}

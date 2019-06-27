<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling\controllers\rest;

use lujie\scheduling\Scheduler;
use yii\base\InvalidConfigException;
use yii\data\ArrayDataProvider;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;

/**
 * Class ScheduleTaskController
 * @package lujie\scheduling\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SchedulerController extends Controller
{
    /**
     * @var Scheduler
     */
    public $scheduler = 'scheduler';

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->scheduler = Instance::ensure($this->scheduler, Scheduler::class);
    }

    /**
     * @param $taskCode
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function actionHandle($taskCode): void
    {
        $task = $this->scheduler->getTask($taskCode);
        $this->scheduler->handle($task);
    }

    /**
     * @param $taskCode
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function actionExecute($taskCode): void
    {
        $task = $this->scheduler->getTask($taskCode);
        $this->scheduler->execute($task);
    }

    /**
     * @return ArrayDataProvider
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function actionTasks(): ArrayDataProvider
    {
        return new ArrayDataProvider(['allModels' => ArrayHelper::toArray($this->scheduler->getTasks())]);
    }
}

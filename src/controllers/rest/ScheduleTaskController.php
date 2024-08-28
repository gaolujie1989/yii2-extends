<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling\controllers\rest;

use lujie\extend\rest\ActiveController;
use lujie\scheduling\Scheduler;
use yii\base\InvalidConfigException;
use yii\data\ArrayDataProvider;
use yii\data\DataProviderInterface;
use yii\di\Instance;

/**
 * Class ScheduleTaskController
 * @package lujie\scheduling\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ScheduleTaskController extends ActiveController
{
    /**
     * @var string|bool
     */
    public $modelClass = false;

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
     * @return array
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function actions(): array
    {
        $actions = parent::actions();
        if (empty($this->modelClass)) {
            unset($actions['index']);
        }
        return $actions;
    }

    /**
     * @return DataProviderInterface
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function actionIndex(): DataProviderInterface
    {
        return new ArrayDataProvider(['allModels' => $this->scheduler->getTasks()]);
    }

    /**
     * @param string|int $taskCode
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function actionHandle($taskCode): void
    {
        $task = $this->scheduler->getTask($taskCode);
        $this->scheduler->handle($task);
    }

    /**
     * @param string|int $taskCode
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function actionExecute($taskCode): void
    {
        $task = $this->scheduler->getTask($taskCode);
        $this->scheduler->execute($task);
    }
}

<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling\controllers\rest;

use lujie\data\loader\QueryDataLoader;
use lujie\extend\rest\ActiveController;
use lujie\scheduling\monitor\models\ScheduleTask;
use lujie\scheduling\Scheduler;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\data\DataProviderInterface;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;

/**
 * Class ScheduleTaskController
 * @package lujie\scheduling\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ScheduleTaskController extends ActiveController
{
    /**
     * @var string
     */
    public $modelClass = ScheduleTask::class;

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
     * @inheritdoc
     */
    public function actionIndex(): DataProviderInterface
    {
        $dataLoader = $this->scheduler->taskLoader;
        if ($dataLoader instanceof QueryDataLoader) {
            $query = clone $dataLoader->query;
            return new ActiveDataProvider([
                'query' => $query->andFilterWhere($dataLoader->condition),
            ]);
        }
        return new ArrayDataProvider(['allModels' => ArrayHelper::toArray($dataLoader->all())]);
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
}

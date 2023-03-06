<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\project\controllers\rest;

use lujie\batch\BatchAction;
use lujie\extend\rest\ActiveController;
use lujie\project\forms\TaskBatchForm;
use lujie\project\models\Task;

/**
 * Class ProjectController
 * @package lujie\project\controllers\backend
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TaskController extends ActiveController
{
    public $modelClass = Task::class;

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actions(): array
    {
        return array_merge(parent::actions(), [
            'batch-update' => [
                'class' => BatchAction::class,
                'modelClass' => $this->formClass,
                'checkAccess' => [$this, 'checkAccess'],
                'batchFormClass' => TaskBatchForm::class,
                'method' => 'batchUpdate'
            ]
        ]);
    }
}

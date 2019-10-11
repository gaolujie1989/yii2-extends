<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\project\forms;

use lujie\ar\relation\behaviors\RelationDeletableBehavior;
use lujie\project\models\Task;
use lujie\project\models\TaskGroup;
use lujie\project\models\TaskQuery;
use yii\db\ActiveQuery;

/**
 * Class TaskGroupForm
 * @package lujie\project\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TaskGroupForm extends TaskGroup
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['project_id'], 'integer'],
            [['name'], 'string', 'max' => 250],
            [['description'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'relationDelete' => [
                'class' => RelationDeletableBehavior::class,
                'relations' => ['tasks']
            ]
        ]);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getTasks(): TaskQuery
    {
        return $this->hasMany(TaskForm::class, ['task_group_id' => 'task_group_id']);
    }
}

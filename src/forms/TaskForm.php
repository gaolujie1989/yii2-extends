<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\project\forms;

use lujie\ar\relation\behaviors\RelationDeletableBehavior;
use lujie\ar\relation\behaviors\RelationSavableBehavior;
use lujie\project\models\Task;

/**
 * Class TaskForm
 * @package lujie\project\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TaskForm extends Task
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['project_id', 'task_group_id', 'parent_task_id',
                'priority', 'status', 'owner_id', 'executor_id'], 'integer'],
            [['additional'], 'safe'],
            [['name'], 'string', 'max' => 250],
            [['description'], 'string', 'max' => 1000],
            [['due_time', 'started_time', 'finished_time'], 'date']
        ];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'relationSave' => [
                'class' => RelationSavableBehavior::class,
                'relations' => ['attachments']
            ],
            'relationDelete' => [
                'class' => RelationDeletableBehavior::class,
                'relations' => ['attachments']
            ]
        ]);
    }
}

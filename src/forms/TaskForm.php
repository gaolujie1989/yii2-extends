<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\project\forms;

use lujie\alias\behaviors\TimestampAliasBehavior;
use lujie\ar\relation\behaviors\RelationDeletableBehavior;
use lujie\ar\relation\behaviors\RelationSavableBehavior;
use lujie\extend\db\FormTrait;
use lujie\project\models\Task;
use yii2tech\ar\position\PositionBehavior;

/**
 * Class TaskForm
 * @package lujie\project\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TaskForm extends Task
{
    use FormTrait;

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'relationSave' => [
                'class' => RelationSavableBehavior::class,
                'relations' => ['attachments'],
                'indexKeys' => ['attachments' => 'file'],
                'linkUnlinkRelations' => ['attachments'],
            ],
            'relationDelete' => [
                'class' => RelationDeletableBehavior::class,
                'relations' => ['attachments']
            ],
            'tsAlias' => [
                'class' => TimestampAliasBehavior::class,
                'aliasProperties' => [
                    'due_time' => 'due_at',
                    'started_time' => 'started_at',
                    'finished_time' => 'finished_at'
                ]
            ],
            'position' => [
                'class' => PositionBehavior::class,
                'groupAttributes' => ['project_id', 'task_group_id']
            ]
        ]);
    }
}

<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\project\forms;

use lujie\ar\relation\behaviors\RelationDeletableBehavior;
use lujie\ar\relation\behaviors\RelationSavableBehavior;
use lujie\extend\helpers\ModelHelper;
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
        $rules = parent::rules();
        ModelHelper::removeAttributesRules($rules, [
            'due_at', 'started_at', 'finished_at',
            'archived_at', 'archived_by', 'deleted_at', 'deleted_by',
            'additional'
        ]);
        return array_merge($rules, [
            [['due_time', 'started_time', 'finished_time'], 'date'],
            [['attachments'], 'safe'],
        ]);
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
                'relations' => ['attachments'],
                'indexKeys' => ['attachments' => 'file'],
                'linkUnlinkRelations' => ['attachments'],
            ],
            'relationDelete' => [
                'class' => RelationDeletableBehavior::class,
                'relations' => ['attachments']
            ]
        ]);
    }
}

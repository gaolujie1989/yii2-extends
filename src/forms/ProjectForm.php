<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\project\forms;

use lujie\ar\relation\behaviors\RelationDeletableBehavior;
use lujie\ar\relation\behaviors\RelationSavableBehavior;
use lujie\extend\base\FormTrait;
use lujie\extend\helpers\ModelHelper;
use lujie\project\constants\GlobalStatusConst;
use lujie\project\models\Project;
use lujie\project\models\TaskQuery;
use yii\db\ActiveQuery;

/**
 * Class ProjectForm
 * @package lujie\project\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ProjectForm extends Project
{
    use FormTrait;

    /**
     * @var array
     */
    public $groupTemplates = [
        'DEFAULT' => [
            'taskGroups' => [
                [
                    'name' => 'Default',
                ],
            ]
        ],
        'SIMPLE_PROCESS' => [
            'taskGroups' => [
                [
                    'name' => 'To Do',
                ],
                [
                    'name' => 'Doing',
                ],
                [
                    'name' => 'Done',
                ],
            ],
        ]
    ];

    /**
     * @var string
     */
    public $template;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        $rules = ModelHelper::formRules($this, parent::rules());
        ModelHelper::removeAttributesRules($rules, ['taskGroups']);
        return array_merge($rules, [
            [['visibility'], 'in', 'range' => GlobalStatusConst::STATUS_LIST],
            [['template'], 'in', 'range' => array_keys($this->groupTemplates), 'when' => static function ($model) {
                /** @var self $model */
                return $model->getIsNewRecord();
            }],
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
                'relations' => ['taskGroups']
            ],
            'relationDelete' => [
                'class' => RelationDeletableBehavior::class,
                'relations' => ['taskGroups', 'tasks']
            ]
        ]);
    }

    /**
     * @param bool $insert
     * @return bool
     * @inheritdoc
     */
    public function beforeSave($insert): bool
    {
        if ($insert && $this->template && isset($this->groupTemplates[$this->template])) {
            $groupTemplate = $this->groupTemplates[$this->template];
            $this->taskGroups = $groupTemplate['taskGroups'];
        }
        return parent::beforeSave($insert);
    }

    /**
     * @return ActiveQuery|TaskQuery
     * @inheritdoc
     */
    public function getTasks(): ActiveQuery
    {
        return $this->hasMany(TaskForm::class, ['project_id' => 'project_id']);
    }
}

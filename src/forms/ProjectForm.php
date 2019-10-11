<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\project\forms;

use lujie\ar\relation\behaviors\RelationDeletableBehavior;
use lujie\ar\relation\behaviors\RelationSavableBehavior;
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
        return [
            [['owner_id'], 'integer'],
            [['options'], 'safe'],
            [['name'], 'string', 'max' => 250],
            [['description'], 'string', 'max' => 1000],
            [['visibility'], 'in', 'range' => GlobalStatusConst::STATUS_LIST],
            [['template'], 'in', 'range' => array_keys($this->groupTemplates), 'when' => static function ($model) {
                /** @var self $model */
                return $model->getIsNewRecord();
            }],
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
        if ($insert && $this->template) {
            $groupTemplate = $this->groupTemplates[$this->template];
            $this->taskGroups = $groupTemplate['taskGroups'];
        }
        return parent::beforeSave($insert);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getTasks(): TaskQuery
    {
        return $this->hasMany(TaskForm::class, ['project_id' => 'project_id']);
    }
}

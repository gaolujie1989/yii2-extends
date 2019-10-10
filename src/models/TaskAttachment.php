<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\project\models;


use lujie\alias\behaviors\AliasPropertyBehavior;
use lujie\upload\models\UploadSavedFile;
use yii\db\ActiveQuery;

/**
 * Class TaskAttachment
 *
 * @property int $task_id
 * @property int $project_id
 *
 * @property Project $project
 * @property Task $task
 *
 * @package lujie\project\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TaskAttachment extends UploadSavedFile
{
    public const MODEL_TYPE_TASK_ATTACHMENT = 'TASK_ATTACHMENT';

    public const MODEL_TYPE = self::MODEL_TYPE_TASK_ATTACHMENT;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['task_id', 'project_id'], 'integer']
        ]);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'alias' => [
                'class' => AliasPropertyBehavior::class,
                'aliasProperties' => [
                    'task_id' => 'model_id',
                    'project_id' => 'model_parent_id',
                ]
            ]
        ]);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getProject(): ProjectQuery
    {
        return $this->hasOne(Project::class, ['project_id' => 'model_parent_id']);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getTask(): TaskQuery
    {
        return $this->hasOne(Task::class, ['task_id' => 'model_id']);
    }
}

<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\project\models;


use lujie\alias\behaviors\AliasPropertyBehavior;
use lujie\upload\models\UploadModelFile;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\Connection;

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
class TaskAttachment extends UploadModelFile
{
    public const MODEL_TYPE_TASK_ATTACHMENT = 'TASK_ATTACHMENT';

    public const MODEL_TYPE = self::MODEL_TYPE_TASK_ATTACHMENT;

    /**
     * @return Connection|object
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public static function getDb(): Connection
    {
        $app = Yii::$app;
        $db = Yii::$app->params['projectDB'] ?? null;
        return $db ? $app->get($db) : parent::getDb();
    }

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
     * @return ActiveQuery|ProjectQuery
     * @inheritdoc
     */
    public function getProject(): ActiveQuery
    {
        return $this->hasOne(Project::class, ['project_id' => 'model_parent_id']);
    }

    /**
     * @return ActiveQuery|TaskQuery
     * @inheritdoc
     */
    public function getTask(): ActiveQuery
    {
        return $this->hasOne(Task::class, ['task_id' => 'model_id']);
    }
}

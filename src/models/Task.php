<?php

namespace lujie\project\models;

use lujie\alias\behaviors\TimestampAliasBehavior;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use lujie\upload\models\UploadSavedFileQuery;
use Yii;
use yii\db\ActiveQuery;
use yii2tech\ar\position\PositionBehavior;

/**
 * This is the model class for table "{{%task}}".
 *
 * @property string $task_id
 * @property string $project_id
 * @property string $task_group_id
 * @property string $parent_task_id
 * @property int $position
 * @property string $name
 * @property string $description
 * @property array $additional
 * @property int $priority
 * @property int $status
 * @property string $owner_id
 * @property string $executor_id
 * @property int $due_at
 * @property int $started_at
 * @property int $finished_at
 * @property int $archived_at
 * @property int $deleted_at
 *
 * @property string $due_time
 * @property string $started_time
 * @property string $finished_time
 *
 * @property Project $project
 * @property TaskGroup $taskGroup
 * @property TaskAttachment[] $attachments
 */
class Task extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%task}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['project_id', 'task_group_id', 'parent_task_id',
                'position', 'priority', 'status', 'owner_id', 'executor_id',
                'due_at', 'started_at', 'finished_at', 'archived_at', 'deleted_at'], 'integer'],
            [['additional'], 'safe'],
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
        return array_merge(parent::behaviors(), $this->traceableBehaviors(), [
            'dateAlias' => [
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

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'task_id' => Yii::t('lujie/project', 'Task ID'),
            'project_id' => Yii::t('lujie/project', 'Project ID'),
            'task_group_id' => Yii::t('lujie/project', 'Task Group ID'),
            'parent_task_id' => Yii::t('lujie/project', 'Parent Task ID'),
            'position' => Yii::t('lujie/project', 'Position'),
            'name' => Yii::t('lujie/project', 'Name'),
            'description' => Yii::t('lujie/project', 'Description'),
            'additional' => Yii::t('lujie/project', 'Additional'),
            'priority' => Yii::t('lujie/project', 'Priority'),
            'status' => Yii::t('lujie/project', 'Status'),
            'owner_id' => Yii::t('lujie/project', 'Owner ID'),
            'executor_id' => Yii::t('lujie/project', 'Executor ID'),
            'due_at' => Yii::t('lujie/project', 'Due At'),
            'started_at' => Yii::t('lujie/project', 'Started At'),
            'finished_at' => Yii::t('lujie/project', 'Finished At'),
            'archived_at' => Yii::t('lujie/project', 'Archived At'),
            'deleted_at' => Yii::t('lujie/project', 'Deleted At'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return TaskQuery the active query used by this AR class.
     */
    public static function find(): TaskQuery
    {
        return new TaskQuery(static::class);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        return array_merge(parent::fields(), [
            'id' => 'id',
            'due_time' => 'due_time',
            'started_time' => 'started_time',
            'finished_time' => 'finished_time',
        ]);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function extraFields(): array
    {
        return array_merge(parent::extraFields(), [
            'project' => 'project',
            'taskGroup' => 'taskGroup',
            'attachments' => 'attachments',
            'subTasks' => 'subTasks',
        ]);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getProject(): ProjectQuery
    {
        return $this->hasOne(Project::class, ['project_id' => 'project_id']);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getTaskGroup(): TaskGroupQuery
    {
        return $this->hasOne(TaskGroup::class, ['task_group_id' => 'task_group_id']);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getAttachments(): UploadSavedFileQuery
    {
        return $this->hasMany(TaskAttachment::class, ['model_id' => 'task_id']);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getSubTasks(): TaskQuery
    {
        return $this->hasMany(self::class, ['parent_task_id' => 'task_id']);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getParentTask(): TaskQuery
    {
        return $this->hasOne(self::class, ['task_id' => 'parent_task_id']);
    }
}

<?php

namespace lujie\project\models;

use lujie\alias\behaviors\TimestampAliasBehavior;
use lujie\upload\models\UploadModelFileQuery;
use Yii;
use yii\db\ActiveQuery;
use yii2tech\ar\position\PositionBehavior;

/**
 * This is the model class for table "{{%task}}".
 *
 * @property int $task_id
 * @property int $project_id
 * @property int $task_group_id
 * @property int $parent_task_id
 * @property int $position
 * @property string $name
 * @property string|null $description
 * @property array|null $additional
 * @property int $priority
 * @property int $status
 * @property int $owner_id
 * @property int $executor_id
 * @property int $due_at
 * @property int $started_at
 * @property int $finished_at
 * @property int $archived_at
 * @property int $archived_by
 * @property int $deleted_at
 * @property int $deleted_by
 *
 * @property string $due_time
 * @property string $started_time
 * @property string $finished_time
 *
 * @property Project $project
 * @property TaskGroup $taskGroup
 * @property TaskAttachment[] $attachments
 * @property Task[] $subTasks
 * @property Task $parentTask
 */
class Task extends \lujie\project\base\db\ActiveRecord
{
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
            [['project_id', 'task_group_id', 'parent_task_id', 'position', 'priority', 'status', 'owner_id', 'executor_id',
                'due_at', 'started_at', 'finished_at', 'archived_at', 'archived_by', 'deleted_at', 'deleted_by'], 'default', 'value' => 0],
            [['name', 'description'], 'default', 'value' => ''],
            [['additional'], 'default', 'value' => []],
            [['project_id', 'task_group_id', 'parent_task_id', 'position', 'priority', 'status', 'owner_id', 'executor_id',
                'due_at', 'started_at', 'finished_at', 'archived_at', 'archived_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['description'], 'string'],
            [['additional'], 'safe'],
            [['name'], 'string', 'max' => 250],
        ];
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
            'archived_by' => Yii::t('lujie/project', 'Archived By'),
            'deleted_at' => Yii::t('lujie/project', 'Deleted At'),
            'deleted_by' => Yii::t('lujie/project', 'Deleted By'),
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
            'parentTask' => 'parentTask',
        ]);
    }

    /**
     * @return ActiveQuery|ProjectQuery
     * @inheritdoc
     */
    public function getProject(): ActiveQuery
    {
        return $this->hasOne(Project::class, ['project_id' => 'project_id']);
    }

    /**
     * @return ActiveQuery|TaskGroupQuery
     * @inheritdoc
     */
    public function getTaskGroup(): ActiveQuery
    {
        return $this->hasOne(TaskGroup::class, ['task_group_id' => 'task_group_id']);
    }

    /**
     * @return ActiveQuery|UploadModelFileQuery
     * @inheritdoc
     */
    public function getAttachments(): ActiveQuery
    {
        /** @var UploadModelFileQuery $query */
        $query = $this->hasMany(TaskAttachment::class, ['model_id' => 'task_id']);
        return $query->clearWhereAppendOnCondition();
    }

    /**
     * @return ActiveQuery|TaskQuery
     * @inheritdoc
     */
    public function getSubTasks(): ActiveQuery
    {
        return $this->hasMany(static::class, ['parent_task_id' => 'task_id']);
    }

    /**
     * @return ActiveQuery|TaskQuery
     * @inheritdoc
     */
    public function getParentTask(): ActiveQuery
    {
        return $this->hasOne(static::class, ['task_id' => 'parent_task_id']);
    }
}

<?php

namespace lujie\project\models;

use Yii;
use yii\db\ActiveQuery;
use yii2tech\ar\position\PositionBehavior;

/**
 * This is the model class for table "{{%task_group}}".
 *
 * @property int $task_group_id
 * @property string $project_id
 * @property int $position
 * @property string $name
 * @property string $description
 *
 * @property Project $project
 * @property Task[] $tasks
 */
class TaskGroup extends \lujie\project\base\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%task_group}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['project_id', 'position'], 'integer'],
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
            'position' => [
                'class' => PositionBehavior::class,
                'groupAttributes' => ['project_id']
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'task_group_id' => Yii::t('lujie/project', 'Task Group ID'),
            'project_id' => Yii::t('lujie/project', 'Project ID'),
            'position' => Yii::t('lujie/project', 'Position'),
            'name' => Yii::t('lujie/project', 'Name'),
            'description' => Yii::t('lujie/project', 'Description'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return TaskGroupQuery the active query used by this AR class.
     */
    public static function find(): TaskGroupQuery
    {
        return new TaskGroupQuery(static::class);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function extraFields(): array
    {
        return array_merge(parent::extraFields(), [
            'project' => 'project',
            'tasks' => 'tasks',
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
     * @return ActiveQuery|TaskQuery
     * @inheritdoc
     */
    public function getTasks(): ActiveQuery
    {
        return $this->hasMany(Task::class, ['task_group_id' => 'task_group_id']);
    }
}

<?php

namespace lujie\project\models;

use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%project}}".
 *
 * @property int $project_id
 * @property string $name
 * @property string $description
 * @property string $visibility
 * @property int $owner_id
 * @property int $archived_at
 * @property int $deleted_at
 * @property array $options
 *
 * @property TaskGroup[] $taskGroups
 * @property Task[] $tasks
 */
class Project extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait;

    public const VISIBILITY_PUBLIC = 'PUBLIC';
    public const VISIBILITY_PRIVATE = 'PRIVATE';
    public const VISIBILITY_SYSTEM = 'SYSTEM';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%project}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['owner_id', 'archived_at', 'deleted_at'], 'integer'],
            [['options'], 'safe'],
            [['name'], 'string', 'max' => 250],
            [['description'], 'string', 'max' => 1000],
            [['visibility'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'project_id' => Yii::t('lujie/project', 'Project ID'),
            'name' => Yii::t('lujie/project', 'Name'),
            'description' => Yii::t('lujie/project', 'Description'),
            'visibility' => Yii::t('lujie/project', 'Visibility'),
            'owner_id' => Yii::t('lujie/project', 'Owner ID'),
            'archived_at' => Yii::t('lujie/project', 'Archived At'),
            'deleted_at' => Yii::t('lujie/project', 'Deleted At'),
            'options' => Yii::t('lujie/project', 'Options'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ProjectQuery the active query used by this AR class.
     */
    public static function find(): ProjectQuery
    {
        return new ProjectQuery(static::class);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getTaskGroups(): ActiveQuery
    {
        return $this->hasMany(TaskGroup::find(), ['project_id' => 'project_id']);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getTasks(): ActiveQuery
    {
        return $this->hasMany(Task::find(), ['project_id' => 'project_id']);
    }
}

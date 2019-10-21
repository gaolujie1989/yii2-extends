<?php

namespace lujie\scheduling\monitor\models;

use lujie\alias\behaviors\JsonAliasBehavior;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;

/**
 * This is the model class for table "schedule_task".
 *
 * @property int $schedule_task_id
 * @property int $position
 * @property string $task_code
 * @property string $task_group
 * @property string $task_desc
 * @property array $executable
 * @property string $expression
 * @property string $timezone
 * @property int $should_locked
 * @property string $mutex
 * @property int $timeout
 * @property int $should_queued
 * @property string $queue
 * @property int $ttr
 * @property int $attempts
 * @property int $status
 */
class ScheduleTask extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait;

    public const STATUS_ACTIVE = 10;
    public const STATUS_INACTIVE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%schedule_task}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['position', 'should_locked', 'timeout', 'should_queued', 'ttr', 'attempts', 'status'], 'integer'],
            [['task_code'], 'required'],
            [['executable'], 'safe'],
            [['task_code', 'task_group', 'expression', 'timezone', 'mutex', 'queue'], 'string', 'max' => 50],
            [['task_desc'], 'string', 'max' => 255],
            [['task_code'], 'unique'],
        ];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'json' => [
                'class' => JsonAliasBehavior::class,
                'aliasProperties' => [
                    'executableJson' => 'executable'
                ]
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'schedule_task_id' => Yii::t('lujie/scheduling', 'Schedule Task ID'),
            'position' => Yii::t('lujie/scheduling', 'Position'),
            'task_code' => Yii::t('lujie/scheduling', 'Task Code'),
            'task_group' => Yii::t('lujie/scheduling', 'Task Group'),
            'task_desc' => Yii::t('lujie/scheduling', 'Task Desc'),
            'executable' => Yii::t('lujie/scheduling', 'Executable'),
            'expression' => Yii::t('lujie/scheduling', 'Expression'),
            'timezone' => Yii::t('lujie/scheduling', 'Timezone'),
            'should_locked' => Yii::t('lujie/scheduling', 'Should Locked'),
            'mutex' => Yii::t('lujie/scheduling', 'Mutex'),
            'timeout' => Yii::t('lujie/scheduling', 'Timeout'),
            'should_queued' => Yii::t('lujie/scheduling', 'Should Queued'),
            'queue' => Yii::t('lujie/scheduling', 'Queue'),
            'ttr' => Yii::t('lujie/scheduling', 'Ttr'),
            'attempts' => Yii::t('lujie/scheduling', 'Attempts'),
            'status' => Yii::t('lujie/scheduling', 'Status')
        ];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        return array_merge(parent::fields(), [
            'executableJson'
        ]);
    }
}

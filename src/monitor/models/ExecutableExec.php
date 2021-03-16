<?php

namespace lujie\executing\monitor\models;

use lujie\extend\db\AliasFieldTrait;
use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%executable_exec}}".
 *
 * @property int $executable_exec_id
 * @property string $executable_exec_uid
 * @property string $executable_id
 * @property string $executor
 * @property int $queued_at
 * @property int $started_at
 * @property int $finished_at
 * @property int $skipped_at
 * @property int $memory_usage
 * @property string|null $executable
 * @property string|null $error
 * @property array|null $additional
 * @property int $status
 */
class ExecutableExec extends ActiveRecord
{
    use TraceableBehaviorTrait, AliasFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%executable_exec}}';
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['executable_id'], 'required'],
            [['executable_exec_uid', 'executor', 'executable', 'error'], 'default', 'value' => ''],
            [['queued_at', 'started_at', 'finished_at', 'skipped_at', 'memory_usage', 'status'], 'default', 'value' => 0],
            [['additional'], 'default', 'value' => []],
            [['queued_at', 'started_at', 'finished_at', 'skipped_at', 'memory_usage', 'status'], 'integer'],
            [['executable', 'error'], 'string'],
            [['additional'], 'safe'],
            [['executable_exec_uid'], 'string', 'max' => 32],
            [['executable_id', 'executor'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'executable_exec_id' => Yii::t('lujie/executing', 'Executable Exec ID'),
            'executable_exec_uid' => Yii::t('lujie/executing', 'Executable Exec UID'),
            'executable_id' => Yii::t('lujie/executing', 'Executable ID'),
            'executor' => Yii::t('lujie/executing', 'Executor'),
            'queued_at' => Yii::t('lujie/executing', 'Queued At'),
            'started_at' => Yii::t('lujie/executing', 'Started At'),
            'finished_at' => Yii::t('lujie/executing', 'Finished At'),
            'skipped_at' => Yii::t('lujie/executing', 'Skipped At'),
            'memory_usage' => Yii::t('lujie/executing', 'Memory Usage'),
            'executable' => Yii::t('lujie/executing', 'Executable'),
            'error' => Yii::t('lujie/executing', 'Error'),
            'additional' => Yii::t('lujie/executing', 'Additional'),
            'status' => Yii::t('lujie/executing', 'Error'),
        ];
    }

    /**
     * @return ExecutableExecQuery
     * @inheritdoc
     */
    public static function find(): ExecutableExecQuery
    {
        return new ExecutableExecQuery(static::class);
    }
}

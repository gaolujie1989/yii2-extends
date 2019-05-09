<?php

namespace lujie\queuing\monitor\models;

use lujie\extend\db\TraceableBehaviorTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%queue_job_exec}}".
 *
 * @property int $job_exec_id
 * @property string $queue
 * @property int $job_id
 * @property int $worker_pid
 * @property int $started_at
 * @property int $finished_at
 * @property int $memory_usage
 * @property int $attempt
 * @property string $error
 * @property int $status
 */
class QueueJobExec extends ActiveRecord
{
    use TraceableBehaviorTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%queue_job_exec}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['job_id', 'worker_pid', 'started_at', 'finished_at', 'memory_usage', 'attempt', 'status'], 'integer'],
            [['error'], 'string'],
            [['queue'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'job_exec_id' => Yii::t('lujie/queuing', 'Job Exec ID'),
            'queue' => Yii::t('lujie/queuing', 'Queue'),
            'job_id' => Yii::t('lujie/queuing', 'Job ID'),
            'worker_pid' => Yii::t('lujie/queuing', 'Worker ID'),
            'started_at' => Yii::t('lujie/queuing', 'Started At'),
            'finished_at' => Yii::t('lujie/queuing', 'Finished At'),
            'memory_usage' => Yii::t('lujie/queuing', 'Memory Usage'),
            'attempt' => Yii::t('lujie/queuing', 'Attempt'),
            'error' => Yii::t('lujie/queuing', 'Error'),
            'status' => Yii::t('lujie/queuing', 'Status'),
        ];
    }
}

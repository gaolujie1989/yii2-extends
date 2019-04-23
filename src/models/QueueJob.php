<?php

namespace lujie\queuing\models;

use Yii;

/**
 * This is the model class for table "{{%queue_job}}".
 *
 * @property int $queue_job_id
 * @property string $queue
 * @property int $job_id
 * @property string $job
 * @property int $ttr
 * @property int $delay
 * @property int $pushed_at
 * @property int $last_exec_id
 * @property int $last_exec_at
 * @property int $last_exec_status
 */
class QueueJob extends \lujie\core\db\ActiveRecord
{
    public $status;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%queue_job}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['job_id', 'ttr', 'delay', 'pushed_at', 'last_exec_id', 'last_exec_at', 'last_exec_status'], 'integer'],
            [['job'], 'string'],
            [['queue'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'queue_job_id' => Yii::t('lujie/queuing', 'Queue Job ID'),
            'queue' => Yii::t('lujie/queuing', 'Queue'),
            'job_id' => Yii::t('lujie/queuing', 'Job ID'),
            'job' => Yii::t('lujie/queuing', 'Job'),
            'ttr' => Yii::t('lujie/queuing', 'Ttr'),
            'delay' => Yii::t('lujie/queuing', 'Delay'),
            'pushed_at' => Yii::t('lujie/queuing', 'Pushed At'),
            'last_exec_id' => Yii::t('lujie/queuing', 'Last Exec ID'),
            'last_exec_at' => Yii::t('lujie/queuing', 'Last Exec At'),
            'last_exec_status' => Yii::t('lujie/queuing', 'Last Exec Status'),
        ];
    }
}

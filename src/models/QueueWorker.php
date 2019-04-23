<?php

namespace lujie\queuing\monitor\models;

use Yii;

/**
 * This is the model class for table "{{%queue_worker}}".
 *
 * @property int $queue_worker_id
 * @property string $queue
 * @property int $pid
 * @property int $started_at
 * @property int $finished_at
 * @property int $pinged_at
 * @property int $success_count
 * @property int $failed_count
 */
class QueueWorker extends \lujie\core\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%queue_worker}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'started_at', 'finished_at', 'pinged_at', 'success_count', 'failed_count'], 'integer'],
            [['queue'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'queue_worker_id' => Yii::t('lujie/queuing', 'Queue Worker ID'),
            'queue' => Yii::t('lujie/queuing', 'Queue'),
            'pid' => Yii::t('lujie/queuing', 'Pid'),
            'started_at' => Yii::t('lujie/queuing', 'Started At'),
            'finished_at' => Yii::t('lujie/queuing', 'Finished At'),
            'pinged_at' => Yii::t('lujie/queuing', 'Pinged At'),
            'success_count' => Yii::t('lujie/queuing', 'Success Count'),
            'failed_count' => Yii::t('lujie/queuing', 'Failed Count'),
        ];
    }
}

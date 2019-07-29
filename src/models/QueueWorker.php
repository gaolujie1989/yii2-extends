<?php

namespace lujie\queuing\monitor\models;

use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;
use yii\db\ActiveRecord;

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
class QueueWorker extends ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait;

    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%queue_worker}}';
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['pid', 'started_at', 'finished_at', 'pinged_at', 'success_count', 'failed_count'], 'integer'],
            [['queue'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
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

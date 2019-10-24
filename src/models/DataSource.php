<?php

namespace lujie\data\recording\models;

use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%data_source}}".
 *
 * @property int $data_source_id
 * @property int $data_account_id
 * @property string $name
 * @property string $type
 * @property array $condition
 * @property array $additional
 * @property int $status
 * @property int $last_exec_at
 * @property int $last_exec_status
 * @property array $last_exec_result
 *
 * @property DataAccount $dataAccount
 */
class DataSource extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait;

    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 10;

    public const EXEC_STATUS_PENDING = 0;
    public const EXEC_STATUS_QUEUED = 1;
    public const EXEC_STATUS_RUNNING = 5;
    public const EXEC_STATUS_SUCCESS = 10;
    public const EXEC_STATUS_FAILED = 11;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%data_source}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['data_account_id', 'status', 'last_exec_at', 'last_exec_status'], 'integer'],
            [['condition', 'additional', 'last_exec_result'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['type'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'data_source_id' => Yii::t('lujie/data', 'Data Source ID'),
            'data_account_id' => Yii::t('lujie/data', 'Data Account ID'),
            'name' => Yii::t('lujie/data', 'Name'),
            'type' => Yii::t('lujie/data', 'Type'),
            'condition' => Yii::t('lujie/data', 'Condition'),
            'additional' => Yii::t('lujie/data', 'Additional'),
            'status' => Yii::t('lujie/data', 'Status'),
            'last_exec_at' => Yii::t('lujie/data', 'Last Exec At'),
            'last_exec_status' => Yii::t('lujie/data', 'Last Exec Status'),
            'last_exec_result' => Yii::t('lujie/data', 'Last Exec Result'),
        ];
    }

    /**
     * @return DataSourceQuery|ActiveQuery
     * @inheritdoc
     */
    public static function find(): DataSourceQuery
    {
        return new DataSourceQuery(static::class);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getDataAccount(): ActiveQuery
    {
        return $this->hasOne(DataAccount::class, ['data_account_id' => 'data_account_id']);
    }
}

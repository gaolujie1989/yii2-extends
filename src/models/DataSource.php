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
 *
 * @property DataAccount $dataAccount
 */
class DataSource extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait;

    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 10;

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
            [['data_account_id', 'status'], 'integer'],
            [['condition', 'additional'], 'safe'],
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

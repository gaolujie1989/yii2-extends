<?php

namespace lujie\data\center\models;

use lujie\extend\db\TraceableBehaviorTrait;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%data_source}}".
 *
 * @property int $data_source_id
 * @property int $data_account_id
 * @property string $name
 * @property string $type
 * @property array $options
 * @property array $additional_info
 * @property int $status
 *
 * @property DataAccount $dataAccount
 */
class DataSource extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait;

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
            [['options', 'additional_info'], 'safe'],
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
            'options' => Yii::t('lujie/data', 'Options'),
            'additional_info' => Yii::t('lujie/data', 'Additional Info'),
            'status' => Yii::t('lujie/data', 'Status'),
        ];
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

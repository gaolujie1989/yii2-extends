<?php

namespace lujie\data\center\models;

use lujie\extend\db\TraceableBehaviorTrait;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%data_record}}".
 *
 * @property int $data_record_id
 * @property int $data_account_id
 * @property int $data_type
 * @property int $data_id
 * @property string $data_key
 * @property int $data_parent_id
 * @property array $data_additional
 * @property int $data_created_at
 * @property int $data_updated_at
 */
class DataRecord extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%data_record}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['data_account_id', 'data_id', 'data_parent_id',
                'data_created_at', 'data_updated_at'], 'integer'],
            [['data_additional'], 'safe'],
            [['data_type'], 'string', 'max' => 50],
            [['data_key'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'data_record_id' => Yii::t('lujie/data', 'Data Record ID'),
            'data_account_id' => Yii::t('lujie/data', 'Data Account ID'),
            'data_type' => Yii::t('lujie/data', 'Data Type'),
            'data_id' => Yii::t('lujie/data', 'Data ID'),
            'data_key' => Yii::t('lujie/data', 'Data Key'),
            'data_parent_id' => Yii::t('lujie/data', 'Data Parent ID'),
            'data_additional' => Yii::t('lujie/data', 'Data Additional'),
            'data_created_at' => Yii::t('lujie/data', 'Data Created At'),
            'data_updated_at' => Yii::t('lujie/data', 'Data Updated At'),
        ];
    }

    /**
     * @return DataRecordQuery|ActiveQuery
     * @inheritdoc
     */
    public static function find()
    {
        return new DataRecordQuery(static::class);
    }
}

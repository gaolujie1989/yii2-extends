<?php

namespace lujie\data\center\models;

use lujie\extend\db\TraceableBehaviorTrait;
use Yii;

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
 * @property int $created_at
 * @property int $updated_at
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
            [['data_account_id', 'data_type', 'data_id', 'data_parent_id',
                'data_created_at', 'data_updated_at'], 'integer'],
            [['data_additional'], 'safe'],
            [['data_key'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'data_record_id' => 'Data Record ID',
            'data_account_id' => 'Data Account ID',
            'data_type' => 'Data Type',
            'data_id' => 'Data ID',
            'data_key' => 'Data Key',
            'data_parent_id' => 'Data Parent ID',
            'data_additional' => 'Data Additional',
            'data_created_at' => 'Data Created At',
            'data_updated_at' => 'Data Updated At',
        ];
    }
}

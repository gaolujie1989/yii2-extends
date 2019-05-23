<?php

namespace lujie\data\center\models;

use lujie\extend\db\TraceableBehaviorTrait;
use Yii;

/**
 * This is the model class for table "{{%data_source}}".
 *
 * @property int $data_source_id
 * @property int $data_account_id
 * @property string $name
 * @property array $options
 * @property array $additional_info
 * @property int $status
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
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'data_source_id' => 'Data Source ID',
            'data_account_id' => 'Data Account ID',
            'name' => 'Name',
            'options' => 'Options',
            'additional_info' => 'Additional Info',
            'status' => 'Status',
        ];
    }
}

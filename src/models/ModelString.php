<?php

namespace lujie\eav\models;

use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;

/**
 * This is the model class for table "{{%model_string}}".
 *
 * @property int $model_text_id
 * @property string $model_type
 * @property int $model_id
 * @property string $key
 * @property string $value
 * @property string $channel
 */
class ModelString extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%model_string}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['model_type', 'key', 'value', 'channel'], 'default', 'value' => ''],
            [['model_id'], 'default', 'value' => 0],
            [['model_id'], 'integer'],
            [['model_type', 'key', 'channel'], 'string', 'max' => 50],
            [['value'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'model_text_id' => Yii::t('lujie/eav', 'Model Text ID'),
            'model_type' => Yii::t('lujie/eav', 'Model Type'),
            'model_id' => Yii::t('lujie/eav', 'Model ID'),
            'key' => Yii::t('lujie/eav', 'Key'),
            'value' => Yii::t('lujie/eav', 'Value'),
            'channel' => Yii::t('lujie/eav', 'Channel'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ModelStringQuery the active query used by this AR class.
     */
    public static function find(): ModelStringQuery
    {
        return new ModelStringQuery(static::class);
    }
}

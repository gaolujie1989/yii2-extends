<?php

namespace lujie\eav\models;

use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;

/**
 * This is the model class for table "{{%model_int}}".
 *
 * @property int $model_text_id
 * @property string $model_type
 * @property int $model_id
 * @property string $key
 * @property int $value
 * @property string $channel
 */
class ModelInt extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%model_int}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['model_type', 'key', 'channel'], 'default', 'value' => ''],
            [['model_id', 'value'], 'default', 'value' => 0],
            [['model_id', 'value'], 'integer'],
            [['model_type', 'key', 'channel'], 'string', 'max' => 50],
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
     * @return ModelIntQuery the active query used by this AR class.
     */
    public static function find(): ModelIntQuery
    {
        return new ModelIntQuery(static::class);
    }
}

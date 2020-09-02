<?php

namespace lujie\eav\models;

use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;

/**
 * This is the model class for table "{{%model_text}}".
 *
 * @property int $model_text_id
 * @property string $model_type
 * @property int $model_id
 * @property string $key
 * @property string $value
 * @property string $channel
 */
class ModelText extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%model_text}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['model_type'], 'default', 'value' => ''],
            [['model_id'], 'default', 'value' => 0],
            [['model_id'], 'integer'],
            [['key', 'value', 'channel'], 'required'],
            [['value'], 'string'],
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
     * @return ModelTextQuery the active query used by this AR class.
     */
    public static function find(): ModelTextQuery
    {
        return new ModelTextQuery(static::class);
    }
}

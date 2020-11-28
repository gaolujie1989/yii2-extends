<?php

namespace lujie\common\modelData\models;

use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;

/**
 * This is the model class for table "{{%model_data}}".
 *
 * @property int $model_data_id
 * @property string $model_type
 * @property int $model_id
 * @property resource|null $data_text
 */
class ModelData extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

    public const MODEL_TYPE = 'DEFAULT';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%model_data}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['model_type', 'model_id'], 'default', 'value' => 0],
            [['model_id'], 'integer'],
            [['data_text'], 'string'],
            [['model_type'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'model_data_id' => Yii::t('lujie/common', 'Model Data ID'),
            'model_type' => Yii::t('lujie/common', 'Model Type'),
            'model_id' => Yii::t('lujie/common', 'Model ID'),
            'data_text' => Yii::t('lujie/common', 'Data Text'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ModelDataQuery the active query used by this AR class.
     */
    public static function find(): ModelDataQuery
    {
        return (new ModelDataQuery(static::class))->modelType(static::MODEL_TYPE);
    }
}

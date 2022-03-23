<?php

namespace lujie\common\option\models;

use Yii;

/**
 * This is the model class for table "{{%model_option}}".
 *
 * @property int $model_option_id
 * @property string $model_type
 * @property int $model_id
 * @property int $option_id
 */
class ModelOption extends \lujie\extend\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%model_option}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['model_type'], 'default', 'value' => ''],
            [['model_id', 'option_id'], 'default', 'value' => 0],
            [['model_id', 'option_id'], 'integer'],
            [['model_type'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'model_option_id' => Yii::t('lujie/common', 'Model Option ID'),
            'model_type' => Yii::t('lujie/common', 'Model Type'),
            'model_id' => Yii::t('lujie/common', 'Model ID'),
            'option_id' => Yii::t('lujie/common', 'Option ID'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ModelOptionQuery the active query used by this AR class.
     */
    public static function find(): ModelOptionQuery
    {
        return new ModelOptionQuery(static::class);
    }
}

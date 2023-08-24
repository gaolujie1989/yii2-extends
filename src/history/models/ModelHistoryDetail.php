<?php

namespace lujie\common\history\models;

use Yii;

/**
 * This is the model class for table "{{%model_history_detail}}".
 *
 * @property int $model_history_detail_id
 * @property int $model_history_id
 * @property string $changed_attribute
 * @property string|null $old_value
 * @property string|null $new_value
 *
 * @method array|ModelHistoryDetail|null findOne($condition)
 * @method array|ModelHistoryDetail[] findAll($condition)
 */
class ModelHistoryDetail extends \lujie\extend\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%model_history_detail}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['model_history_id'], 'default', 'value' => 0],
            [['changed_attribute', 'old_value', 'new_value'], 'default', 'value' => ''],
            [['model_history_id'], 'integer'],
            [['old_value', 'new_value'], 'string'],
            [['changed_attribute'], 'string', 'max' => 50],
            [['model_history_id', 'changed_attribute'], 'unique', 'targetAttribute' => ['model_history_id', 'changed_attribute']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'model_history_detail_id' => Yii::t('lujie/common', 'Model History Detail ID'),
            'model_history_id' => Yii::t('lujie/common', 'Model History ID'),
            'changed_attribute' => Yii::t('lujie/common', 'Changed Attribute'),
            'old_value' => Yii::t('lujie/common', 'Old Value'),
            'new_value' => Yii::t('lujie/common', 'New Value'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ModelHistoryDetailQuery the active query used by this AR class.
     */
    public static function find(): ModelHistoryDetailQuery
    {
        return new ModelHistoryDetailQuery(static::class);
    }
}

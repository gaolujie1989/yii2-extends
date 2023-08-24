<?php

namespace lujie\common\history\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%model_history}}".
 *
 * @property int $model_history_id
 * @property string $model_type
 * @property string $model_class
 * @property int $model_id
 * @property int $model_key
 * @property int $model_parent_id
 *
 * @property ModelHistoryDetail[] $details
 *
 * @method array|ModelHistory|null findOne($condition)
 * @method array|ModelHistory[] findAll($condition)
 */
class ModelHistory extends \lujie\extend\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%model_history}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['model_type', 'model_class'], 'default', 'value' => ''],
            [['model_id', 'model_key', 'model_parent_id'], 'default', 'value' => 0],
            [['model_id', 'model_key', 'model_parent_id'], 'integer'],
            [['model_type'], 'string', 'max' => 50],
            [['model_class'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'model_history_id' => Yii::t('lujie/common', 'Model History ID'),
            'model_type' => Yii::t('lujie/common', 'Model Type'),
            'model_class' => Yii::t('lujie/common', 'Model Class'),
            'model_id' => Yii::t('lujie/common', 'Model ID'),
            'model_key' => Yii::t('lujie/common', 'Model Key'),
            'model_parent_id' => Yii::t('lujie/common', 'Model Parent ID'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ModelHistoryQuery the active query used by this AR class.
     */
    public static function find(): ModelHistoryQuery
    {
        return new ModelHistoryQuery(static::class);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function extraFields(): array
    {
        return array_merge(parent::extraFields(), [
            'details' => 'details',
        ]);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getDetails(): ActiveQuery
    {
        return $this->hasMany(ModelHistoryDetail::class, ['model_history_id' => 'model_history_id']);
    }
}

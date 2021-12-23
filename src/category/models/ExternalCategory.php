<?php

namespace lujie\common\category\models;

use Yii;

/**
 * This is the model class for table "{{%external_category}}".
 *
 * @property int $id
 * @property string $external_type
 * @property int $category_id
 * @property int $parent_id
 * @property int $position
 * @property string $name
 * @property array|null $labels
 * @property array|null $additional
 */
class ExternalCategory extends \lujie\extend\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%external_category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['external_type', 'name'], 'default', 'value' => ''],
            [['category_id', 'parent_id', 'position'], 'default', 'value' => 0],
            [['labels', 'additional'], 'default', 'value' => []],
            [['category_id', 'parent_id', 'position'], 'integer'],
            [['labels', 'additional'], 'safe'],
            [['external_type'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 200],
            [['external_type', 'category_id'], 'unique', 'targetAttribute' => ['external_type', 'category_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('lujie/common', 'ID'),
            'external_type' => Yii::t('lujie/common', 'External Type'),
            'category_id' => Yii::t('lujie/common', 'Category ID'),
            'parent_id' => Yii::t('lujie/common', 'Parent ID'),
            'position' => Yii::t('lujie/common', 'Position'),
            'name' => Yii::t('lujie/common', 'Name'),
            'labels' => Yii::t('lujie/common', 'Labels'),
            'additional' => Yii::t('lujie/common', 'Additional'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ExternalCategoryQuery the active query used by this AR class.
     */
    public static function find(): ExternalCategoryQuery
    {
        return new ExternalCategoryQuery(static::class);
    }
}

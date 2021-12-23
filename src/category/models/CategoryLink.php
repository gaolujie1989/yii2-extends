<?php

namespace lujie\common\category\models;

use Yii;

/**
 * This is the model class for table "{{%category_link}}".
 *
 * @property int $category_link_id
 * @property int $category_id
 * @property int $external_type
 * @property int $external_category_id
 */
class CategoryLink extends \lujie\extend\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%category_link}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['category_id', 'external_type', 'external_category_id'], 'default', 'value' => 0],
            [['category_id', 'external_type', 'external_category_id'], 'integer'],
            [['category_id', 'external_type'], 'unique', 'targetAttribute' => ['category_id', 'external_type']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'category_link_id' => Yii::t('lujie/common', 'Category Link ID'),
            'category_id' => Yii::t('lujie/common', 'Category ID'),
            'external_type' => Yii::t('lujie/common', 'External Type'),
            'external_category_id' => Yii::t('lujie/common', 'External Category ID'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return CategoryLinkQuery the active query used by this AR class.
     */
    public static function find(): CategoryLinkQuery
    {
        return new CategoryLinkQuery(static::class);
    }
}

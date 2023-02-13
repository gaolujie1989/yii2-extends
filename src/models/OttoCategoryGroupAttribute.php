<?php

namespace lujie\sales\channel\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%otto_category_group_attribute}}".
 *
 * @property int $otto_category_group_attribute_id
 * @property string $category_group
 * @property string $attribute_group
 * @property string $name
 *
 * @property OttoCategoryGroup $categoryGroup
 * @property OttoAttribute $groupAttributes
 */
class OttoCategoryGroupAttribute extends \lujie\extend\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%otto_category_group_attribute}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['category_group', 'attribute_group', 'name'], 'default', 'value' => ''],
            [['created_at'], 'default', 'value' => 0],
            [['created_at'], 'integer'],
            [['category_group', 'attribute_group', 'name'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'otto_category_group_attribute_id' => Yii::t('lujie/salesChannel', 'Otto Category Group Attribute ID'),
            'category_group' => Yii::t('lujie/salesChannel', 'Category Group'),
            'attribute_group' => Yii::t('lujie/salesChannel', 'Attribute Group'),
            'name' => Yii::t('lujie/salesChannel', 'Name'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return OttoCategoryGroupAttributeQuery the active query used by this AR class.
     */
    public static function find(): OttoCategoryGroupAttributeQuery
    {
        return new OttoCategoryGroupAttributeQuery(static::class);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function extraFields(): array
    {
        return array_merge(parent::extraFields(), [
            'categoryGroup' => 'categoryGroup',
            'groupAttributes' => 'groupAttributes',
        ]);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getCategoryGroup(): ActiveQuery
    {
        return $this->hasOne(OttoCategoryGroup::class, ['category_group' => 'category_group']);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getGroupAttributes(): ActiveQuery
    {
        return $this->hasOne(OttoAttribute::class, ['attribute_group' => 'attribute_group', 'name' => 'name']);
    }
}

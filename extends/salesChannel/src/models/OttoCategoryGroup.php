<?php

namespace lujie\sales\channel\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%otto_category_group}}".
 *
 * @property int $otto_category_group_id
 * @property string $category_group
 * @property array|null $categories
 * @property string $title
 * @property array|null $title_attributes
 * @property array|null $variation_themes
 * @property int $otto_created_at
 * @property int $otto_updated_at
 *
 * @property OttoCategoryGroupAttribute[] $groupAttributeRelations
 * @property OttoAttribute[] $groupAttributes
 */
class OttoCategoryGroup extends \lujie\extend\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%otto_category_group}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['category_group', 'title'], 'default', 'value' => ''],
            [['categories', 'title_attributes', 'attributes', 'variation_themes'], 'default', 'value' => []],
            [['otto_created_at', 'otto_updated_at'], 'default', 'value' => 0],
            [['categories', 'title_attributes', 'variation_themes'], 'safe'],
            [['otto_created_at', 'otto_updated_at'], 'integer'],
            [['category_group', 'title'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'otto_category_group_id' => Yii::t('lujie/salesChannel', 'Otto Category Group ID'),
            'category_group' => Yii::t('lujie/salesChannel', 'Category Group'),
            'categories' => Yii::t('lujie/salesChannel', 'Categories'),
            'title' => Yii::t('lujie/salesChannel', 'Title'),
            'title_attributes' => Yii::t('lujie/salesChannel', 'Title Attributes'),
            'variation_themes' => Yii::t('lujie/salesChannel', 'Variation Themes'),
            'otto_created_at' => Yii::t('lujie/salesChannel', 'Otto Created At'),
            'otto_updated_at' => Yii::t('lujie/salesChannel', 'Otto Updated At'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return OttoCategoryGroupQuery the active query used by this AR class.
     */
    public static function find(): OttoCategoryGroupQuery
    {
        return new OttoCategoryGroupQuery(static::class);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function extraFields(): array
    {
        return array_merge(parent::extraFields(), [
            'groupAttributeRelations' => 'groupAttributeRelations',
            'groupAttributes' => 'groupAttributes',
        ]);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getGroupAttributeRelations(): ActiveQuery
    {
        return $this->hasMany(OttoCategoryGroupAttribute::class, ['category_group' => 'category_group']);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getGroupAttributes(): ActiveQuery
    {
        return $this->hasMany(OttoAttribute::class, ['attribute_group' => 'attribute_group', 'name' => 'name'])
            ->via('groupAttributeRelations');
    }
}

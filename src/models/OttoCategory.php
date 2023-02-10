<?php

namespace lujie\sales\channel\models;

use Yii;

/**
 * This is the model class for table "{{%otto_category}}".
 *
 * @property int $otto_category_id
 * @property string $category_group
 * @property string $name
 * @property string $title
 * @property array|null $attributes
 * @property array|null $variation_themes
 * @property int $otto_created_at
 * @property int $otto_updated_at
 */
class OttoCategory extends \lujie\extend\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%otto_category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['category_group', 'name', 'title'], 'default', 'value' => ''],
            [['attributes', 'variation_themes'], 'default', 'value' => []],
            [['otto_created_at', 'otto_updated_at'], 'default', 'value' => 0],
            [['attributes', 'variation_themes'], 'safe'],
            [['otto_created_at', 'otto_updated_at'], 'integer'],
            [['category_group', 'name', 'title'], 'string', 'max' => 200],
            [['category_group', 'name'], 'unique', 'targetAttribute' => ['category_group', 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'otto_category_id' => Yii::t('lujie/salesChannel', 'Otto Category ID'),
            'category_group' => Yii::t('lujie/salesChannel', 'Category Group'),
            'name' => Yii::t('lujie/salesChannel', 'Name'),
            'title' => Yii::t('lujie/salesChannel', 'Title'),
            'attributes' => Yii::t('lujie/salesChannel', 'Attributes'),
            'variation_themes' => Yii::t('lujie/salesChannel', 'Variation Themes'),
            'otto_created_at' => Yii::t('lujie/salesChannel', 'Otto Created At'),
            'otto_updated_at' => Yii::t('lujie/salesChannel', 'Otto Updated At'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return OttoCategoryQuery the active query used by this AR class.
     */
    public static function find(): OttoCategoryQuery
    {
        return new OttoCategoryQuery(static::class);
    }
}

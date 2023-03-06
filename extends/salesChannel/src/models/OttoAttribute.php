<?php

namespace lujie\sales\channel\models;

use Yii;

/**
 * This is the model class for table "{{%otto_attribute}}".
 *
 * @property int $otto_attribute_id
 * @property string $attribute_group
 * @property string $name
 * @property string $type
 * @property int $multi_value
 * @property string $unit
 * @property string $unit_display_name
 * @property array|null $allowed_values
 * @property array|null $feature_relevance
 * @property array|null $related_media_assets
 * @property string $relevance
 * @property string $description
 * @property array|null $example_values
 * @property array|null $recommended_values
 * @property string $reference
 */
class OttoAttribute extends \lujie\extend\db\ActiveRecord
{
    public const TYPE_FLOAT = 'FLOAT';
    public const TYPE_INTEGER = 'INTEGER';
    public const TYPE_STRING = 'STRING';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%otto_attribute}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['attribute_group', 'name', 'type', 'unit', 'unit_display_name', 'relevance', 'description', 'reference'], 'default', 'value' => ''],
            [['multi_value'], 'default', 'value' => 0],
            [['allowed_values', 'feature_relevance', 'related_media_assets', 'example_values', 'recommended_values'], 'default', 'value' => []],
            [['multi_value'], 'integer'],
            [['allowed_values', 'feature_relevance', 'related_media_assets', 'example_values', 'recommended_values'], 'safe'],
            [['attribute_group', 'name', 'reference'], 'string', 'max' => 200],
            [['type', 'relevance'], 'string', 'max' => 10],
            [['unit'], 'string', 'max' => 20],
            [['unit_display_name'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'otto_attribute_id' => Yii::t('lujie/salesChannel', 'Otto Attribute ID'),
            'attribute_group' => Yii::t('lujie/salesChannel', 'Attribute Group'),
            'name' => Yii::t('lujie/salesChannel', 'Name'),
            'type' => Yii::t('lujie/salesChannel', 'Type'),
            'multi_value' => Yii::t('lujie/salesChannel', 'Multi Value'),
            'unit' => Yii::t('lujie/salesChannel', 'Unit'),
            'unit_display_name' => Yii::t('lujie/salesChannel', 'Unit Display Name'),
            'allowed_values' => Yii::t('lujie/salesChannel', 'Allowed Values'),
            'feature_relevance' => Yii::t('lujie/salesChannel', 'Feature Relevance'),
            'related_media_assets' => Yii::t('lujie/salesChannel', 'Related Media Assets'),
            'relevance' => Yii::t('lujie/salesChannel', 'Relevance'),
            'description' => Yii::t('lujie/salesChannel', 'Description'),
            'example_values' => Yii::t('lujie/salesChannel', 'Example Values'),
            'recommended_values' => Yii::t('lujie/salesChannel', 'Recommended Values'),
            'reference' => Yii::t('lujie/salesChannel', 'Reference'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return OttoAttributeQuery the active query used by this AR class.
     */
    public static function find(): OttoAttributeQuery
    {
        return new OttoAttributeQuery(static::class);
    }
}

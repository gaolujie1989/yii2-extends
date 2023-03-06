<?php

namespace lujie\common\category\models;

use Yii;
use yii\db\ActiveQuery;

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
 *
 * @property ExternalCategory $parent
 * @property ExternalCategory[] $children
 * @property ExternalCategory[] $leaf
 * @property bool $isLeaf
 */
class ExternalCategory extends \lujie\extend\db\ActiveRecord
{
    public const EXTERNAL_TYPE = '';

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
        return (new ExternalCategoryQuery(static::class))
            ->andFilterWhere(['external_type' => static::EXTERNAL_TYPE]);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        return array_merge(parent::fields(), [
            'label' => 'label',
        ]);
    }

    /**
     * @return string
     * @inheritdoc
     */
    public function getLabel(): string
    {
        $label = $this->labels[Yii::$app->language] ?? '';
        return $label ?: $this->name;
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function extraFields(): array
    {
        return array_merge(parent::extraFields(), [
            'parent' => 'parent',
            'children' => 'children',
            'isLeaf' => 'isLeaf'
        ]);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getParent(): ActiveQuery
    {
        return $this->hasOne(static::class, ['category_id' => 'parent_id']);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getChildren(): ActiveQuery
    {
        return $this->hasMany(static::class, ['parent_id' => 'category_id']);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getLeaf(): ActiveQuery
    {
        return $this->hasOne(static::class, ['parent_id' => 'category_id'])
            ->select(['parent_id'])
            ->distinct()
            ->asArray();
    }

    /**
     * @return bool
     * @inheritdoc
     */
    public function getIsLeaf(): bool
    {
        return empty($this->leaf);
    }
}

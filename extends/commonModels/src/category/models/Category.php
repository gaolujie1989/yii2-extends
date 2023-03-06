<?php

namespace lujie\common\category\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property int $category_id
 * @property int $parent_id
 * @property int $position
 * @property string $name
 * @property array|null $labels
 * @property array|null $additional
 *
 * @property Category $parent
 * @property Category[] $children
 * @property Category[] $leaf
 * @property bool $isLeaf
 */
class Category extends \lujie\extend\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['parent_id', 'position'], 'default', 'value' => 0],
            [['name'], 'default', 'value' => ''],
            [['labels', 'additional'], 'default', 'value' => []],
            [['parent_id', 'position'], 'integer'],
            [['labels', 'additional'], 'safe'],
            [['name'], 'string', 'max' => 200],
            [['parent_id', 'name'], 'unique', 'targetAttribute' => ['parent_id', 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
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
     * @return CategoryQuery the active query used by this AR class.
     */
    public static function find(): CategoryQuery
    {
        return new CategoryQuery(static::class);
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

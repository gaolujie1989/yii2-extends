<?php

namespace lujie\common\category\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property int $category_id
 * @property string $parent_id
 * @property string $name
 * @property int $position
 * @property array|null $labels
 * @property array|null $additional
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
            [['parent_id', 'name'], 'default', 'value' => ''],
            [['position'], 'default', 'value' => 0],
            [['labels', 'additional'], 'default', 'value' => []],
            [['position'], 'integer'],
            [['labels', 'additional'], 'safe'],
            [['parent_id', 'name'], 'string', 'max' => 50],
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
            'name' => Yii::t('lujie/common', 'Name'),
            'position' => Yii::t('lujie/common', 'Position'),
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
}

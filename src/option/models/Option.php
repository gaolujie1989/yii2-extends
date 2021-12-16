<?php

namespace lujie\common\option\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%option}}".
 *
 * @property int $option_id
 * @property int $parent_id
 * @property int $position
 * @property string $value
 * @property string $name
 * @property array|null $labels
 * @property array|null $additional
 *
 * @property Option $parent
 * @property Option[] $children
 */
class Option extends \lujie\extend\db\ActiveRecord
{
    public const VALUE_TYPE_INT = 1;
    public const VALUE_TYPE_FLOAT = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%option}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['parent_id', 'position'], 'default', 'value' => 0],
            [['value', 'name'], 'default', 'value' => ''],
            [['labels', 'additional'], 'default', 'value' => []],
            [['parent_id', 'position'], 'integer'],
            [['labels', 'additional'], 'safe'],
            [['value'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 255],
            [['parent_id', 'value'], 'unique', 'targetAttribute' => ['parent_id', 'value']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'option_id' => Yii::t('lujie/option', 'Option ID'),
            'parent_id' => Yii::t('lujie/option', 'Parent ID'),
            'position' => Yii::t('lujie/option', 'Position'),
            'value' => Yii::t('lujie/option', 'value'),
            'name' => Yii::t('lujie/option', 'Name'),
            'labels' => Yii::t('lujie/option', 'Labels'),
            'additional' => Yii::t('lujie/option', 'Additional'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return OptionQuery the active query used by this AR class.
     */
    public static function find(): OptionQuery
    {
        return new OptionQuery(static::class);
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
        ]);
    }

    /**
     * @return ActiveQuery|OptionQuery
     * @inheritdoc
     */
    public function getParent(): ActiveQuery
    {
        return $this->hasOne(static::class, ['option_id' => 'parent_id']);
    }

    /**
     * @return ActiveQuery|OptionQuery
     * @inheritdoc
     */
    public function getChildren(): ActiveQuery
    {
        return $this->hasOne(static::class, ['parent_id' => 'option_id']);
    }
}

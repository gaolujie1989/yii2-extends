<?php

namespace lujie\common\option\models;

use Yii;

/**
 * This is the model class for table "{{%option}}".
 *
 * @property int $option_id
 * @property string $type
 * @property string $value
 * @property int $value_type
 * @property int $position
 * @property string $name
 * @property array|null $labels
 * @property array|null $additional
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
            [['type', 'value', 'name'], 'default', 'value' => ''],
            [['value_type', 'position'], 'default', 'value' => 0],
            [['labels', 'additional'], 'default', 'value' => []],
            [['value_type', 'position'], 'integer'],
            [['labels', 'additional'], 'safe'],
            [['type', 'value'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 255],
            [['type', 'value'], 'unique', 'targetAttribute' => ['type', 'value']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'option_id' => Yii::t('lujie/option', 'Option ID'),
            'type' => Yii::t('lujie/option', 'Type'),
            'value' => Yii::t('lujie/option', 'Value'),
            'value_type' => Yii::t('lujie/option', 'Value Type'),
            'position' => Yii::t('lujie/option', 'Position'),
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
}
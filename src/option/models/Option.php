<?php

namespace lujie\common\option\models;

use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%model_option}}".
 *
 * @property int $option_id
 * @property string $parent_id
 * @property int $position
 * @property string $key
 * @property string $name
 * @property array|null $labels
 * @property array|null $additional
 */
class Option extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%model_option}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['parent_id', 'key', 'name'], 'default', 'value' => ''],
            [['position'], 'default', 'value' => 0],
            [['labels', 'additional'], 'default', 'value' => []],
            [['position'], 'integer'],
            [['labels', 'additional'], 'safe'],
            [['parent_id', 'key'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 255],
            [['parent_id', 'key'], 'unique', 'targetAttribute' => ['parent_id', 'key']],
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
            'key' => Yii::t('lujie/option', 'Key'),
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
            'parentOption' => 'parentOption',
            'childrenOptions' => 'childrenOptions',
        ]);
    }

    /**
     * @return ActiveQuery|OptionQuery
     * @inheritdoc
     */
    public function getParentOption(): ActiveQuery
    {
        return $this->hasOne(Option::class, ['option_id' => 'parent_id']);
    }

    /**
     * @return ActiveQuery|OptionQuery
     * @inheritdoc
     */
    public function getChildrenOptions(): ActiveQuery
    {
        return $this->hasOne(Option::class, ['parent_id' => 'option_id']);
    }
}

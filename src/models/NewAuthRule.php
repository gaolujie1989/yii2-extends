<?php

namespace lujie\auth\models;

use Yii;

/**
 * This is the model class for table "{{%new_auth_rule}}".
 *
 * @property int $rule_id
 * @property string $name
 * @property resource|null $data
 *
 * @property NewAuthItem[] $items
 */
class NewAuthRule extends \lujie\extend\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%new_auth_rule}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['data'], 'string'],
            [['name'], 'string', 'max' => 64],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'rule_id' => Yii::t('lujie/auth', 'Rule ID'),
            'name' => Yii::t('lujie/auth', 'Name'),
            'data' => Yii::t('lujie/auth', 'Data'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return NewAuthRuleQuery the active query used by this AR class.
     */
    public static function find(): NewAuthRuleQuery
    {
        return new NewAuthRuleQuery(static::class);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function extraFields(): array
    {
        return array_merge(parent::extraFields(), [
            'items' => 'items',
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems(): NewAuthItemQuery
    {
        return $this->hasMany(NewAuthItem::class, ['rule_name' => 'name']);
    }
}

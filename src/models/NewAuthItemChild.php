<?php

namespace lujie\auth\models;

use Yii;

/**
 * This is the model class for table "{{%new_auth_item_child}}".
 *
 * @property int $item_child_id
 * @property string $parent
 * @property string $child
 *
 * @property NewAuthItem $childItem
 * @property NewAuthItem $parentItem
 */
class NewAuthItemChild extends \lujie\extend\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%new_auth_item_child}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['parent', 'child'], 'required'],
            [['parent', 'child'], 'string', 'max' => 64],
            [['parent', 'child'], 'unique', 'targetAttribute' => ['parent', 'child']],
            [['child'], 'exist', 'skipOnError' => true, 'targetClass' => NewAuthItem::class, 'targetAttribute' => ['child' => 'name']],
            [['parent'], 'exist', 'skipOnError' => true, 'targetClass' => NewAuthItem::class, 'targetAttribute' => ['parent' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'item_child_id' => Yii::t('lujie/auth', 'Item Child ID'),
            'parent' => Yii::t('lujie/auth', 'Parent'),
            'child' => Yii::t('lujie/auth', 'Child'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return NewAuthItemChildQuery the active query used by this AR class.
     */
    public static function find(): NewAuthItemChildQuery
    {
        return new NewAuthItemChildQuery(static::class);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function extraFields(): array
    {
        return array_merge(parent::extraFields(), [
            'childItem' => 'childItem',
            'parentItem' => 'parentItem',
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildItem(): NewAuthItemQuery
    {
        return $this->hasOne(NewAuthItem::class, ['name' => 'child']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentItem(): NewAuthItemQuery
    {
        return $this->hasOne(NewAuthItem::class, ['name' => 'parent']);
    }
}

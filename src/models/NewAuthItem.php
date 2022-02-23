<?php

namespace lujie\auth\models;

use Yii;

/**
 * This is the model class for table "{{%new_auth_item}}".
 *
 * @property int $item_id
 * @property string $name
 * @property int $type
 * @property string|null $description
 * @property string|null $rule_name
 * @property resource|null $data
 *
 * @property NewAuthRule $rule
 * @property NewAuthAssignment[] $assignments
 * @property NewAuthItemChild[] $childRelations
 * @property NewAuthItem[] $children
 * @property NewAuthItemChild[] $parentRelations
 * @property NewAuthItem[] $parents
 */
class NewAuthItem extends \lujie\extend\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%new_auth_item}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['description'], 'default', 'value' => ''],
            [['name', 'type'], 'required'],
            [['type'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [['name'], 'unique'],
            [['rule_name'], 'exist', 'skipOnError' => true, 'targetClass' => NewAuthRule::class, 'targetAttribute' => ['rule_name' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'item_id' => Yii::t('lujie/auth', 'Item ID'),
            'name' => Yii::t('lujie/auth', 'Name'),
            'type' => Yii::t('lujie/auth', 'Type'),
            'description' => Yii::t('lujie/auth', 'Description'),
            'rule_name' => Yii::t('lujie/auth', 'Rule Name'),
            'data' => Yii::t('lujie/auth', 'Data'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return NewAuthItemQuery the active query used by this AR class.
     */
    public static function find(): NewAuthItemQuery
    {
        return new NewAuthItemQuery(static::class);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function extraFields(): array
    {
        return array_merge(parent::extraFields(), [
            'rule' => 'rule',
            'assignments' => 'assignments',
            'childRelations' => 'childRelations',
            'children' => 'children',
            'parentRelations' => 'parentRelations',
            'parents' => 'parents',
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRule(): NewAuthRuleQuery
    {
        return $this->hasOne(NewAuthRule::class, ['name' => 'rule_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssignments(): NewAuthAssignmentQuery
    {
        return $this->hasMany(NewAuthAssignment::class, ['item_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildRelations(): NewAuthItemChildQuery
    {
        return $this->hasMany(NewAuthItemChild::class, ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren(): NewAuthItemQuery
    {
        return $this->hasMany(static::class, ['name' => 'child'])->via('childRelations');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentRelations(): NewAuthItemChildQuery
    {
        return $this->hasMany(NewAuthItemChild::class, ['child' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParents(): NewAuthItemQuery
    {
        return $this->hasMany(static::class, ['name' => 'parent'])->via('parentRelations');
    }
}

<?php

namespace lujie\auth\models;

use Yii;

/**
 * This is the model class for table "{{%new_auth_assignment}}".
 *
 * @property int $assignment_id
 * @property string $item_name
 * @property string $user_id
 *
 * @property NewAuthItem $item
 */
class NewAuthAssignment extends \lujie\extend\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%new_auth_assignment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['item_name', 'user_id'], 'required'],
            [['item_name', 'user_id'], 'string', 'max' => 64],
            [['item_name'], 'exist', 'skipOnError' => true, 'targetClass' => NewAuthItem::class, 'targetAttribute' => ['item_name' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'assignment_id' => Yii::t('lujie/auth', 'Assignment ID'),
            'item_name' => Yii::t('lujie/auth', 'Item Name'),
            'user_id' => Yii::t('lujie/auth', 'User ID'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return NewAuthAssignmentQuery the active query used by this AR class.
     */
    public static function find(): NewAuthAssignmentQuery
    {
        return new NewAuthAssignmentQuery(static::class);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function extraFields(): array
    {
        return array_merge(parent::extraFields(), [
            'item' => 'item',
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem(): NewAuthItemQuery
    {
        return $this->hasOne(NewAuthItem::class, ['name' => 'item_name']);
    }
}

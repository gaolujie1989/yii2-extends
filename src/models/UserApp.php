<?php

namespace lujie\user\models;

use lujie\extend\constants\StatusConst;
use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;
use yii\caching\TagDependency;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%user_app}}".
 *
 * @property int $user_app_id
 * @property int $user_id
 * @property string $name
 * @property string $key
 * @property string $secret
 * @property int $status
 *
 * @property User $user
 */
class UserApp extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%user_app}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'status'], 'integer'],
            [['name'], 'string', 'max' => 200],
            [['key', 'secret'], 'string', 'max' => 32],
            [['key'], 'unique'],
            [['user_id', 'name'], 'unique', 'targetAttribute' => ['user_id', 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'user_app_id' => Yii::t('lujie/user', 'User App ID'),
            'user_id' => Yii::t('lujie/user', 'User ID'),
            'name' => Yii::t('lujie/user', 'Name'),
            'key' => Yii::t('lujie/user', 'Key'),
            'secret' => Yii::t('lujie/user', 'Secret'),
            'status' => Yii::t('lujie/user', 'Status'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return UserAppQuery the active query used by this AR class.
     */
    public static function find(): UserAppQuery
    {
        return new UserAppQuery(static::class);
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);
        if ($this->status === StatusConst::STATUS_INACTIVE || isset($changedAttributes['secret'])) {
            TagDependency::invalidate(User::getCache(), [$this->user->getTokenCacheTag('AppLogin')]);
        }
    }

    /**
     * @inheritdoc
     */
    public function afterDelete(): void
    {
        parent::afterDelete();
        TagDependency::invalidate(User::getCache(), [$this->user->getTokenCacheTag('AppLogin')]);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function extraFields(): array
    {
        return array_merge(parent::extraFields(), [
            'user' => 'user'
        ]);
    }

    /**
     * @return ActiveQuery|UserQuery
     * @inheritdoc
     */
    public function getUser(): UserQuery
    {
        return $this->hasOne(User::class, ['user_id' => 'user_id']);
    }
}

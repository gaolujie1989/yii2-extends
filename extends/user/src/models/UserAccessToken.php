<?php

namespace lujie\user\models;

use Yii;
use yii\caching\CacheInterface;
use yii\caching\TagDependency;
use yii\di\Instance;

/**
 * This is the model class for table "user_access_token".
 *
 * @property int $user_access_token_id
 * @property int $user_id
 * @property string $access_token
 * @property string $token_type
 * @property int $expired_at
 * @property int $last_accessed_at
 */
class UserAccessToken extends \lujie\extend\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'user_access_token';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'expired_at', 'last_accessed_at'], 'default', 'value' => 0],
            [['access_token', 'token_type'], 'default', 'value' => ''],
            [['user_id', 'expired_at', 'last_accessed_at'], 'integer'],
            [['access_token'], 'string', 'max' => 64],
            [['token_type'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'user_access_token_id' => Yii::t('lujie/user', 'User Access Token ID'),
            'user_id' => Yii::t('lujie/user', 'User ID'),
            'access_token' => Yii::t('lujie/user', 'Access Token'),
            'token_type' => Yii::t('lujie/user', 'Token Type'),
            'expired_at' => Yii::t('lujie/user', 'Expired At'),
            'last_accessed_at' => Yii::t('lujie/user', 'Last Accessed At'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return UserAccessTokenQuery the active query used by this AR class.
     */
    public static function find(): UserAccessTokenQuery
    {
        return new UserAccessTokenQuery(static::class);
    }

    /**
     * @param $insert
     * @param $changedAttributes
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);
        if (!$insert) {
            (new User())->invalidateCache();
        }
    }
}

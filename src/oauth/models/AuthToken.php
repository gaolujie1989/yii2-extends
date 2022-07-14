<?php

namespace lujie\common\oauth\models;

use Yii;

/**
 * This is the model class for table "{{%auth_token}}".
 *
 * @property int $auth_token_id
 * @property int $user_id
 * @property string $auth_service
 * @property int $auth_user_id
 * @property string $auth_username
 * @property string $access_token
 * @property string $refresh_token
 * @property int $expires_at
 * @property array|null $additional
 */
class AuthToken extends \lujie\extend\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%auth_token}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'auth_user_id', 'expires_at'], 'default', 'value' => 0],
            [['auth_service', 'auth_username', 'access_token', 'refresh_token'], 'default', 'value' => ''],
            [['additional'], 'default', 'value' => []],
            [['user_id', 'auth_user_id', 'expires_at'], 'integer'],
            [['additional'], 'safe'],
            [['auth_service', 'auth_username'], 'string', 'max' => 50],
            [['access_token', 'refresh_token'], 'string', 'max' => 1000],
            [['user_id', 'auth_service'], 'unique', 'targetAttribute' => ['user_id', 'auth_service']],
            [['auth_service', 'auth_user_id', 'auth_username'], 'unique', 'targetAttribute' => ['auth_service', 'auth_user_id', 'auth_username']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'auth_token_id' => Yii::t('lujie/common', 'Auth Token ID'),
            'user_id' => Yii::t('lujie/common', 'User ID'),
            'auth_service' => Yii::t('lujie/common', 'Auth Service'),
            'auth_user_id' => Yii::t('lujie/common', 'Auth User ID'),
            'auth_username' => Yii::t('lujie/common', 'Auth Username'),
            'access_token' => Yii::t('lujie/common', 'Access Token'),
            'refresh_token' => Yii::t('lujie/common', 'Refresh Token'),
            'expires_at' => Yii::t('lujie/common', 'Expires At'),
            'additional' => Yii::t('lujie/common', 'Additional'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return AuthTokenQuery the active query used by this AR class.
     */
    public static function find(): AuthTokenQuery
    {
        return new AuthTokenQuery(static::class);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        $fields = parent::fields();
        unset($fields['access_token'], $fields['refresh_token'], $fields['additional']);
        return $fields;
    }
}

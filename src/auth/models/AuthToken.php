<?php

namespace lujie\common\oauth\models;

use Yii;

/**
 * This is the model class for table "{{%oauth_token}}".
 *
 * @property int $oauth_token_id
 * @property int $user_id
 * @property string $source
 * @property int $source_id
 * @property string $source_name
 * @property string $access_token
 * @property string $refresh_token
 * @property int $expires_at
 */
class OauthToken extends \lujie\extend\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%oauth_token}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'source_id', 'expires_at'], 'default', 'value' => 0],
            [['source', 'source_name', 'access_token', 'refresh_token'], 'default', 'value' => ''],
            [['user_id', 'source_id', 'expires_at'], 'integer'],
            [['source', 'source_name', 'access_token', 'refresh_token'], 'string', 'max' => 50],
            [['user_id', 'source'], 'unique', 'targetAttribute' => ['user_id', 'source']],
            [['source', 'source_id', 'source_name'], 'unique', 'targetAttribute' => ['source', 'source_id', 'source_name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'oauth_token_id' => Yii::t('lujie/common', 'Oauth Token ID'),
            'user_id' => Yii::t('lujie/common', 'User ID'),
            'source' => Yii::t('lujie/common', 'Source'),
            'source_id' => Yii::t('lujie/common', 'Source ID'),
            'source_name' => Yii::t('lujie/common', 'Source Name'),
            'access_token' => Yii::t('lujie/common', 'Access Token'),
            'refresh_token' => Yii::t('lujie/common', 'Refresh Token'),
            'expires_at' => Yii::t('lujie/common', 'Expires At'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return OauthTokenQuery the active query used by this AR class.
     */
    public static function find(): OauthTokenQuery
    {
        return new OauthTokenQuery(static::class);
    }
}

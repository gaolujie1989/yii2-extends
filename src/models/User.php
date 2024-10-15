<?php

namespace lujie\user\models;

use lujie\user\accessToken\AccessTokenManagerInterface;
use lujie\user\accessToken\CacheAccessTokenManager;
use Yii;
use yii\caching\CacheInterface;
use yii\caching\TagDependency;
use yii\di\Instance;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property int $user_id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $email
 * @property int $status
 */
class User extends \lujie\extend\db\ActiveRecord implements IdentityInterface
{
    public static $userCacheDuration = 86400;

    public static $userCacheTags = ['user'];

    public static $tokenTypes = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['username', 'email'], 'required'],
            [['status'], 'integer'],
            [['username', 'email'], 'string', 'max' => 200],
            [['username'], 'unique'],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'user_id' => Yii::t('lujie/user', 'User ID'),
            'username' => Yii::t('lujie/user', 'Username'),
            'auth_key' => Yii::t('lujie/user', 'Auth Key'),
            'password_hash' => Yii::t('lujie/user', 'Password Hash'),
            'email' => Yii::t('lujie/user', 'Email'),
            'status' => Yii::t('lujie/user', 'Status'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find(): UserQuery
    {
        return new UserQuery(static::class);
    }

    #region implements IdentityInterface

    /**
     * @param int|string $id
     * @return User|null
     * @inheritdoc
     */
    public static function findIdentity($id): ?self
    {
        return static::find()
            ->userId($id)
            ->active()
            ->cache(static::$userCacheDuration, new TagDependency(['tags' => static::$userCacheTags]))
            ->one();
    }

    /**
     * @param string|null $type
     * @return AccessTokenManagerInterface
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public static function getAccessTokenManager(?string $type = null): AccessTokenManagerInterface
    {
        return Yii::$app->get('accessTokenManager', false) ?: new CacheAccessTokenManager();
    }

    /**
     * @param $token
     * @param $type
     * @return self|null
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null): ?self
    {
        $tokenType = $type ? (static::$tokenTypes[$type] ?? null) : null;
        $userId = static::getAccessTokenManager($type)->getUserId($token, $tokenType);
        return static::findIdentity($userId);
    }

    /**
     * @param string|null $type
     * @param int $duration
     * @param int $length
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function createAccessToken(?string $type = null, int $duration = 86400, int $length = 64): string
    {
        return static::getAccessTokenManager($type)->createAccessToken($this->user_id, $type, $duration, $length);
    }

    /**
     * @return string
     * @inheritdoc
     */
    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return bool
     * @inheritdoc
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    #endregion

    /**
     * @param string $password
     * @return bool
     * @inheritdoc
     */
    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * @param string $password
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function setPassword(string $password): void
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function generateAuthKey(): void
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }


    /**
     * @param bool $insert
     * @return bool
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function beforeSave($insert): bool
    {
        if ($insert) {
            $this->generateAuthKey();
            if (empty($this->password_hash)) {
                $this->setPassword(Yii::$app->security->generateRandomString());
            }
        }
        return parent::beforeSave($insert);
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
        $this->invalidateCache();
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function invalidateCache(): void
    {
        $connection = static::getDb();
        if ($connection->enableQueryCache && $connection->queryCache) {
            /** @var CacheInterface $queryCache */
            $queryCache = Instance::ensure($connection->queryCache);
            TagDependency::invalidate($queryCache, static::$userCacheTags);
        }
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        $fields = parent::fields();
        $fields['id'] = 'id';
        unset($fields['auth_key'], $fields['password_hash'], $fields['password_reset_token']);
        return $fields;
    }
}

<?php

namespace lujie\user\models;

use lujie\extend\constants\StatusConst;
use Yii;
use yii\caching\CacheInterface;
use yii\caching\TagDependency;
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
    public const LOGIN_TYPE = 'Login';

    protected const CACHE_DURATION = 86400;
    protected const CACHE_USER_TAG = 'User';
    protected const CACHE_TOKEN_TAG = 'UserToken';
    protected const CACHE_USER_KEY_PREFIX = 'User:';
    protected const CACHE_USER_ID_KEY_PREFIX = 'UserID:';

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
     * @return CacheInterface
     * @inheritdoc
     */
    public static function getCache(): CacheInterface
    {
        return Yii::$app->getCache();
    }

    /**
     * @param int $id
     * @return string
     * @inheritdoc
     */
    public static function getUserCacheKey(int $id): string
    {
        return static::CACHE_USER_KEY_PREFIX . $id;
    }

    /**
     * @param string $key
     * @return string
     * @inheritdoc
     */
    public static function getUserIdCacheKey(string $key): string
    {
        return static::CACHE_USER_ID_KEY_PREFIX . $key;
    }

    /**
     * @param int $id
     * @param string|null $type
     * @return string[]
     * @inheritdoc
     */
    public static function getUserIdTokenTags(int $id, ?string $type): array
    {
        return [static::CACHE_TOKEN_TAG, static::CACHE_TOKEN_TAG . $id . ($type ?? '')];
    }

    /**
     * @param int|string $id
     * @return User|null
     * @inheritdoc
     */
    public static function findIdentity($id): ?self
    {
        $dependency = new TagDependency(['tags' => [static::CACHE_USER_TAG]]);
        $findUser = static::getCache()->getOrSet(static::getUserCacheKey($id), static function () use ($id) {
            //return false if dont want to be cached
            return static::findOne(['user_id' => $id, 'status' => StatusConst::STATUS_ACTIVE]) ?? false;
        }, static::CACHE_DURATION, $dependency);
        return $findUser === false ? null : $findUser;
    }

    /**
     * @param mixed $token
     * @param null $type
     * @return User|null
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null): ?self
    {
        $key = ($type ?? '') . $token;
        $userId = static::getCache()->get(static::getUserIdCacheKey($key))
            ?: static::getCache()->get(static::getUserIdCacheKey($token));
        if ($userId) {
            return static::findIdentity($userId);
        }
        return null;
    }

    /**
     * @param string|null $type
     * @param int $duration
     * @param int $length
     * @return string
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function getAccessToken(?string $type = null, int $duration = 86400, int $length = 32): string
    {
        $token = Yii::$app->security->generateRandomString($length);
        $key = ($type ?? '') . $token;
        $dependency = new TagDependency(['tags' => static::getUserIdTokenTags($this->getId(), $type)]);
        static::getCache()->set(static::getUserIdCacheKey($key), $this->getId(), $duration, $dependency);
        static::getCache()->set(static::getUserIdCacheKey($token), $this->getId(), $duration, $dependency);
        return $token;
    }

    /**
     * @return int|string
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
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
     * @param bool $insert
     * @param array $changedAttributes
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);
        if ($this->status === StatusConst::STATUS_INACTIVE) {
            static::getCache()->delete(static::getUserCacheKey($this->user_id));
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

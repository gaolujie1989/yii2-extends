<?php

namespace lujie\user\models;

use lujie\extend\db\TraceableBehaviorTrait;
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
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    use TraceableBehaviorTrait;

    public const STATUS_ACTIVE = 10;
    public const STATUS_INACTIVE = 0;

    protected const CACHE_DURATION = 86400;
    protected const CACHE_TAGS = ['User'];
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
     * @param $key
     * @return string
     * @inheritdoc
     */
    public static function getUserCacheKey($key): string
    {
        return static::CACHE_USER_KEY_PREFIX . $key;
    }

    /**
     * @param $key
     * @return string
     * @inheritdoc
     */
    public static function getUserIdCacheKey($key): string
    {
        return static::CACHE_USER_ID_KEY_PREFIX . $key;
    }

    /**
     * @param int|string $id
     * @return User|null
     * @inheritdoc
     */
    public static function findIdentity($id): ?self
    {
        $dependency = new TagDependency(['tags' => static::CACHE_TAGS]);
        static::getCache()->getOrSet(static::getUserCacheKey($id), static function () use ($id) {
            return static::findOne(['user_id' => $id, 'status' => static::STATUS_ACTIVE]);
        }, static::CACHE_DURATION, $dependency);
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
        $userId = static::getCache()->get(static::getUserIdCacheKey($key));
        if ($userId) {
            return static::findIdentity($userId);
        }
        return null;
    }

    /**
     * @param null $type
     * @param int $duration
     * @param int $length
     * @return string
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function getAccessToken($type = null, int $duration = 86400, int $length = 32): string
    {
        $token = Yii::$app->security->generateRandomString($length);
        $key = ($type ?? '') . $token;
        $dependency = new TagDependency(['tags' => static::CACHE_TAGS]);
        static::getCache()->set(static::getUserIdCacheKey($key), $this->getId(), $duration, $dependency);
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
     * @param $password
     * @return bool
     * @inheritdoc
     */
    public function validatePassword($password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * @param $password
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function setPassword($password): void
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
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        $fields = parent::fields();
        unset($fields['auth_key'], $fields['password_hash'], $fields['password_reset_token']);
        return $fields;
    }
}

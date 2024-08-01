<?php

namespace lujie\user\models;

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
    public const LOGIN_TYPE = 'UserLogin';
    
    public static $cacheDuration = 86400;

    public static $cacheTags = ['user'];

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
     * @param int|string $id
     * @return User|null
     * @inheritdoc
     */
    public static function findIdentity($id): ?self
    {
        return static::find()
            ->userId($id)
            ->active()
            ->cache(static::$cacheDuration, new TagDependency(['tags' => static::$cacheTags]))
            ->one();
    }

    /**
     * @param mixed $token
     * @param null $type
     * @return User|null
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null): ?self
    {
        $query = UserAccessToken::find()
            ->accessToken($token)
            ->expiredAtBetween(0, time())
            ->cache(static::$cacheDuration, new TagDependency(['tags' => static::$cacheTags]));
        if ($type) {
            $query->tokenType($type);
        }
        $userAccessToken = $query->one();
        if ($userAccessToken === null || $userAccessToken->expired_at < time()) {
            return null;
        }
        return static::findIdentity($userAccessToken->user_id);
    }

    /**
     * @param string|null $type
     * @param int $duration
     * @param int $length
     * @return string
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function createAccessToken(?string $type = null, int $duration = 86400, int $length = 64): UserAccessToken
    {
        $userAccessToken = new UserAccessToken();
        $userAccessToken->user_id = $this->user_id;
        $userAccessToken->access_token = Yii::$app->security->generateRandomString($length);
        $userAccessToken->token_type = $type ?? '';
        $userAccessToken->expired_at = time() + $duration;
        $userAccessToken->last_accessed_at = 0;
        $userAccessToken->save(false);
        return $userAccessToken;
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
            TagDependency::invalidate($queryCache, static::$cacheTags);
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

<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\remote\user;

use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\caching\CacheInterface;
use yii\caching\TagDependency;
use yii\di\Instance;
use yii\httpclient\Exception;
use yii\web\IdentityInterface;

/**
 * Class RemoteUser
 * @package lujie\remote\user
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RemoteUser extends BaseObject implements IdentityInterface
{
    public $data;

    //for static function, so defined with const
    protected const CACHE_DURATION = 86400;
    protected const CACHE_TAGS = ['RemoteUser', 'User'];
    protected const CACHE_USER_TOKEN_KEY_PREFIX = 'RemoteUserToken:';
    protected const CACHE_USER_DATA_KEY_PREFIX = 'RemoteUserData:';

    /**
     * @param string $token
     * @param string|null $type
     * @return array|null
     * @throws InvalidConfigException
     * @throws Exception
     * @inheritdoc
     */
    protected static function getUserData(string $token, string $type = null): ?array
    {
        /** @var RemoteUserClient $client */
        $client = Instance::ensure('remoteUserClient', RemoteUserClient::class);
        return $client->getUserByAccessToken($token, $type);
    }

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
    public static function getUserTokenCacheKey($key): string
    {
        return static::CACHE_USER_TOKEN_KEY_PREFIX . $key;
    }

    /**
     * @param $key
     * @return string
     * @inheritdoc
     */
    public static function getUserDataCacheKey($key): string
    {
        return static::CACHE_USER_DATA_KEY_PREFIX . $key;
    }

    /**
     * @param int|string $id
     * @return RemoteUser|null
     * @inheritdoc
     */
    public static function findIdentity($id): ?RemoteUser
    {
        $cache = static::getCache();
        $token = $cache->get(static::getUserTokenCacheKey($id));
        if ($token && $userData = $cache->get(static::getUserDataCacheKey($token))) {
            $remoteUser = new self();
            $remoteUser->data = $userData;
            return $remoteUser;
        }
        return null;
    }

    /**
     * @param mixed $token
     * @param null $type
     * @return RemoteUser|IdentityInterface
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null): ?RemoteUser
    {
        $dependency = new TagDependency(['tags' => static::CACHE_TAGS]);
        $data = static::getCache()->getOrSet(static::getUserDataCacheKey($token), static function () use ($token, $type, $dependency) {
            $userData = static::getUserData($token, $type);
            if ($userData && isset($userData['id'])) {
                static::getCache()->set(static::getUserTokenCacheKey($userData['id']), $token, static::CACHE_DURATION, $dependency);
            }
            return $userData;
        }, static::CACHE_DURATION, $dependency);

        return $data ? new self(['data' => $data]) : null;
    }

    /**
     * @return int|string|null
     * @inheritdoc
     */
    public function getId()
    {
        return $this->data['id'] ?? null;
    }

    /**
     * @return string|null
     * @inheritdoc
     */
    public function getAuthKey(): ?string
    {
        return $this->data['authKey'] ?? null;
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
}

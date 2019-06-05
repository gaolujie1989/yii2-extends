<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\remote\user;

use Yii;
use yii\base\BaseObject;
use yii\caching\CacheInterface;
use yii\caching\TagDependency;
use yii\di\Instance;
use yii\web\IdentityInterface;

/**
 * Class RemoteUser
 * @package lujie\remote\user
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RemoteUser extends BaseObject implements IdentityInterface
{
    public $data;

    protected const CACHE_DURATION = 86400;
    protected const CACHE_TAG = 'RemoteUser';
    protected const CACHE_KEY_PREFIX = 'RemoteUser:';
    protected const CACHE_KEY_TYPE_TOKEN = 'Token:';
    protected const CACHE_KEY_TYPE_USER_DATA = 'UserData:';

    /**
     * @param string $token
     * @param string|null $type
     * @return array|null
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
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
    public static function getCacheKey($key, string $type = ''): string
    {
        return static::CACHE_KEY_PREFIX . $type . $key;
    }

    /**
     * @param int|string $id
     * @return RemoteUser|null
     * @inheritdoc
     */
    public static function findIdentity($id): ?RemoteUser
    {
        $cache = static::getCache();
        $token = $cache->get(static::getCacheKey($id, static::CACHE_KEY_TYPE_TOKEN));
        if ($token && $userData = $cache->get(static::getCacheKey($token, static::CACHE_KEY_TYPE_USER_DATA))) {
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
        $data = static::getCache()->getOrSet(static::getCacheKey($token, static::CACHE_KEY_TYPE_USER_DATA), static function () use ($token, $type) {
            $userData = static::getUserData($token, $type);
            if ($userData && isset($userData['id'])) {
                static::getCache()->set(static::getCacheKey($userData['id'], static::CACHE_KEY_TYPE_TOKEN), $token);
            }
            return $userData;
        }, static::CACHE_DURATION, new TagDependency(['tags' => static::CACHE_TAG]));

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

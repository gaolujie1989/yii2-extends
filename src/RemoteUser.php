<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\remote\user;

use Yii;
use yii\caching\CacheInterface;
use yii\caching\TagDependency;
use yii\di\Instance;
use yii\web\IdentityInterface;

/**
 * Class RemoteUser
 * @package lujie\remote\user
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RemoteUser implements IdentityInterface
{
    public $data;

    const CACHE_DURATION = 86400;
    const CACHE_TAG = 'RemoteUser';
    const CACHE_KEY_PREFIX = 'RemoteUser:';

    /**
     * @param $token
     * @param null $type
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    protected static function getUserData($token, $type = null)
    {
        /** @var RemoteUserClient $client */
        $client = Instance::ensure('remoteUserClient', RemoteUserClient::class);
        $client->getUserByAccessToken($token, $type);
    }

    /**
     * @return CacheInterface
     * @inheritdoc
     */
    public static function getCache()
    {
        return Yii::$app->getCache();
    }

    /**
     * @param $key
     * @return string
     * @inheritdoc
     */
    public static function getCacheKey($key)
    {
        return static::CACHE_KEY_PREFIX . $key;
    }

        /**
     * @param int|string $id
     * @return RemoteUser|IdentityInterface|null
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        $cache = static::getCache();
        if ($token = $cache->get(static::getCacheKey($id))) {
            if ($userData = $cache->get(static::getCacheKey($token))) {
                $remoteUser = new RemoteUser();
                $remoteUser->data = $userData;
                return $remoteUser;
            }
        }
        return null;
    }

    /**
     * @param mixed $token
     * @param null $type
     * @return RemoteUser|IdentityInterface
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $remoteUser = new RemoteUser();
        $remoteUser->data = static::getCache()->getOrSet(static::getCacheKey($token), function () use ($token, $type) {
            $userData = static::getUserData($token, $type);
            static::getCache()->set(static::getCacheKey($userData['id']), $token);
            return $userData;
        }, static::CACHE_DURATION, new TagDependency(['tags' => static::CACHE_TAG]));

        return $remoteUser;
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
    public function getAuthKey()
    {
        return $this->data['authKey'] ?? null;
    }

    /**
     * @param string $authKey
     * @return bool
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
}

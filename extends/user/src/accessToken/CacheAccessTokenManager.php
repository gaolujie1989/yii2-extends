<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\user\accessToken;

use lujie\extend\caching\CachingTrait;
use Yii;
use yii\base\BaseObject;

/**
 * Class CacheAccessToken
 * @package lujie\user\accessToken
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CacheAccessTokenManager extends BaseObject implements AccessTokenManagerInterface
{
    use CachingTrait;

    /**
     * @param $token
     * @param string|null $tokenType
     * @return int|null
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function getUserId($token, ?string $tokenType = null): ?int
    {
        $cacheValue = $this->getCacheValue(__CLASS__ . $token);
        if ($cacheValue) {
            if ($tokenType !== null && $tokenType !== $cacheValue['tokenType']) {
                return null;
            }
            return $cacheValue['userId'];
        }
        return null;
    }

    /**
     * @param int $userId
     * @param string|null $tokenType
     * @param int $duration
     * @param int $length
     * @return string
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function createAccessToken(int $userId, ?string $tokenType = null, int $duration = 86400, int $length = 64): string
    {
        $token = Yii::$app->security->generateRandomString($length);
        $this->setCacheValue(__CLASS__ . $token, [
            'userId' => $userId,
            'token' => $token,
            'tokenType' => $tokenType
        ], $duration);
        return $token;
    }
}

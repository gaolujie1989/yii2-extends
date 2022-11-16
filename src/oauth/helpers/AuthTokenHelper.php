<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\oauth\helpers;

use lujie\common\oauth\models\AuthToken;
use yii\authclient\ClientInterface;
use yii\authclient\OAuthToken;
use yii\base\InvalidArgumentException;

class AuthTokenHelper
{
    /**
     * @param ClientInterface $client
     * @return AuthToken|null
     * @inheritdoc
     */
    public static function getAuthToken(ClientInterface $client): ?AuthToken
    {
        $authService = $client->getId();
        $userAttributes = $client->getUserAttributes();
        $authUserId = $userAttributes['id'] ?? 0;
        $authUsername = $userAttributes['username'] ?? '';

        $authTokenQuery = AuthToken::find()->authService($authService);
        if ($authUserId) {
            $authTokenQuery->authUserId($authUserId);
        } else if ($authUsername) {
            $authTokenQuery->authUsername($authUsername);
        } else {
            return null;
        }
        return $authTokenQuery->one();
    }

    /**
     * @param OAuthToken $token
     * @param string $expireDurationParamKey
     * @return int|null
     * @inheritdoc
     */
    public static function getRefreshTokenExpireDuration(OAuthToken $token, string $expireDurationParamKey = 'refresh_token_expires_in', int $default = 86400 * 365): ?int
    {
        return $token->getParam(static::defaultRefreshTokenExpireDurationParamKey($token, $expireDurationParamKey)) ?: $default;
    }

    /**
     * @param OAuthToken $token
     * @param string $expireDurationParamKey
     * @return string
     * @inheritdoc
     */
    protected static function defaultRefreshTokenExpireDurationParamKey(OAuthToken $token, string $expireDurationParamKey = 'refresh_token_expires_in'): string
    {
        foreach ($token->getParams() as $name => $value) {
            if (strpos($name, 'refresh_token_expir') !== false) {
                $expireDurationParamKey = (string)$name;
                break;
            }
        }
        return $expireDurationParamKey;
    }
}
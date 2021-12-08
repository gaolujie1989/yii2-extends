<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\oauth\helpers;

use lujie\common\oauth\models\AuthToken;
use yii\authclient\ClientInterface;
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
            throw new InvalidArgumentException('Invalid user attributes');
        }
        return $authTokenQuery->one();
    }
}
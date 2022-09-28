<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\authclient;

use yii\authclient\OAuthToken;
use yii\httpclient\Request;

/**
 * Trait ExtendOAuth2
 * @package lujie\extend\authclient
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait OAuth2ExtendTrait
{
    /**
     * @return array
     * @inheritdoc
     */
    protected function initUserAttributes(): array
    {
        return [];
    }

    /**
     * @param array $tokenConfig
     * @return OAuthToken
     * @inheritdoc
     */
    protected function createToken(array $tokenConfig = []): OAuthToken
    {
        $tokenConfig['tokenSecretParamKey'] = 'refresh_token';
        $authToken = parent::createToken($tokenConfig);
        //To Avoid 401
        $authToken->setExpireDuration($authToken->getExpireDuration() - 5);
        return $authToken;
    }

    /**
     * @param Request $request
     * @param OAuthToken $accessToken
     * @inheritdoc
     */
    public function applyAccessTokenToRequest($request, $accessToken): void
    {
        $request->getHeaders()->set('Authorization', 'Bearer ' . $accessToken->getToken());
    }
}
<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\authclient;

use lujie\extend\helpers\HttpClientHelper;
use yii\authclient\InvalidResponseException;
use yii\authclient\OAuthToken;
use yii\httpclient\Request;

/**
 * Trait ExtendOAuth2
 *
 * @property string $tokenParamKey = 'access_token'
 * @property string $tokenSecretParamKey = 'refresh_token'
 * @property int $tokenExpireDuration = 3600
 *
 * @package lujie\extend\authclient
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait OAuthExtendTrait
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
        $tokenConfig['tokenParamKey'] = $this->tokenParamKey ?? 'access_token';
        $tokenConfig['tokenSecretParamKey'] = $this->tokenSecretParamKey ?? 'refresh_token';
        $authToken = parent::createToken($tokenConfig);
        //To Avoid 401
        $authToken->setExpireDuration(($authToken->getExpireDuration() ?: ($this->tokenExpireDuration ?? 3600)) - 5);
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

    /**
     * @param Request $request
     * @return array|mixed
     * @throws InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    protected function sendRequest($request)
    {
        return HttpClientHelper::sendRequest($request)->getData();
    }
}
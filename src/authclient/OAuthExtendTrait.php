<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\authclient;

use lujie\extend\helpers\HttpClientHelper;
use yii\authclient\InvalidResponseException;
use yii\authclient\OAuthToken;
use yii\httpclient\Request;
use yii\httpclient\Response;
use lujie\extend\httpclient\Response as ExtendResponse;

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
     * @var ?Request
     */
    private $lastRequest;

    /**
     * @var ?Response
     */
    private $lastResponse;

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
     * @return array|OAuthToken|null
     * @throws InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function getAccessToken()
    {
        $token = parent::getAccessToken();
        //To Avoid 401, 防止手动setAccessToken是过期的
        if (is_object($token) && $this->autoRefreshAccessToken && $token->getIsExpired()) {
            $token = $this->refreshAccessToken($token);
        }
        return $token;
    }

    /**
     * @param array $token
     * @throws InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function setAccessTokenIfTokenIsValid(array $token): void
    {
        $autoRefreshAccessToken = $this->autoRefreshAccessToken;
        $this->autoRefreshAccessToken = false;
        $accessToken = $this->getAccessToken();
        if (!is_object($accessToken) || !$accessToken->getIsValid()) {
            $this->setAccessToken($token);
        }
        $this->autoRefreshAccessToken = $autoRefreshAccessToken;
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
     * @return array|Response|null
     * @throws InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    protected function sendRequest($request)
    {
        $this->lastRequest = $request;
        $this->lastResponse = HttpClientHelper::sendRequest($request);
        if ($this->lastResponse->getFormat()) {
            return $this->lastResponse->getData();
        }
        return $this->lastResponse;
    }

    /**
     * @return Request|null
     */
    public function getLastRequest(): ?Request
    {
        return $this->lastRequest;
    }

    /**
     * @return Response|ExtendResponse|null
     */
    public function getLastResponse(): Response|ExtendResponse|null
    {
        return $this->lastResponse;
    }
}

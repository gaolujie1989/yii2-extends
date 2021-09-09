<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\authclient;

use lujie\extend\BatchIteratorTrait;
use lujie\extend\helpers\HttpClientHelper;
use Yii;
use yii\authclient\BaseOAuth;
use yii\authclient\InvalidResponseException;
use yii\authclient\OAuthToken;
use yii\httpclient\Request;

/**
 * Class SimpleOAuth
 * @package lujie\extend\authclient
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SimpleOAuth extends BaseOAuth
{
    use BatchIteratorTrait, RestApiTrait;

    /**
     * @var string
     */
    public $userUrl = 'auth/info';

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $tokenParamKey = 'token';

    /**
     * @var int
     */
    public $expireDuration = 3600;

    #region BaseOAuth

    /**
     * @return string
     * @inheritdoc
     */
    public function getId(): string
    {
        return parent::getId() . '_' . $this->username;
    }

    /**
     * @return array
     * @inheritdoc
     */
    protected function initUserAttributes(): array
    {
        return $this->api($this->authUrl);
    }

    /**
     * @return OAuthToken
     * @throws InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function getAccessToken(): OAuthToken
    {
        $token = parent::getAccessToken();
        if (!is_object($token) || $token->getIsExpired()) {
            $token = $this->authenticate();
        }
        return $token;
    }

    /**
     * @param array $params
     * @return OAuthToken
     * @throws InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function authenticate(array $params = []): OAuthToken
    {
        if (empty($params)) {
            $params = [
                'username' => $this->username,
                'password' => $this->password,
            ];
        }
        if (!empty($this->scope)) {
            $params['scope'] = $this->scope;
        }

        Yii::info("Login to {$this->authUrl} with username {$this->username}", __METHOD__);
        $request = $this->createRequest()
            ->setMethod('POST')
            ->setUrl($this->authUrl)
            ->setData($params);

        $response = $this->sendRequest($request);

        $token = $this->createToken(['params' => $response]);
        $this->setAccessToken($token);

        return $token;
    }

    /**
     * Creates token from its configuration.
     * @param array $tokenConfig token configuration.
     * @return OAuthToken token instance.
     */
    protected function createToken(array $tokenConfig = []): OAuthToken
    {
        $tokenConfig['tokenParamKey'] = $this->tokenParamKey;
        $authToken = parent::createToken($tokenConfig);
        if ($this->expireDuration && $authToken->getExpireDuration() === null) {
            $authToken->setExpireDuration($this->expireDuration);
        }
        return $authToken;
    }

    /**
     * @param OAuthToken $token
     * @return OAuthToken
     * @throws InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function refreshAccessToken(OAuthToken $token): OAuthToken
    {
        return $this->authenticate();
    }

    /**
     * @param Request $request HTTP request instance.
     * @param OAuthToken $accessToken access token instance.
     * @inheritdoc
     */
    public function applyAccessTokenToRequest($request, $accessToken): void
    {
        $request->getHeaders()->set('Authorization', 'Bearer ' . $accessToken->getToken());
    }

    #endregion BaseOAuth

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
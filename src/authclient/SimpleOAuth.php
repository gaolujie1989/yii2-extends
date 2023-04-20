<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\authclient;

use Yii;
use yii\authclient\BaseOAuth;
use yii\authclient\InvalidResponseException;
use yii\authclient\OAuthToken;

/**
 * Class SimpleOAuth
 * @package lujie\extend\authclient
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SimpleOAuth extends BaseOAuth
{
    use RestApiTrait, BatchApiTrait, OAuthExtendTrait;

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

    #region BaseOAuth

    /**
     * @return string
     * @inheritdoc
     */
    public function getId(): string
    {
        return $this->getName() . '-' . $this->username;
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

    #endregion BaseOAuth
}
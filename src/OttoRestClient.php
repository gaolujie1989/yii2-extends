<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\otto;

use lujie\extend\authclient\OAuthExtendTrait;
use yii\authclient\OAuth2;
use yii\authclient\OAuthToken;

/**
 * Class OttoRestClient
 * @package lujie\otto
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OttoRestClient extends OAuth2
{
    use OAuthExtendTrait;

    protected $sandbox = false;

    public $sandboxUrlMap = ['api.otto.market' => 'sandbox.api.otto.market'];

    public $apiBaseUrl = 'https://api.otto.market/';

    public $tokenUrl = ' https://api.otto.market/v1/token';

    public $username;

    public $password;

    /**
     * @param bool $sandbox
     * @inheritdoc
     */
    public function setSandbox(bool $sandbox = true): void
    {
        $this->sandbox = $sandbox;
        $map = $this->sandbox ? $this->sandboxUrlMap : array_flip($this->sandboxUrlMap);
        $this->apiBaseUrl = strtr($this->apiBaseUrl, $map);
        $this->authUrl = strtr($this->authUrl, $map);
        $this->tokenUrl = strtr($this->tokenUrl, $map);
    }

    /**
     * @param OAuthToken $token
     * @return OAuthToken
     * @inheritdoc
     */
    public function refreshAccessToken(OAuthToken $token): OAuthToken
    {
        $refreshExpiresAt = ($token->getParam('refresh_expires_in') ?: 0) + $token->createTimestamp - 5;
        if ($refreshExpiresAt > time()) {
            return parent::refreshAccessToken($token);
        }
        return $this->authenticateUser($this->username, $this->password);
    }

    protected function applyClientCredentialsToRequest($request): void
    {
    }
}
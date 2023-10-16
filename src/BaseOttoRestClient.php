<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\otto;

use lujie\extend\authclient\RestOAuth2;
use yii\authclient\OAuthToken;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;

/**
 * Class BaseAmazonSPClient
 * @package lujie\amazon\sp
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BaseOttoRestClient extends RestOAuth2
{
    public $sandboxUrlMap = ['api.otto.market' => 'sandbox.api.otto.market'];

    public $tokenUrl = 'v1/token';

    public $clientId = 'token-otto-api';

    public $username;

    public $password;

    #region Auth token

    /**
     * @return OAuthToken
     * @inheritdoc
     */
    public function getAccessToken(): OAuthToken
    {
        $authToken = parent::getAccessToken();
        if (!is_object($authToken)) {
            $authToken = $this->authenticateUser($this->username, $this->password);
        }
        return $authToken;
    }

    /**
     * @param OAuthToken $token
     * @return OAuthToken
     * @inheritdoc
     */
    public function refreshAccessToken(OAuthToken $token): OAuthToken
    {
        $refreshExpiresAt = ($token->getParam('refresh_expires_in') ?: 0) + $token->createTimestamp - ($this->tokenExpireBefore ?? 300);
        if ($refreshExpiresAt > time()) {
            return parent::refreshAccessToken($token);
        }
        return $this->authenticateUser($this->username, $this->password);
    }

    /**
     * @param \yii\httpclient\Request $request
     * @inheritdoc
     */
    protected function applyClientCredentialsToRequest($request): void
    {
        if ($request->getUrl() === $this->tokenUrl) {
            $request->addData(['client_id' => $this->clientId]);
            $request->format = Client::FORMAT_URLENCODED;
        }
    }

    #endregion

    #region Batch

    /**
     * @param array $responseData
     * @param array $condition
     * @return array|null
     * @inheritdoc
     */
    protected function getNextPageCondition(array $responseData, array $condition): ?array
    {
        $links = ArrayHelper::map($responseData['links'] ?? [], 'rel', 'href');
        if (empty($links['next'])) {
            return null;
        }
        return $this->getNextByLink($links['next']);
    }

    /**
     * @param array $responseData
     * @param string $method
     * @return array
     * @throws \Exception
     * @inheritdoc
     */
    protected function getPageData(array $responseData, string $method): array
    {
        foreach ($responseData as $key => $items) {
            if (is_array($items) && $key !== 'links') {
                return $items;
            }
        }
        return [];
    }

    #endregion
}

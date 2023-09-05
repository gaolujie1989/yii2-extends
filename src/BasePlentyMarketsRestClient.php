<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\plentyMarkets;

use lujie\extend\authclient\RestOAuth2;
use yii\authclient\OAuthToken;
use yii\httpclient\Request;

/**
 * Class BasePlentyMarketsRestClient
 * @package lujie\plentyMarkets
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BasePlentyMarketsRestClient extends RestOAuth2
{
    public $username;

    public $password;

    #region OAuth2

    /**
     * @return OAuthToken
     * @inheritdoc
     */
    public function getAccessToken(): OAuthToken
    {
        $token = parent::getAccessToken();
        if (!is_object($token) || $token->getIsExpired()) {
            $token = $this->authenticateUser($this->username, $this->password);
        }
        return $token;
    }

    /**
     * @param OAuthToken $token
     * @return OAuthToken
     * @inheritdoc
     */
    public function refreshAccessToken(OAuthToken $token): OAuthToken
    {
        return $this->authenticateUser($this->username, $this->password);
    }

    /**
     * @param Request $request
     * @inheritdoc
     */
    protected function applyClientCredentialsToRequest($request): void
    {
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
        $pageCount = $responseData['lastPageNumber'] ?? 1;
        $condition['page'] = $condition['page'] ?? 1;
        if ($condition['page'] > $pageCount) {
            return null;
        }
        $condition['page']++;
        return $condition;
    }

    /**
     * @param array $responseData
     * @param string $method
     * @return array
     * @inheritdoc
     */
    protected function getPageData(array $responseData, string $method): array
    {
        return $responseData['entries'] ?? [];
    }

    #endregion
}

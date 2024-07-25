<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\otto;

use lujie\extend\authclient\RestOAuth2;
use yii\authclient\InvalidResponseException;
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

    public $scope = 'products orders receipts returns price-reduction shipments quantities';

    #region Auth token

    /**
     * @return OAuthToken
     * @inheritdoc
     */
    public function getAccessToken(): OAuthToken
    {
        $accessToken = parent::getAccessToken();
        if (empty($accessToken)) {
            return $this->authenticateClient();
        }
        return $accessToken;
    }

    /**
     * @param OAuthToken $token
     * @return OAuthToken
     * @throws InvalidResponseException
     * @inheritdoc
     */
    public function refreshAccessToken(OAuthToken $token): OAuthToken
    {
        return $this->authenticateClient();
    }

    /**
     * @param $request
     * @inheritdoc
     */
    protected function applyClientCredentialsToRequest($request): void
    {
        parent::applyClientCredentialsToRequest($request);
        $request->format = Client::FORMAT_URLENCODED;
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

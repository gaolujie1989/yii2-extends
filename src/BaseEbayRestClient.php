<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\ebay;

use lujie\extend\authclient\RestOAuth2;
use lujie\extend\httpclient\Response;
use yii\helpers\Inflector;
use yii\httpclient\Client;

/**
 * Class BaseEbayRestClient
 * @package lujie\ebay
 * @author Lujie Zhou <gao_lujie@live.cn>
 * @document https://developer.ebay.com/develop/apis/restful-apis/sell-apis
 */
class BaseEbayRestClient extends RestOAuth2
{
    protected $sandbox = false;

    public $sandboxUrlMap = [
        'auth.ebay.com' => 'auth.sandbox.ebay.com',
        'api.ebay.com' => 'api.sandbox.ebay.com',
    ];

    public $apiBaseUrl = 'https://api.ebay.com/';

    public $authUrl = 'https://auth.ebay.com/oauth2/authorize';

    public $tokenUrl = 'https://api.ebay.com/identity/v1/oauth2/token';

    /**
     * @var string
     */
    public $version = 'v1';

    public $scope = [
        'https://api.ebay.com/oauth/api_scope',
        'https://api.ebay.com/oauth/api_scope/sell.marketing.readonly',
        'https://api.ebay.com/oauth/api_scope/sell.marketing',
        'https://api.ebay.com/oauth/api_scope/sell.inventory.readonly',
        'https://api.ebay.com/oauth/api_scope/sell.inventory',
        'https://api.ebay.com/oauth/api_scope/sell.account.readonly',
        'https://api.ebay.com/oauth/api_scope/sell.account',
        'https://api.ebay.com/oauth/api_scope/sell.fulfillment.readonly',
        'https://api.ebay.com/oauth/api_scope/sell.fulfillment',
        'https://api.ebay.com/oauth/api_scope/sell.analytics.readonly',
        'https://api.ebay.com/oauth/api_scope/sell.finances',
        'https://api.ebay.com/oauth/api_scope/sell.payment.dispute',
        'https://api.ebay.com/oauth/api_scope/commerce.identity.readonly',
        'https://api.ebay.com/oauth/api_scope/commerce.notification.subscription',
        'https://api.ebay.com/oauth/api_scope/commerce.notification.subscription.readonly',
    ];

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->initRest();
        if (is_array($this->scope)) {
            $this->scope = implode(' ', $this->scope);
        }
    }

    /**
     * @param \yii\httpclient\Request $request
     * @inheritdoc
     */
    protected function applyClientCredentialsToRequest($request): void
    {
        $request->getHeaders()->set('Authorization', 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret));
        if ($request->getUrl() === $this->tokenUrl) {
            $request->format = Client::FORMAT_URLENCODED;
        }
    }

    #region Batch

    /**
     * @param array $responseData
     * @param array $condition
     * @return array|null
     * @inheritdoc
     */
    protected function getNextPageCondition(array $responseData, array $condition): ?array
    {
        $condition['offset'] = ($responseData['offset'] ?? 0) + ($responseData['limit'] ?? 50);
        if (($responseData['total'] ?? 0) <= $condition['offset']) {
            return null;
        }
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
        foreach ($responseData as $key => $items) {
            if (is_array($items)) {
                return $items;
            }
        }
        return [];
    }

    #endregion
}

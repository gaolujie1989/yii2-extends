<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\ebay;

use lujie\extend\authclient\BatchApiTrait;
use lujie\extend\authclient\OAuthExtendTrait;
use lujie\extend\authclient\RestApiTrait;
use lujie\extend\authclient\RestOAuth2;
use yii\authclient\OAuth2;
use yii\base\InvalidArgumentException;
use yii\httpclient\Client;

/**
 * Class EbayRestClient
 *
 * @method array listOrders($data = [])
 * @method \Generator eachOrder($condition = [], $batchSize = 100)
 * @method \Generator batchOrder($condition = [], $batchSize = 100)
 * @method array getOrder($data)
 * @method array createOrder($data)
 * @method array updateOrder($data)
 * @method array deleteOrder($data)
 *
 * @package lujie\ebay
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class EbayRestClient extends RestOAuth2
{
    protected $sandbox = false;

    public $sandboxUrlMap = [
        'auth.ebay.com' => 'auth.sandbox.ebay.com',
        'api.ebay.com' => 'api.sandbox.ebay.com',
    ];

    public $apiBaseUrl = 'https://api.ebay.com/';

    public $authUrl = 'https://auth.ebay.com/oauth2/authorize';

    public $tokenUrl = 'https://api.ebay.com/identity/v1/oauth2/token';

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
     * @var array
     */
    public $resources = [
        'Order' => 'sell/fulfillment/v1/order',
    ];

    /**
     * @var string
     */
    public $version = 'v1';

    /**
     * @var array
     */
    public $extraActions = [
    ];

    public $responseDataKeys = [
        'listOrders' => 'orders',
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

    /**
     * @param string $name
     * @param array $data
     * @return array|null
     * @throws \Exception
     * @inheritdoc
     */
    public function restApi(string $name, array $data): ?array
    {
        $responseData = parent::restApi($name, $data);
        if ($dataKey = $this->responseDataKeys[$name] ?? null) {
            $responseData['data'] = $responseData[$dataKey];
            unset($responseData[$dataKey]);
        }
        return $responseData;
    }

    /**
     * @param array $responseData
     * @param array $condition
     * @return array|null
     * @inheritdoc
     */
    protected function getNextPageCondition(array $responseData, array $condition): ?array
    {
        return $this->getNextByLink($responseData['next'] ?? null);
    }
}
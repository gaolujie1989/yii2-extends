<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\ebay;

use lujie\extend\authclient\OAuth2ExtendTrait;
use lujie\extend\authclient\RestApiTrait;
use yii\authclient\OAuth2;
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
class EbayRestClient extends OAuth2
{
    use RestApiTrait, OAuth2ExtendTrait;

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
        $data = $request->getData();
        if (is_array($data) && ($data['grant_type'] ?? null) === 'refresh_token') {
            $request->format = Client::FORMAT_URLENCODED;
        }
    }
}
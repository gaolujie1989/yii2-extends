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
 * @method \Generator eachOrders($condition = [], $batchSize = 100)
 * @method \Generator batchOrders($condition = [], $batchSize = 100)
 * @method array getOrder($data)
 * @method array shipOrder($data)
 *
 * @method array listInventoryItems($data = [])
 * @method \Generator eachInventoryItems($condition = [], $batchSize = 100)
 * @method \Generator batchInventoryItems($condition = [], $batchSize = 100)
 * @method array getInventoryItem($data)
 * @method array deleteInventoryItem($data)
 * @method array saveInventoryItem($data)
 *
 * @method array listInventoryItemGroups($data = [])
 * @method \Generator eachInventoryItemGroups($condition = [], $batchSize = 100)
 * @method \Generator batchInventoryItemGroups($condition = [], $batchSize = 100)
 * @method array getInventoryItemGroup($data)
 * @method array deleteInventoryItemGroup($data)
 * @method array saveInventoryItemGroup($data)
 *
 * @method array listInventoryLocations($data = [])
 * @method \Generator eachInventoryLocations($condition = [], $batchSize = 100)
 * @method \Generator batchInventoryLocations($condition = [], $batchSize = 100)
 * @method array getInventoryLocation($data)
 * @method array createInventoryLocation($data)
 * @method array updateInventoryLocation($data)
 * @method array deleteInventoryLocation($data)
 * @method array enableInventoryLocation($data)
 * @method array disableInventoryLocation($data)
 *
 * @method array listInventoryOffers($data = [])
 * @method \Generator eachInventoryOffers($condition = [], $batchSize = 100)
 * @method \Generator batchInventoryOffers($condition = [], $batchSize = 100)
 * @method array getInventoryOffer($data)
 * @method array createInventoryOffer($data)
 * @method array updateInventoryOffer($data)
 * @method array deleteInventoryOffer($data)
 * @method array getListingFeesInventoryOffer($data)
 * @method array withdrawInventoryOffer($data)
 * @method array withdrawByInventoryItemGroupInventoryOffer($data)
 * @method array publishInventoryOffer($data)
 * @method array publishByInventoryItemGroupInventoryOffer($data)
 * @method array bulkSaveInventoryItem($data)
 * @method array bulkUpdatePriceQuantity($data)
 * @method array bulkMigrateListing($data)
 * @method array bulkCreateOffer($data)
 * @method array bulkPublishOffer($data)
 *
 * @package lujie\ebay
 * @author Lujie Zhou <gao_lujie@live.cn>
 * @document https://developer.ebay.com/develop/apis/restful-apis/sell-apis
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
        'InventoryItem' => 'sell/inventory/v1/inventory_item',
        'InventoryItemGroup' => 'sell/inventory/v1/inventory_item_group',
        'InventoryLocation' => 'sell/inventory/v1/location',
        'InventoryOffer' => 'sell/inventory/v1/offer',
    ];

    /**
     * @var string
     */
    public $version = 'v1';

    /**
     * @var array
     */
    public $extraActions = [
        'Order' => [
            'create' => false,
            'update' => false,
            'delete' => false,
            'ship' => ['POST', '{orderId}/shipping_fulfillment'],
        ],
        'InventoryItem' => [
            'create' => false,
            'update' => false,
            'get' => ['GET', '{sku}'],
            'save' => ['PUT', '{sku}'],
            'delete' => ['DELETE', '{sku}'],
        ],
        'InventoryItemGroup' => [
            'create' => false,
            'update' => false,
            'get' => ['GET', '{inventoryItemGroupKey}'],
            'save' => ['PUT', '{inventoryItemGroupKey}'],
            'delete' => ['DELETE', '{inventoryItemGroupKey}'],
        ],
        'InventoryLocation' => [
            'get' => ['GET', '{merchantLocationKey}'],
            'create' => ['POST', '{merchantLocationKey}'],
            'update' => ['PUT', '{merchantLocationKey}/update_location_details'],
            'enable' => ['POST', '{merchantLocationKey}/enable'],
            'disable' => ['POST ', '{merchantLocationKey}/disable'],
            'delete' => ['DELETE', '{merchantLocationKey}'],
        ],
        'InventoryOffer' => [
            'get' => ['GET', '{offerId}'],
            'getListingFees' => ['GET', 'get_listing_fees'],
            'update' => ['PUT', '{offerId}'],
            'delete' => ['DELETE', '{offerId}'],
            'withdraw' => ['POST', '{offerId}/withdraw'],
            'withdrawByInventoryItemGroup' => ['POST', 'withdraw_by_inventory_item_group'],
            'publish' => ['POST', '{offerId}/publish'],
            'publishByInventoryItemGroup' => ['POST', 'publish_by_inventory_item_group'],
        ]
    ];

    public $extraMethods = [
        'bulkSaveInventoryItem' => ['POST', 'sell/inventory/v1/bulk_create_or_replace_inventory_item'],
        'bulkUpdatePriceQuantity' => ['POST', 'sell/inventory/v1/bulk_update_price_quantity'],
        'bulkMigrateListing' => ['POST', 'sell/inventory/v1/bulk_migrate_listing'],
        'bulkCreateOffer' => ['POST', 'sell/inventory/v1/bulk_create_offer'],
        'bulkPublishOffer' => ['POST', 'sell/inventory/v1/bulk_publish_offer'],
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

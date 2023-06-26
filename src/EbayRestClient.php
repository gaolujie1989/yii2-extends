<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\ebay;

use lujie\extend\authclient\RestOAuth2;
use lujie\extend\httpclient\Response;
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
 * @method array createOrReplaceInventoryItem($data)
 *
 * @method array listInventoryItemGroups($data = [])
 * @method \Generator eachInventoryItemGroups($condition = [], $batchSize = 100)
 * @method \Generator batchInventoryItemGroups($condition = [], $batchSize = 100)
 * @method array getInventoryItemGroup($data)
 * @method array deleteInventoryItemGroup($data)
 * @method array createOrReplaceInventoryItemGroup($data)
 *
 * @method array listLocations($data = [])
 * @method \Generator eachLocations($condition = [], $batchSize = 100)
 * @method \Generator batchLocations($condition = [], $batchSize = 100)
 * @method array getLocation($data)
 * @method array createLocation($data)
 * @method array updateLocation($data)
 * @method array deleteLocation($data)
 * @method array enableLocation($data)
 * @method array disableLocation($data)
 *
 * @method array listOffers($data = [])
 * @method \Generator eachOffers($condition = [], $batchSize = 100)
 * @method \Generator batchOffers($condition = [], $batchSize = 100)
 * @method array getOffer($data)
 * @method array createOffer($data)
 * @method array updateOffer($data)
 * @method array deleteOffer($data)
 * @method array getListingFeesOffer($data)
 * @method array withdrawOffer($data)
 * @method array withdrawByInventoryItemGroupOffer($data)
 * @method array publishOffer($data)
 * @method array publishByInventoryItemGroupOffer($data)
 *
 * @method array listCustomPolicies($data = [])
 * @method \Generator eachCustomPolicies($condition = [], $batchSize = 100)
 * @method \Generator batchCustomPolicies($condition = [], $batchSize = 100)
 * @method array getCustomPolicy($data)
 * @method array createCustomPolicy($data)
 * @method array updateCustomPolicy($data)
 * @method array deleteCustomPolicy($data)
 *
 * @method array listFulfillmentPolicies($data = [])
 * @method \Generator eachFulfillmentPolicies($condition = [], $batchSize = 100)
 * @method \Generator batchFulfillmentPolicies($condition = [], $batchSize = 100)
 * @method array getFulfillmentPolicy($data)
 * @method array createFulfillmentPolicy($data)
 * @method array updateFulfillmentPolicy($data)
 * @method array deleteFulfillmentPolicy($data)
 *
 * @method array listPaymentPolicies($data = [])
 * @method \Generator eachPaymentPolicies($condition = [], $batchSize = 100)
 * @method \Generator batchPaymentPolicies($condition = [], $batchSize = 100)
 * @method array getPaymentPolicy($data)
 * @method array createPaymentPolicy($data)
 * @method array updatePaymentPolicy($data)
 * @method array deletePaymentPolicy($data)
 *
 * @method array listReturnPolicies($data = [])
 * @method \Generator eachReturnPolicies($condition = [], $batchSize = 100)
 * @method \Generator batchReturnPolicies($condition = [], $batchSize = 100)
 * @method array getReturnPolicy($data)
 * @method array createReturnPolicy($data)
 * @method array updateReturnPolicy($data)
 * @method array deleteReturnPolicy($data)
 * @method array bulkCreateOrReplaceInventoryItem($data)
 * @method array bulkUpdatePriceQuantity($data)
 * @method array bulkMigrateListing($data)
 * @method array bulkCreateOffer($data)
 * @method array bulkPublishOffer($data)
 * @method array getCategoryTree($data)
 * @method array getCategorySubTree($data)
 * @method array getCategoryTreeItemAspects($data)
 * @method array getCategoryItemAspects($data)
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

    /**
     * @var string
     */
    public $version = 'v1';

    public $httpClientOptions = [
        'requestConfig' => [
            'headers' => [
                'Accept-Encoding' => 'gzip, deflate',
                'Content-Language' => 'en-US',
            ],
            'format' => 'json',
        ],
        'responseConfig' => [
            'class' => Response::class,
            'format' => 'json'
        ],
    ];

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
        'Location' => 'sell/inventory/v1/location',
        'Offer' => 'sell/inventory/v1/offer',
        'CustomPolicy' => 'sell/account/v1/custom_policy',
        'FulfillmentPolicy' => 'sell/account/v1/fulfillment_policy',
        'PaymentPolicy' => 'sell/account/v1/payment_policy',
        'ReturnPolicy' => 'sell/account/v1/return_policy',
    ];

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
            'createOrReplace' => ['PUT', '{sku}'],
            'delete' => ['DELETE', '{sku}'],
        ],
        'InventoryItemGroup' => [
            'create' => false,
            'update' => false,
            'get' => ['GET', '{inventoryItemGroupKey}'],
            'createOrReplace' => ['PUT', '{inventoryItemGroupKey}'],
            'delete' => ['DELETE', '{inventoryItemGroupKey}'],
        ],
        'Location' => [
            'get' => ['GET', '{merchantLocationKey}'],
            'create' => ['POST', '{merchantLocationKey}'],
            'update' => ['PUT', '{merchantLocationKey}/update_location_details'],
            'enable' => ['POST', '{merchantLocationKey}/enable'],
            'disable' => ['POST ', '{merchantLocationKey}/disable'],
            'delete' => ['DELETE', '{merchantLocationKey}'],
        ],
        'Offer' => [
            'get' => ['GET', '{offerId}'],
            'getListingFees' => ['GET', 'get_listing_fees'],
            'update' => ['PUT', '{offerId}'],
            'delete' => ['DELETE', '{offerId}'],
            'withdraw' => ['POST', '{offerId}/withdraw'],
            'withdrawByInventoryItemGroup' => ['POST', 'withdraw_by_inventory_item_group'],
            'publish' => ['POST', '{offerId}/publish'],
            'publishByInventoryItemGroup' => ['POST', 'publish_by_inventory_item_group'],
        ],
    ];

    public $extraMethods = [
        'bulkCreateOrReplaceInventoryItem' => ['POST', 'sell/inventory/v1/bulk_create_or_replace_inventory_item'],
        'bulkUpdatePriceQuantity' => ['POST', 'sell/inventory/v1/bulk_update_price_quantity'],
        'bulkMigrateListing' => ['POST', 'sell/inventory/v1/bulk_migrate_listing'],
        'bulkCreateOffer' => ['POST', 'sell/inventory/v1/bulk_create_offer'],
        'bulkPublishOffer' => ['POST', 'sell/inventory/v1/bulk_publish_offer'],
        'getCategoryTree' => ['GET', 'commerce/taxonomy/v1/category_tree/{category_tree_id}'],
        'getCategorySubTree' => ['GET', 'commerce/taxonomy/v1/category_tree/{category_tree_id}/get_category_subtree'],
        'getCategoryTreeItemAspects' => ['GET', '/commerce/taxonomy/v1/category_tree/{category_tree_id}/fetch_item_aspects'],
        'getCategoryItemAspects' => ['GET', '/commerce/taxonomy/v1/category_tree/{category_tree_id}/get_item_aspects_for_category'],
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
}

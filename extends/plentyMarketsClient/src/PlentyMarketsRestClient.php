<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\plentyMarkets;

use Generator;
use Iterator;
use lujie\extend\authclient\RestClientTrait;
use lujie\extend\httpclient\RateLimitCheckerBehavior;
use yii\authclient\InvalidResponseException;
use yii\authclient\OAuth2;
use yii\authclient\OAuthToken;
use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\httpclient\CurlTransport;
use yii\httpclient\Request;

/**
 * Class PlentyMarketsRestClient
 *
 * @method array listSalesPrices($data = [])
 * @method Generator eachSalesPrices($condition = [], $batchSize = 100)
 * @method Generator batchSalesPrices($condition = [], $batchSize = 100)
 * @method array getSalesPrice($data)
 * @method array createSalesPrice($data)
 * @method array updateSalesPrice($data)
 * @method array deleteSalesPrice($data)
 *
 * @method array listAttributes($data = [])
 * @method Generator eachAttributes($condition = [], $batchSize = 100)
 * @method Generator batchAttributes($condition = [], $batchSize = 100)
 * @method array getAttribute($data)
 * @method array createAttribute($data)
 * @method array updateAttribute($data)
 * @method array deleteAttribute($data)
 *
 * @method array listAttributeNames($data)
 * @method Generator eachAttributeNames($condition = [], $batchSize = 100)
 * @method Generator batchAttributeNames($condition = [], $batchSize = 100)
 * @method array getAttributeName($data)
 * @method array createAttributeName($data)
 * @method array updateAttributeName($data)
 * @method array deleteAttributeName($data)
 *
 * @method array listAttributeValues($data)
 * @method Generator eachAttributeValues($condition = [], $batchSize = 100)
 * @method Generator batchAttributeValues($condition = [], $batchSize = 100)
 * @method array getAttributeValue($data)
 * @method array createAttributeValue($data)
 * @method array updateAttributeValue($data)
 * @method array deleteAttributeValue($data)
 *
 * @method array listAttributeValueNames($data)
 * @method Generator eachAttributeValueNames($condition = [], $batchSize = 100)
 * @method Generator batchAttributeValueNames($condition = [], $batchSize = 100)
 * @method array getAttributeValueName($data)
 * @method array createAttributeValueName($data)
 * @method array updateAttributeValueName($data)
 * @method array deleteAttributeValueName($data)
 *
 * @method array listItems($data = [])
 * @method Generator eachItems($condition = [], $batchSize = 100)
 * @method Generator batchItems($condition = [], $batchSize = 100)
 * @method array getItem($data)
 * @method array createItem($data)
 * @method array updateItem($data)
 * @method array deleteItem($data)
 *
 * @method array listItemImages($data)
 * @method Generator eachItemImages($condition = [], $batchSize = 100)
 * @method Generator batchItemImages($condition = [], $batchSize = 100)
 * @method array getItemImage($data)
 * @method array createItemImage($data)
 * @method array updateItemImage($data)
 * @method array deleteItemImage($data)
 *
 * @method array listItemImageNames($data)
 * @method Generator eachItemImageNames($condition = [], $batchSize = 100)
 * @method Generator batchItemImageNames($condition = [], $batchSize = 100)
 * @method array getItemImageName($data)
 * @method array createItemImageName($data)
 * @method array updateItemImageName($data)
 * @method array deleteItemImageName($data)
 *
 * @method array listItemImageAvailabilities($data)
 * @method Generator eachItemImageAvailabilities($condition = [], $batchSize = 100)
 * @method Generator batchItemImageAvailabilities($condition = [], $batchSize = 100)
 * @method array getItemImageAvailability($data)
 * @method array createItemImageAvailability($data)
 * @method array updateItemImageAvailability($data)
 * @method array deleteItemImageAvailability($data)
 *
 * @method array listItemTexts($data)
 * @method Generator eachItemTexts($condition = [], $batchSize = 100)
 * @method Generator batchItemTexts($condition = [], $batchSize = 100)
 * @method array getItemText($data)
 * @method array createItemText($data)
 * @method array updateItemText($data)
 * @method array deleteItemText($data)
 *
 * @method array listItemProperties($data)
 * @method Generator eachItemProperties($condition = [], $batchSize = 100)
 * @method Generator batchItemPropertes($condition = [], $batchSize = 100)
 * @method array getItemProperty($data)
 * @method array createItemProperty($data)
 * @method array updateItemProperty($data)
 * @method array deleteItemProperty($data)
 *
 * @method array listItemPropertyTexts($data)
 * @method Generator eachItemPropertyTexts($condition = [], $batchSize = 100)
 * @method Generator batchItemPropertyTexts($condition = [], $batchSize = 100)
 * @method array getItemPropertyText($data)
 * @method array createItemPropertyText($data)
 * @method array updateItemPropertyText($data)
 * @method array deleteItemPropertyText($data)
 *
 * @method array listItemShippingProfiles($data)
 * @method Generator eachItemShippingProfiles($condition = [], $batchSize = 100)
 * @method Generator batchItemShippingProfiles($condition = [], $batchSize = 100)
 * @method array getItemShippingProfile($data)
 * @method array createItemShippingProfile($data)
 * @method array updateItemShippingProfile($data)
 * @method array deleteItemShippingProfile($data)
 *
 * @method array listItemVariations($data)
 * @method Generator eachItemVariations($condition = [], $batchSize = 100)
 * @method Generator batchItemVariations($condition = [], $batchSize = 100)
 * @method array getItemVariation($data)
 * @method array createItemVariation($data)
 * @method array updateItemVariation($data)
 * @method array deleteItemVariation($data)
 *
 * @method array listItemVariationImages($data)
 * @method Generator eachItemVariationImages($condition = [], $batchSize = 100)
 * @method Generator batchItemVariationImages($condition = [], $batchSize = 100)
 * @method array getItemVariationImage($data)
 * @method array createItemVariationImage($data)
 * @method array updateItemVariationImage($data)
 * @method array deleteItemVariationImage($data)
 *
 * @method array listItemVariationSalesPrices($data)
 * @method Generator eachItemVariationSalesPrices($condition = [], $batchSize = 100)
 * @method Generator batchItemVariationSalesPrices($condition = [], $batchSize = 100)
 * @method array getItemVariationSalesPrice($data)
 * @method array createItemVariationSalesPrice($data)
 * @method array updateItemVariationSalesPrice($data)
 * @method array deleteItemVariationSalesPrice($data)
 *
 * @method array listItemVariationBundles($data)
 * @method Generator eachItemVariationBundles($condition = [], $batchSize = 100)
 * @method Generator batchItemVariationBundles($condition = [], $batchSize = 100)
 * @method array getItemVariationBundle($data)
 * @method array createItemVariationBundle($data)
 * @method array updateItemVariationBundle($data)
 * @method array deleteItemVariationBundle($data)
 *
 * @method array listItemVariationClients($data)
 * @method Generator eachItemVariationClients($condition = [], $batchSize = 100)
 * @method Generator batchItemVariationClients($condition = [], $batchSize = 100)
 * @method array getItemVariationClient($data)
 * @method array createItemVariationClient($data)
 * @method array updateItemVariationClient($data)
 * @method array deleteItemVariationClient($data)
 *
 * @method array listItemVariationMarkets($data)
 * @method Generator eachItemVariationMarkets($condition = [], $batchSize = 100)
 * @method Generator batchItemVariationMarkets($condition = [], $batchSize = 100)
 * @method array getItemVariationMarket($data)
 * @method array createItemVariationMarket($data)
 * @method array updateItemVariationMarket($data)
 * @method array deleteItemVariationMarket($data)
 *
 * @method array listItemVariationSkus($data)
 * @method Generator eachItemVariationSkus($condition = [], $batchSize = 100)
 * @method Generator batchItemVariationSkus($condition = [], $batchSize = 100)
 * @method array getItemVariationSku($data)
 * @method array createItemVariationSku($data)
 * @method array updateItemVariationSku($data)
 * @method array deleteItemVariationSku($data)
 *
 * @method array listItemVariationBarcodes($data)
 * @method Generator eachItemVariationBarcodes($condition = [], $batchSize = 100)
 * @method Generator batchItemVariationBarcodes($condition = [], $batchSize = 100)
 * @method array getItemVariationBarcode($data)
 * @method array createItemVariationBarcode($data)
 * @method array updateItemVariationBarcode($data)
 * @method array deleteItemVariationBarcode($data)
 *
 * @method array listWarehouses($data = [])
 * @method Generator eachWarehouses($condition = [], $batchSize = 100)
 * @method Generator batchWarehouses($condition = [], $batchSize = 100)
 * @method array getWarehouse($data)
 * @method array createWarehouse($data)
 * @method array updateWarehouse($data)
 * @method array deleteWarehouse($data)
 *
 * @method array listWarehouseLocationDimensions($data)
 * @method Generator eachWarehouseLocationDimensions($condition = [], $batchSize = 100)
 * @method Generator batchWarehouseLocationDimensions($condition = [], $batchSize = 100)
 * @method array getWarehouseLocationDimension($data)
 * @method array createWarehouseLocationDimension($data)
 * @method array updateWarehouseLocationDimension($data)
 * @method array deleteWarehouseLocationDimension($data)
 *
 * @method array listWarehouseLocationLevels($data)
 * @method Generator eachWarehouseLocationLevel($condition = [], $batchSize = 100)
 * @method Generator batchWarehouseLocationLevel($condition = [], $batchSize = 100)
 * @method array getWarehouseLocationLevel($data)
 * @method array createWarehouseLocationLevel($data)
 * @method array updateWarehouseLocationLevel($data)
 * @method array deleteWarehouseLocationLevel($data)
 *
 * @method array listWarehouseLocations($data)
 * @method Generator eachWarehouseLocations($condition = [], $batchSize = 100)
 * @method Generator batchWarehouseLocations($condition = [], $batchSize = 100)
 * @method array getWarehouseLocation($data)
 * @method array createWarehouseLocation($data)
 * @method array updateWarehouseLocation($data)
 * @method array deleteWarehouseLocation($data)
 *
 * @method array listCustomers($data = [])
 * @method Generator eachCustomers($condition = [], $batchSize = 100)
 * @method Generator batchCustomers($condition = [], $batchSize = 100)
 * @method array getCustomer($data)
 * @method array createCustomer($data)
 * @method array updateCustomer($data)
 * @method array deleteCustomer($data)
 *
 * @method array listAddresses($data = [])
 * @method Generator eachAddresses($condition = [], $batchSize = 100)
 * @method Generator batchAddresses($condition = [], $batchSize = 100)
 * @method array getAddress($data)
 * @method array createAddress($data)
 * @method array updateAddress($data)
 * @method array deleteAddress($data)
 *
 * @method array listCustomerAddresses($data)
 * @method Generator eachCustomerAddresses($condition = [], $batchSize = 100)
 * @method Generator batchCustomerAddresses($condition = [], $batchSize = 100)
 * @method array getCustomerAddress($data)
 * @method array createCustomerAddress($data)
 * @method array updateCustomerAddress($data)
 * @method array deleteCustomerAddress($data)
 *
 * @method array listCustomerBanks($data)
 * @method Generator eachCustomerBanks($condition = [], $batchSize = 100)
 * @method Generator batchCustomerBanks($condition = [], $batchSize = 100)
 * @method array getCustomerBanks($data)
 * @method array createCustomerBanks($data)
 * @method array updateCustomerBanks($data)
 * @method array deleteCustomerBanks($data)
 *
 * @method array listOrders($data = [])
 * @method Generator eachOrders($condition = [], $batchSize = 100)
 * @method Generator batchOrders($condition = [], $batchSize = 100)
 * @method array getOrder($data)
 * @method array createOrder($data)
 * @method array updateOrder($data)
 * @method array deleteOrder($data)
 * @method array cancelOrder($data)
 *
 * @method array listOrderProperties($data = [])
 * @method Generator eachOrderProperties($condition = [], $batchSize = 100)
 * @method Generator batchOrderProperties($condition = [], $batchSize = 100)
 * @method array getOrderProperty($data)
 * @method array createOrderProperty($data)
 * @method array updateOrderProperty($data)
 * @method array deleteOrderProperty($data)
 * @method array cancelOrderProperty($data)
 *
 * @method array listOrderPropertyTypes($data = [])
 * @method Generator eachOrderPropertyTypes($condition = [], $batchSize = 100)
 * @method Generator batchOrderPropertyTypes($condition = [], $batchSize = 100)
 * @method array getOrderPropertyType($data)
 * @method array createOrderPropertyType($data)
 * @method array updateOrderPropertyType($data)
 * @method array deleteOrderPropertyType($data)
 * @method array cancelOrderPropertyType($data)
 *
 * @method array listOrderShippingPackages($data)
 * @method Generator eachOrderShippingPackages($condition = [], $batchSize = 100)
 * @method Generator batchOrderShippingPackages($condition = [], $batchSize = 100)
 * @method array getOrderShippingPackage($data)
 * @method array createOrderShippingPackage($data)
 * @method array updateOrderShippingPackage($data)
 * @method array deleteOrderShippingPackage($data)
 *
 * @method array listOrderShippingPallets($data)
 * @method Generator eachOrderShippingPallets($condition = [], $batchSize = 100)
 * @method Generator batchOrderShippingPallets($condition = [], $batchSize = 100)
 * @method array getOrderShippingPallet($data)
 * @method array createOrderShippingPallet($data)
 * @method array updateOrderShippingPallet($data)
 * @method array deleteOrderShippingPallet($data)
 *
 * @method array listOrderShippingPalletPackages($data)
 * @method Generator eachOrderShippingPalletPackages($condition = [], $batchSize = 100)
 * @method Generator batchOrderShippingPalletPackages($condition = [], $batchSize = 100)
 * @method array getOrderShippingPalletPackages($data)
 * @method array createOrderShippingPalletPackages($data)
 * @method array updateOrderShippingPalletPackages($data)
 * @method array deleteOrderShippingPalletPackages($data)
 *
 * @method array listOrderShippingPackageItems($data)
 * @method Generator eachOrderShippingPackageItems($condition = [], $batchSize = 100)
 * @method Generator batchOrderShippingPackageItems($condition = [], $batchSize = 100)
 * @method array getOrderShippingPackageItem($data)
 * @method array createOrderShippingPackageItem($data)
 * @method array updateOrderShippingPackageItem($data)
 * @method array deleteOrderShippingPackageItem($data)
 *
 * @method array deleteOrderItem($data)
 *
 * @method array listOrderItemProperties($data)
 * @method Generator eachOrderItemProperties($condition = [], $batchSize = 100)
 * @method Generator batchOrderItemProperties($condition = [], $batchSize = 100)
 * @method array getOrderItemProperty($data)
 * @method array createOrderItemProperty($data)
 * @method array updateOrderItemProperty($data)
 * @method array deleteOrderItemProperty($data)
 *
 * @method array listOrderItemTransactions($data)
 * @method Generator eachOrderItemTransactions($condition = [], $batchSize = 100)
 * @method Generator batchOrderItemTransactions($condition = [], $batchSize = 100)
 * @method array getOrderItemTransaction($data)
 * @method array createOrderItemTransaction($data)
 * @method array updateOrderItemTransaction($data)
 * @method array deleteOrderItemTransaction($data)
 *
 * @method array listPayments($data = [])
 * @method Generator eachPayments($condition = [], $batchSize = 100)
 * @method Generator batchPayments($condition = [], $batchSize = 100)
 * @method array getPayment($data)
 * @method array createPayment($data)
 * @method array updatePayment($data)
 * @method array deletePayment($data)
 *
 * @method array listPaymentProperties($data)
 * @method Generator eachPaymentProperties($condition = [], $batchSize = 100)
 * @method Generator batchPaymentProperties($condition = [], $batchSize = 100)
 * @method array getPaymentProperty($data)
 * @method array createPaymentProperty($data)
 * @method array updatePaymentProperty($data)
 * @method array deletePaymentProperty($data)
 *
 * @method array listListings($data = [])
 * @method Generator eachListings($condition = [], $batchSize = 100)
 * @method Generator batchListings($condition = [], $batchSize = 100)
 * @method array getListing($data)
 * @method array createListing($data)
 * @method array updateListing($data)
 * @method array deleteListing($data)
 *
 * @method array listListingMarkets($data = [])
 * @method Generator eachListingMarkets($condition = [], $batchSize = 100)
 * @method Generator batchListingMarkets($condition = [], $batchSize = 100)
 * @method array getListingMarket($data)
 * @method array createListingMarket($data)
 * @method array updateListingMarket($data)
 * @method array deleteListingMarket($data)
 *
 * @method array listListingMarketHistories($data = [])
 * @method Generator eachListingMarketHistories($condition = [], $batchSize = 100)
 * @method Generator batchListingMarketHistories($condition = [], $batchSize = 100)
 * @method array getListingMarketHistory($data)
 * @method array relistListingMarketHistory($data)
 * @method array updateListingMarketHistory($data)
 * @method array endListingMarketHistory($data)
 *
 * @method array listListingMarketTexts($data = [])
 * @method Generator eachListingMarketTexts($condition = [], $batchSize = 100)
 * @method Generator batchListingMarketTexts($condition = [], $batchSize = 100)
 * @method array getListingMarketTexts($data)
 * @method array createListingMarketTexts($data)
 * @method array updateListingMarketTexts($data)
 * @method array deleteListingMarketTexts($data)
 *
 * @method array listComments($data = [])
 * @method Generator eachComments($condition = [], $batchSize = 100)
 * @method Generator batchComments($condition = [], $batchSize = 100)
 * @method array getComment($data)
 * @method array createComment($data)
 * @method array updateComment($data)
 * @method array deleteComment($data)
 *
 * @method array batchRequest($data)
 * @method array searchItemVariations($data)
 * @method array listVariations($data)
 * @method Generator eachVariations($condition = [])
 * @method Generator batchVariations($condition = [])
 * @method array listStocks($data)
 * @method array listTypeStocks($data)
 * @method array listWarehouseStocks($data)
 * @method array listWarehouseLocationStocks($data)
 * @method array listWarehouseStockMovements($data)
 * @method Generator eachStocks($data)
 * @method Generator eachTypeStocks($data)
 * @method Generator eachWarehouseStocks($data)
 * @method Generator eachWarehouseLocationStocks($data)
 * @method Generator eachWarehouseStockMovements($data)
 * @method array correctStock($data)
 * @method array bookIncomingStock($data)
 * @method array redistributeStock($data)
 * @method array bookOrderOutgoingStocks($data)
 * @method array revertOrderOutgoingStocks($data)
 * @method array listOrderDates($data)
 * @method array listOrderContracts($data)
 * @method array getOrderPackageNumbers($data)
 * @method array getOrderShippingInformation($data)
 * @method array getOrderShippingPackageItems($data)
 * @method array getOrderShippingPackagePackedItems($data)
 * @method array getOrderShippingPackageUnpackedItems($data)
 * @method array updateShippingPackageItemByUnionId($data)
 * @method array deleteShippingPackageItemByUnionId($data)
 * @method array listOrderPayments($data)
 * @method array listPaymentsByProperty($data)
 * @method array listPaymentsByTransactionId($data)
 * @method array createPaymentOrderRelation($data)
 *
 * @method array bulkCreateItemVariationCategories($data)
 * @method array bulkUpdateItemVariationCategories($data)
 * @method array bulkCreateItemVariationSalesPrices($data)
 * @method array bulkUpdateItemVariationSalesPrices($data)
 * @method array bulkDeleteItemVariationSalesPrices($data)
 * @method array bulkCreateItemVariationMarkets($data)
 * @method array bulkUpdateItemVariationMarkets($data)
 * @method array bulkDeleteItemVariationMarkets($data)
 * @method array bulkCreateItemVariationProperties($data)
 * @method array bulkUpdateItemVariationProperties($data)
 * @method array bulkDeleteItemVariationProperties($data)
 * @method array bulkCreateItemShippingProfiles($data)
 * @method array bulkDeleteItemShippingProfiles($data)
 *
 * @package lujie\plentyMarkets
 * @author Lujie Zhou <gao_lujie@live.cn>
 * @document https://developers.plentymarkets.com/en-gb/plentymarkets-rest-api/index.html
 */
class PlentyMarketsRestClient extends OAuth2
{
    use RestClientTrait, PlentyMarketsBatchRestTrait, PlentyMarketsRestExtendTrait;

    /**
     * @var string
     */
    public $tokenUrl = 'login';

    /**
     * @var string
     */
    public $refreshTokenUrl = 'login/refresh?refresh_token=';

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;

    /**
     * @var array
     */
    public $resources = [
        'SalesPrice' => 'items/sales_prices',
        'Attribute' => 'items/attributes',
        'AttributeName' => 'items/attributes/{attributeId}/names',
        'AttributeValue' => 'items/attributes/{attributeId}/values',
        'AttributeValueName' => 'items/attribute_values/{valueId}/names',

        'Item' => 'items',
        'ItemImage' => 'items/{itemId}/images',
        'ItemImageName' => 'items/{itemId}/images/{imageId}/names',
        'ItemImageAvailability' => 'items/{itemId}/images/{imageId}/availabilities',
        'ItemText' => 'items/{itemId}/variations/{mainVariationId}/descriptions',
        'ItemProperty' => 'items/{itemId}/variations/{mainVariationId}/variation_properties',
        'ItemPropertyText' => 'items/{itemId}/variations/{variationId}/variation_properties/{propertyId}/texts',
        'ItemShippingProfile' => 'items/{itemId}/item_shipping_profiles',

        'ItemVariation' => 'items/{itemId}/variations',
        'ItemVariationImage' => 'items/{itemId}/variations/{variationId}/variation_images',
        'ItemVariationSalesPrice' => 'items/{itemId}/variations/{variationId}/variation_sales_prices',
        'ItemVariationBundle' => 'items/{itemId}/variations/{variationId}/variation_bundles',
        'ItemVariationClient' => 'items/{itemId}/variations/{variationId}/variation_clients',
        'ItemVariationMarket' => 'items/{itemId}/variations/{variationId}/variation_markets',
        'ItemVariationSku' => 'items/{itemId}/variations/{variationId}/variation_skus',
        'ItemVariationBarcode' => 'items/{itemId}/variations/{variationId}/variation_barcodes',

        'Warehouse' => 'stockmanagement/warehouses',
        'WarehouseLocationDimension' => 'warehouses/{warehouseId}/locations/dimensions',
        'WarehouseLocationLevel' => 'warehouses/{warehouseId}/locations/levels',
        'WarehouseLocation' => 'warehouses/{warehouseId}/locations',

        'Customer' => 'accounts/contacts',
        'Address' => 'accounts/addresses',
        'CustomerAddress' => 'accounts/contacts/{contactId}/addresses',
        'CustomerBanks' => 'accounts/contacts/{contactId}/banks',
        'Order' => 'orders',
        'OrderProperty' => 'orders/{orderId}/properties',
        'OrderPropertyType' => 'orders/properties/types',
        'OrderShippingPackage' => 'orders/{orderId}/shipping/packages',
        'OrderShippingPallet' => 'orders/{orderId}/shipping/pallets',
        'OrderShippingPalletPackages' => 'orders/{orderId}/shipping/pallets/{palletId}/packages',
        'OrderShippingPackageItem' => 'orders/shipping/packages', //see extra action
        'OrderItem' => 'orders/{orderId}/items',
        'OrderItemProperty' => 'orders/items/{orderItemId}/properties',
        'OrderItemTransaction' => 'orders/items/{orderItemId}/transactions',
        'Payment' => 'payments',
        'PaymentProperty' => 'payments/{paymentId}/properties',

        'Listing' => 'listings',
        'ListingMarket' => 'listings/markets',
        'ListingMarketHistory' => 'listings/markets/histories',
        'ListingMarketTexts' => 'listings/markets/texts',

        'Comment' => 'comments',
    ];

    /**
     * @var array
     */
    public $extraActions = [
        'AttributeName' => [
            'get' => ['GET', '{lang}'],
            'update' => ['PUT', '{lang}'],
            'delete' => ['DELETE', '{lang}'],
        ],
        'AttributeValueName' => [
            'get' => ['GET', '{lang}'],
            'update' => ['PUT', '{lang}'],
            'delete' => ['DELETE', '{lang}'],
        ],
        'ItemImageName' => [
            'get' => ['GET', '{lang}'],
            'update' => ['PUT', '{lang}'],
            'delete' => ['DELETE', '{lang}'],
        ],
        'ItemImageAvailability' => [
            'get' => ['GET', ''],
            'update' => ['POST', ''],
            'delete' => ['DELETE', ''],
        ],
        'ItemText' => [
            'get' => ['GET', '{lang}'],
            'update' => ['PUT', '{lang}'],
            'delete' => ['DELETE', '{lang}'],
        ],
        'ItemPropertyText' => [
            'get' => ['GET', '{lang}'],
            'update' => ['PUT', '{lang}'],
            'delete' => ['DELETE', '{lang}'],
        ],
        'ItemVariationImage' => [
            'delete' => ['DELETE', '{imageId}'],
        ],
        'ItemVariationSalesPrice' => [
            'get' => ['GET', '{salesPriceId}'],
            'update' => ['PUT', '{salesPriceId}'],
            'delete' => ['DELETE', '{salesPriceId}'],
        ],
        'ItemVariationClient' => [
            'get' => ['GET', '{plentyId}'],
            'update' => ['PUT', '{plentyId}'],
            'delete' => ['DELETE', '{plentyId}'],
        ],
        'ItemVariationMarket' => [
            'get' => ['GET', '{marketId}'],
            'update' => ['PUT', '{marketId}'],
            'delete' => ['DELETE', '{marketId}'],
        ],
        'ItemVariationBarcode' => [
            'get' => ['GET', '{barcodeId}'],
            'update' => ['PUT', '{barcodeId}'],
            'delete' => ['DELETE', '{barcodeId}'],
        ],
        'ItemImage' => [
            'create' => ['POST', 'upload'],
        ],
        'Order' => [
            'cancel' => ['PUT', '{id}/cancel'],
        ],
        'OrderProperty' => [
            'get' => ['GET', '{typeId}'],
            'create' => ['POST', '{typeId}'],
            'update' => ['PUT', '{typeId}'],
            'delete' => ['DELETE', '{typeId}'],
        ],
        'OrderShippingPackageItem' => [
            'list' => ['GET', '{packageId}/items'],
            'create' => ['PUT', '{packageId}/items'],
            'update' => ['PUT', 'items/{id}'],
            'delete' => ['DELETE', 'items/{id}'],
        ],
        'OrderItemProperty' => [
            'get' => ['GET', '{typeId}'],
            'create' => ['POST', '{typeId}'],
            'update' => ['PUT', '{typeId}'],
            'delete' => ['DELETE', '{typeId}'],
        ],
        'ListingMarketHistory' => [
            'relist' => ['POST', 'relist/{id}'],
            'update' => ['PUT', 'update/{id}'],
            'end' => ['DELETE', 'end/{id}'],
        ],
    ];

    /**
     * @var array
     */
    public $extraMethods = [
        'batchRequest' => ['POST', 'batch'],
        'searchItemVariations' => ['GET', 'items/variations'],
        'listVariations' => ['GET', 'items/variations'],

        'listStocks' => ['GET', 'stockmanagement/stock'],
        'listTypeStocks' => ['GET', 'stockmanagement/stock/types/{type}'],
        'listWarehouseStocks' => ['GET', 'stockmanagement/warehouses/{warehouseId}/stock'],
        'listWarehouseLocationStocks' => ['GET', 'stockmanagement/warehouses/{warehouseId}/stock/storageLocations'],
        'listWarehouseStockMovements' => ['GET', 'stockmanagement/warehouses/{warehouseId}/stock/movements'],

        'correctStock' => ['PUT', 'stockmanagement/warehouses/{warehouseId}/stock/correction'],
        'bookIncomingStock' => ['PUT', 'stockmanagement/warehouses/{warehouseId}/stock/bookIncomingItems'],
        'redistributeStock' => ['PUT', 'stockmanagement/stock/redistribute'],

        'bookOrderOutgoingStocks' => ['POST', 'orders/{id}/outgoing_stocks'],
        'revertOrderOutgoingStocks' => ['DELETE', 'orders/{id}/outgoing_stocks'],

        'listOrderDates' => ['GET', 'orders/{orderId}/dates'],
        'listOrderContracts' => ['GET', 'orders/contacts/{contactId}'],
        'getOrderPackageNumbers' => ['GET', 'orders/{orderId}/packagenumbers'],
        'getOrderShippingInformation' => ['GET', 'orders/{orderId}/shipping/shipping_information'],
        'getOrderShippingPackageItems' => ['GET', 'orders/{orderId}/shipping/packages/items'],
        'getOrderShippingPackagePackedItems' => ['GET', 'orders/{orderId}/shipping/packages/packed_items'],
        'getOrderShippingPackageUnpackedItems' => ['GET', 'orders/{orderId}/shipping/packages/unpacked_items'],
        'updateShippingPackageItemByUnionId' => ['PUT', 'orders/shipping/packages/items/{packageId}/{itemId}/{variationId}'],
        'deleteShippingPackageItemByUnionId' => ['DELETE', 'orders/shipping/packages/items/{packageId}/{itemId}/{variationId}'],

        'listOrderPayments' => ['GET', 'payments/orders/{orderId}'],
        'listPaymentsByProperty' => ['GET', 'payments/property/{propertyTypeId}/{propertyValue}'],
        'listPaymentsByTransactionId' => ['GET', 'payments/property/1/{transactionId}'],

        'createPaymentOrderRelation' => ['POST', 'payment/{paymentId}/order/{orderId}'],

        'bulkCreateItemVariationCategories' => ['POST', 'items/variations/variation_categories'],
        'bulkUpdateItemVariationCategories' => ['PUT', 'items/variations/variation_categories'],

        'bulkCreateItemVariationSalesPrices' => ['POST', 'items/variations/variation_sales_prices'],
        'bulkUpdateItemVariationSalesPrices' => ['PUT', 'items/variations/variation_sales_prices'],
        'bulkDeleteItemVariationSalesPrices' => ['DELETE', 'items/{itemId}/variations/{variationId}/variation_sales_prices'],

        'bulkCreateItemVariationMarkets' => ['POST', 'items/variations/variation_markets'],
        'bulkUpdateItemVariationMarkets' => ['PUT', 'items/variations/variation_markets'],
        'bulkDeleteItemVariationMarkets' => ['DELETE', 'items/{itemId}/variations/{variationId}/variation_markets'],

        'bulkCreateItemVariationProperties' => ['POST', 'items/variations/variation_properties'],
        'bulkUpdateItemVariationProperties' => ['PUT', 'items/variations/variation_properties'],
        'bulkDeleteItemVariationProperties' => ['DELETE', 'items/{itemId}/variations/{variationId}/variation_properties'],

        'bulkCreateItemShippingProfiles' => ['POST', 'items/item_shipping_profiles'],
        'bulkDeleteItemShippingProfiles' => ['DELETE', 'items/{itemId}/item_shipping_profiles'],
    ];

    /**
     * @var array
     */
    public $httpClientOptions = [
        'transport' => CurlTransport::class,
        'requestConfig' => [
            'format' => 'json'
        ],
        'responseConfig' => [
            'format' => 'json'
        ],
        'as shortPeriodRateLimitChecker' => [
            'class' => RateLimitCheckerBehavior::class,
            'limitHeader' => 'X-Plenty-Global-Short-Period-Limit',
            'remainingHeader' => 'X-Plenty-Global-Short-Period-Calls-Left',
            'resetHeader' => 'X-Plenty-Global-Short-Period-Decay',
        ],
        'as longPeriodRateLimitChecker' => [
            'class' => RateLimitCheckerBehavior::class,
            'limitHeader' => 'X-Plenty-Global-Long-Period-Limit',
            'remainingHeader' => 'X-Plenty-Global-Long-Period-Calls-Left',
            'resetHeader' => 'X-Plenty-Global-Long-Period-Decay',
        ]
    ];

    /**
     * @var bool
     */
    public $reverse = false;

    /**
     * @param bool $reverse
     * @return $this
     * @inheritdoc
     */
    public function reverse(bool $reverse = true): self
    {
        $this->reverse = $reverse;
        return $this;
    }

    public function init(): void
    {
        parent::init();
        $this->initRest();
    }

    /**
     * Returns default HTTP request options.
     * @return array HTTP request options.
     * @since 2.1
     */
    protected function defaultRequestOptions(): array
    {
        if ($this->httpClient->transport instanceof CurlTransport) {
            return [
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_CONNECTTIMEOUT => 15,
                CURLOPT_TIMEOUT => 75,
            ];
        }
        return [
            'timeout' => 90,
        ];
    }

    #region BaseOAuth

    /**
     * @return string
     * @inheritdoc
     */
    public function getId(): string
    {
        return $this->username;
    }

    /**
     * @return array
     * @inheritdoc
     */
    protected function initUserAttributes(): array
    {
        return $this->api('authorized_user');
    }

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
     * @throws InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function refreshAccessToken(OAuthToken $token): OAuthToken
    {
        $request = $this->createRequest()
            ->setMethod('POST')
            ->setUrl($this->refreshTokenUrl . $token->getParam('refresh_token'));

        $this->applyClientCredentialsToRequest($request);

        try {
            $responseData = $this->sendRequest($request);
            $token = $this->createToken(['params' => $responseData]);
            $this->setAccessToken($token);
            return $token;
        } catch (InvalidResponseException $e) {
            if ($e->response->statusCode === '401') {
                return $this->authenticateUser($this->username, $this->password);
            }
            throw $e;
        }
    }

    /**
     * @param Request $request HTTP request instance.
     * @param OAuthToken $accessToken access token instance.
     * @inheritdoc
     */
    public function applyAccessTokenToRequest($request, $accessToken): void
    {
        $request->getHeaders()->set('Authorization', 'Bearer ' . $accessToken->getToken());
    }

    /**
     * @param Request $request
     * @inheritdoc
     */
    protected function applyClientCredentialsToRequest($request): void
    {
    }

    #endregion BaseOAuth

    /**
     * @param array|string $apiSubUrl
     * @param string $method
     * @param array $data
     * @param array $headers
     * @return array
     * @inheritdoc
     */
    public function api($apiSubUrl, $method = 'GET', $data = [], $headers = [])
    {
        if ($method === 'PUT' && strpos($apiSubUrl, 'listings/markets/histories/update') !== false) {
            unset($data['id']);
        }
        return parent::api($apiSubUrl, $method, $data, $headers);
    }

    /**
     * @param string $name
     * @param array $data
     * @return array
     * @inheritdoc
     */
    public function restApi(string $name, array $data): ?array
    {
        $response = $this->callApiMethod($name, $data);
        if ($name === 'listWarehouseLocations') {
            array_walk($response['entries'], static function (&$values) use ($data) {
                $values['warehouseId'] = $data['warehouseId'];
            });
        }
        return $response;
    }

    /**
     * @param string $resource
     * @param array $condition
     * @param int $batchSize
     * @return Iterator
     * @inheritdoc
     */
    public function batch(string $resource, array $condition = [], int $batchSize = 100): Iterator
    {
        $condition['itemsPerPage'] = $batchSize;
        $listMethod = 'list' . Inflector::pluralize($resource);
        if ($this->reverse) {
            $responseData = $this->restApi($listMethod, $condition) ?? [];
            $firstPageItems = $responseData['entries'] ?? $responseData;
            $firstPage = $condition['page'] ?? 1;
            $condition['page'] = $responseData['lastPageNumber'] ?? 1;

            while ($condition['page'] > $firstPage) {
                $responseData = $this->restApi($listMethod, $condition);
                $items = $responseData['entries'] ?? $responseData;

                yield array_reverse($items);

                $condition['page']--;
            }

            yield array_reverse($firstPageItems);
        } else {
            do {
                $responseData = $this->restApi($listMethod, $condition);
                yield $responseData['entries'] ?? $responseData;

                $pageCount = $responseData['lastPageNumber'] ?? 1;
                $condition['page'] = $condition['page'] ?? 1;
                $condition['page']++;
            } while ($condition['page'] <= $pageCount);
        }
    }

    /**
     * @return PlentyMarketBatchRequest
     * @inheritdoc
     */
    public function createBatchRequest(): PlentyMarketBatchRequest
    {
        return new PlentyMarketBatchRequest(['client' => $this]);
    }

    #region Additional

    /**
     * @param int $orderId
     * @param int $warehouseId
     * @param bool $refreshLocationAssignment
     * @inheritdoc
     */
    public function updateOrderWarehouse(int $orderId, int $warehouseId, bool $refreshLocationAssignment = true): void
    {
        $order = $this->getOrder([
            'id' => $orderId,
        ]);

        $batchRequest = $this->createBatchRequest();
        foreach ($order['orderItems'] as $orderItem) {
            if (empty($orderItem['itemVariationId'])) {
                continue;
            }
            $orderItemProperties = ArrayHelper::index($orderItem['properties'], 'typeId');
            if (empty($orderItemProperties[PlentyMarketsConst::ORDER_ITEM_PROPERTY_TYPE_IDS['WAREHOUSE']])) {
                $batchRequest->createOrderItemProperty([
                    'orderItemId' => $orderItem['id'],
                    'typeId' => PlentyMarketsConst::ORDER_ITEM_PROPERTY_TYPE_IDS['WAREHOUSE'],
                    'value' => $warehouseId
                ]);
            } else if ((int)$orderItemProperties[PlentyMarketsConst::ORDER_ITEM_PROPERTY_TYPE_IDS['WAREHOUSE']] !== $warehouseId) {
                $batchRequest->updateOrderItemProperty([
                    'orderItemId' => $orderItem['id'],
                    'typeId' => PlentyMarketsConst::ORDER_ITEM_PROPERTY_TYPE_IDS['WAREHOUSE'],
                    'value' => $warehouseId
                ]);
            }
            if (isset($orderItemProperties[PlentyMarketsConst::ORDER_ITEM_PROPERTY_TYPE_IDS['LOCATION_RESERVED']])) {
                $batchRequest->deleteOrderItemProperty([
                    'orderItemId' => $orderItem['id'],
                    'typeId' => PlentyMarketsConst::ORDER_ITEM_PROPERTY_TYPE_IDS['LOCATION_RESERVED'],
                ]);
            }
        }
        $batchRequest->send();

        if ($refreshLocationAssignment) {
            $orderRelations = ArrayHelper::index($order['relations'], 'referenceType');
            $orderRelations['warehouse']['referenceId'] = $warehouseId;
            $this->updateOrder([
                'id' => $orderId,
                'relations' => array_values($orderRelations),
                'statusId' => 5.8
            ]);
            if ($order['statusId'] !== 5) {
                $this->updateOrder([
                    'id' => $orderId,
                    'statusId' => $order['statusId']
                ]);
            }
        }
    }

    /**
     * @param int $orderId
     * @param array $packageNumbers
     * @inheritdoc
     */
    public function updateOrderShippingNumbers(int $orderId, array $packageNumbers): void
    {
        $batchRequest = $this->createBatchRequest();
        $orderShippingPackages = $this->eachOrderShippingPackages(['orderId' => $orderId]);
        $orderShippingPackages = iterator_to_array($orderShippingPackages, false);
        foreach ($packageNumbers as $packageNumber) {
            if ($orderShippingPackages) {
                $orderShippingPackage = array_shift($orderShippingPackages);
                if ($orderShippingPackage['packageNumber'] !== $packageNumber) {
                    $orderShippingPackage['packageNumber'] = $packageNumber;
                    $batchRequest->updateOrderShippingPackage($orderShippingPackage);
                }
            } else {
                $batchRequest->createOrderShippingPackage([
                    'orderId' => $orderId,
                    'packageNumber' => $packageNumber,
                    'packageId' => 2,
                    'packageType' => 0,
                ]);
            }
        }
        if ($orderShippingPackages) {
            foreach ($orderShippingPackages as $orderShippingPackage) {
                $batchRequest->deleteOrderShippingPackage($orderShippingPackage);
            }
        }
        $batchRequest->send();
    }

    /**
     * @param int $orderId
     * @param array $address
     * @param array $addressTypes
     * @return array
     * @inheritdoc
     */
    public function updateOrderAddresses(int $orderId, array $address, array $addressTypes = []): array
    {
        $addressTypes = $addressTypes ?: [
            PlentyMarketsConst::ADDRESS_TYPE_IDS['billing'],
            PlentyMarketsConst::ADDRESS_TYPE_IDS['delivery'],
        ];
        $customer = $this->getOrCreateOrderCustomer($orderId, $address);
        $concatId = $customer['id'];
        $customerAddress = $this->createOrUpdateCustomerAddress($concatId, $address, $address['id'] ?? null);
        $addressId = $customerAddress['id'];
        $addressOptions = [];
        foreach ($addressTypes as $addressType) {
            $addressOptions[] =                [
                'typeId' => $addressType,
                'addressId' => $addressId,
            ];
        }
        $orderData = [
            'id' => $orderId,
            'addressRelations' => $addressOptions,
            'relations' => [
                [
                    'referenceType' => 'contact',
                    'referenceId' => $concatId,
                    'relation' => 'receiver',
                ],
            ],
        ];
        return $this->updateOrder($orderData);
    }

    /**
     * @param int $orderId
     * @param array $address
     * @return array
     * @inheritdoc
     */
    protected function getOrCreateOrderCustomer(int $orderId,array $address): array
    {
        $order = $this->getOrder(['id' => $orderId]);
        if (empty($order)) {
            throw new InvalidArgumentException("Invalid order {$orderId}");
        }
        $phone = $address['phone'] ?? null;
        $email = $address['email'] ?? null;
        $customer = null;
        if ($email && empty($customer)) {
            $customers = $this->eachCustomers(['email' => $phone]);
            if ($customer = $customers->current()) {
                return $customer;
            }
        }
        if ($phone && empty($customer)) {
            $customers = $this->eachCustomers(['privatePhone' => $phone]);
            if ($customer = $customers->current()) {
                return $customer;
            }
        }
        if (empty($customer)) {
            $contactData = [
                'referrerId' => $order['referrerId'],
                'plentyId' => $order['plentyId'],
                'typeId' => PlentyMarketsConst::CONTACT_TYPE_IDS['Customer'],
                'firstName' => $address['name2'] ?? $address['firstName'] ?? $address['first_name'],
                'lastName' => $address['name3'] ?? $address['lastName'] ?? $address['last_name'],
                'email' => $email ?: '',
            ];
            $contactOptions = [];
            if ($email) {
                $contactOptions[] = [
                    'typeId' => 2,
                    'subTypeId' => 4,
                    'value' => (string)$email,
                    'priority' => 0,
                ];
            }
            if ($phone) {
                $contactOptions[] = [
                    'typeId' => 1,
                    'subTypeId' => 4,
                    'value' => (string)$phone,
                    'priority' => 0,
                ];
            }
            if ($contactOptions) {
                $contactData['options'] = $contactOptions;
            }
            return $this->createCustomer($contactData);
        }
    }

    /**
     * @param int $concatId
     * @param array $address
     * @param int|null $addressId
     * @return array
     * @inheritdoc
     */
    protected function createOrUpdateCustomerAddress(int $concatId, array $address, ?int $addressId = null): array
    {
        $countryCodeToId = array_flip(array_unique(PlentyMarketsConst::COUNTRY_CODES));
        //fix GB to UK
        $countryCodeToId['GB'] = $countryCodeToId['UK'];
        $country = $address['country'] ?? 'NULL';
        $countryId = $countryCodeToId[$country] ?? null;
        if (empty($countryId)) {
            throw new InvalidArgumentException('Invalid country: ' . $country);
        }

        $addressData = [
            'contactId' => $concatId,
            'name1' => $address['name1'] ?? $address['companyName'],
            'name2' => $address['name2'] ?? $address['firstName'],
            'name3' => $address['name3'] ?? $address['lastName'],

            'address1' => $address['address1'] ?? $address['street'],
            'address2' => $address['address2'] ?? $address['houseNo'],
            'address3' => $address['address3'] ?? $address['additional'],
            'postalCode' => $address['postalCode'] ?? $address['zipCode'],
            'town' => $address['town'] ?? $address['city'],
            'countryId' => $countryId,
        ];

        $addressData = PlentyMarketAddressFormatter::format($addressData);

        $phone = $address['phone'] ?? null;
        $email = $address['email'] ?? null;
        $addressOptions = [];
        if ($email) {
            $addressOptions[] = [
                'typeId' => PlentyMarketsConst::ADDRESS_OPTION_TYPE_IDS['Email'],
                'value' => (string)$email,
            ];
        }
        if ($phone) {
            $addressOptions[] = [
                'typeId' => PlentyMarketsConst::ADDRESS_OPTION_TYPE_IDS['Telephone'],
                'value' => (string)$phone,
            ];
        }
        if ($addressOptions) {
            $addressData['options'] = $addressOptions;
        }
        if ($addressId) {
            $addressData['id'] = $addressId;
        }
        if (isset($addressData['id'])) {
            return $this->updateAddress($addressData);
        }
        return $this->createCustomerAddress($addressData);
    }

    #endregion
}

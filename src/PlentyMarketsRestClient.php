<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\plentyMarkets;

use Generator;
use Iterator;
use lujie\extend\authclient\RestClientTrait;
use lujie\extend\httpclient\RateLimitCheckerBehavior;
use yii\authclient\BaseOAuth;
use yii\authclient\InvalidResponseException;
use yii\authclient\OAuth2;
use yii\authclient\OAuthToken;
use yii\base\InvalidArgumentException;
use yii\helpers\Inflector;
use yii\httpclient\Request;
use yii\web\NotFoundHttpException;

/**
 * Class PlentyMarketsRestClient
 *
 * @method array listSalesPrices($data = [])
 * @method Generator eachSalesPrice($condition = [], $batchSize = 100)
 * @method Generator batchSalesPrice($condition = [], $batchSize = 100)
 * @method array getSalesPrice($data)
 * @method array createSalesPrice($data)
 * @method array updateSalesPrice($data)
 * @method array deleteSalesPrice($data)
 *
 * @method array listAttributes($data = [])
 * @method Generator eachAttribute($condition = [], $batchSize = 100)
 * @method Generator batchAttribute($condition = [], $batchSize = 100)
 * @method array getAttribute($data)
 * @method array createAttribute($data)
 * @method array updateAttribute($data)
 * @method array deleteAttribute($data)
 *
 * @method array listAttributeNames($data)
 * @method Generator eachAttributeName($condition = [], $batchSize = 100)
 * @method Generator batchAttributeName($condition = [], $batchSize = 100)
 * @method array getAttributeName($data)
 * @method array createAttributeName($data)
 * @method array updateAttributeName($data)
 * @method array deleteAttributeName($data)
 *
 * @method array listAttributeValues($data)
 * @method Generator eachAttributeValue($condition = [], $batchSize = 100)
 * @method Generator batchAttributeValue($condition = [], $batchSize = 100)
 * @method array getAttributeValue($data)
 * @method array createAttributeValue($data)
 * @method array updateAttributeValue($data)
 * @method array deleteAttributeValue($data)
 *
 * @method array listAttributeValueNames($data)
 * @method Generator eachAttributeValueName($condition = [], $batchSize = 100)
 * @method Generator batchAttributeValueName($condition = [], $batchSize = 100)
 * @method array getAttributeValueName($data)
 * @method array createAttributeValueName($data)
 * @method array updateAttributeValueName($data)
 * @method array deleteAttributeValueName($data)
 *
 * @method array listItems($data = [])
 * @method Generator eachItem($condition = [], $batchSize = 100)
 * @method Generator batchItem($condition = [], $batchSize = 100)
 * @method array getItem($data)
 * @method array createItem($data)
 * @method array updateItem($data)
 * @method array deleteItem($data)
 *
 * @method array listItemImages($data)
 * @method Generator eachItemImage($condition = [], $batchSize = 100)
 * @method Generator batchItemImage($condition = [], $batchSize = 100)
 * @method array getItemImage($data)
 * @method array createItemImage($data)
 * @method array updateItemImage($data)
 * @method array deleteItemImage($data)
 *
 * @method array listItemImageNames($data)
 * @method Generator eachItemImageName($condition = [], $batchSize = 100)
 * @method Generator batchItemImageName($condition = [], $batchSize = 100)
 * @method array getItemImageName($data)
 * @method array createItemImageName($data)
 * @method array updateItemImageName($data)
 * @method array deleteItemImageName($data)
 *
 * @method array listItemImageAvailabilities($data)
 * @method Generator eachItemImageAvailability($condition = [], $batchSize = 100)
 * @method Generator batchItemImageAvailability($condition = [], $batchSize = 100)
 * @method array getItemImageAvailability($data)
 * @method array createItemImageAvailability($data)
 * @method array updateItemImageAvailability($data)
 * @method array deleteItemImageAvailability($data)
 *
 * @method array listItemTexts($data)
 * @method Generator eachItemText($condition = [], $batchSize = 100)
 * @method Generator batchItemText($condition = [], $batchSize = 100)
 * @method array getItemText($data)
 * @method array createItemText($data)
 * @method array updateItemText($data)
 * @method array deleteItemText($data)
 *
 * @method array listItemProperties($data)
 * @method Generator eachItemProperty($condition = [], $batchSize = 100)
 * @method Generator batchItemProperty($condition = [], $batchSize = 100)
 * @method array getItemProperty($data)
 * @method array createItemProperty($data)
 * @method array updateItemProperty($data)
 * @method array deleteItemProperty($data)
 *
 * @method array listItemPropertyTexts($data)
 * @method Generator eachItemPropertyText($condition = [], $batchSize = 100)
 * @method Generator batchItemPropertyText($condition = [], $batchSize = 100)
 * @method array getItemPropertyText($data)
 * @method array createItemPropertyText($data)
 * @method array updateItemPropertyText($data)
 * @method array deleteItemPropertyText($data)
 *
 * @method array listItemShippingProfiles($data)
 * @method Generator eachItemShippingProfile($condition = [], $batchSize = 100)
 * @method Generator batchItemShippingProfile($condition = [], $batchSize = 100)
 * @method array getItemShippingProfile($data)
 * @method array createItemShippingProfile($data)
 * @method array updateItemShippingProfile($data)
 * @method array deleteItemShippingProfile($data)
 *
 * @method array listItemVariations($data)
 * @method Generator eachItemVariation($condition = [], $batchSize = 100)
 * @method Generator batchItemVariation($condition = [], $batchSize = 100)
 * @method array getItemVariation($data)
 * @method array createItemVariation($data)
 * @method array updateItemVariation($data)
 * @method array deleteItemVariation($data)
 *
 * @method array listItemVariationImages($data)
 * @method Generator eachItemVariationImage($condition = [], $batchSize = 100)
 * @method Generator batchItemVariationImage($condition = [], $batchSize = 100)
 * @method array getItemVariationImage($data)
 * @method array createItemVariationImage($data)
 * @method array updateItemVariationImage($data)
 * @method array deleteItemVariationImage($data)
 *
 * @method array listItemVariationSalesPrices($data)
 * @method Generator eachItemVariationSalesPrice($condition = [], $batchSize = 100)
 * @method Generator batchItemVariationSalesPrice($condition = [], $batchSize = 100)
 * @method array getItemVariationSalesPrice($data)
 * @method array createItemVariationSalesPrice($data)
 * @method array updateItemVariationSalesPrice($data)
 * @method array deleteItemVariationSalesPrice($data)
 *
 * @method array listItemVariationBundles($data)
 * @method Generator eachItemVariationBundle($condition = [], $batchSize = 100)
 * @method Generator batchItemVariationBundle($condition = [], $batchSize = 100)
 * @method array getItemVariationBundle($data)
 * @method array createItemVariationBundle($data)
 * @method array updateItemVariationBundle($data)
 * @method array deleteItemVariationBundle($data)
 *
 * @method array listItemVariationClients($data)
 * @method Generator eachItemVariationClient($condition = [], $batchSize = 100)
 * @method Generator batchItemVariationClient($condition = [], $batchSize = 100)
 * @method array getItemVariationClient($data)
 * @method array createItemVariationClient($data)
 * @method array updateItemVariationClient($data)
 * @method array deleteItemVariationClient($data)
 *
 * @method array listItemVariationMarkets($data)
 * @method Generator eachItemVariationMarket($condition = [], $batchSize = 100)
 * @method Generator batchItemVariationMarket($condition = [], $batchSize = 100)
 * @method array getItemVariationMarket($data)
 * @method array createItemVariationMarket($data)
 * @method array updateItemVariationMarket($data)
 * @method array deleteItemVariationMarket($data)
 *
 * @method array listItemVariationSkus($data)
 * @method Generator eachItemVariationSku($condition = [], $batchSize = 100)
 * @method Generator batchItemVariationSku($condition = [], $batchSize = 100)
 * @method array getItemVariationSku($data)
 * @method array createItemVariationSku($data)
 * @method array updateItemVariationSku($data)
 * @method array deleteItemVariationSku($data)
 *
 * @method array listItemVariationBarcodes($data)
 * @method Generator eachItemVariationBarcode($condition = [], $batchSize = 100)
 * @method Generator batchItemVariationBarcode($condition = [], $batchSize = 100)
 * @method array getItemVariationBarcode($data)
 * @method array createItemVariationBarcode($data)
 * @method array updateItemVariationBarcode($data)
 * @method array deleteItemVariationBarcode($data)
 *
 * @method array listWarehouses($data = [])
 * @method Generator eachWarehouse($condition = [], $batchSize = 100)
 * @method Generator batchWarehouse($condition = [], $batchSize = 100)
 * @method array getWarehouse($data)
 * @method array createWarehouse($data)
 * @method array updateWarehouse($data)
 * @method array deleteWarehouse($data)
 *
 * @method array listWarehouseLocationDimensions($data)
 * @method Generator eachWarehouseLocationDimension($condition = [], $batchSize = 100)
 * @method Generator batchWarehouseLocationDimension($condition = [], $batchSize = 100)
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
 * @method Generator eachWarehouseLocation($condition = [], $batchSize = 100)
 * @method Generator batchWarehouseLocation($condition = [], $batchSize = 100)
 * @method array getWarehouseLocation($data)
 * @method array createWarehouseLocation($data)
 * @method array updateWarehouseLocation($data)
 * @method array deleteWarehouseLocation($data)
 *
 * @method array listCustomers($data = [])
 * @method Generator eachCustomer($condition = [], $batchSize = 100)
 * @method Generator batchCustomer($condition = [], $batchSize = 100)
 * @method array getCustomer($data)
 * @method array createCustomer($data)
 * @method array updateCustomer($data)
 * @method array deleteCustomer($data)
 *
 * @method array listAddresses($data = [])
 * @method Generator eachAddress($condition = [], $batchSize = 100)
 * @method Generator batchAddress($condition = [], $batchSize = 100)
 * @method array getAddress($data)
 * @method array createAddress($data)
 * @method array updateAddress($data)
 * @method array deleteAddress($data)
 *
 * @method array listCustomerAddresses($data)
 * @method Generator eachCustomerAddress($condition = [], $batchSize = 100)
 * @method Generator batchCustomerAddress($condition = [], $batchSize = 100)
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
 * @method Generator eachOrder($condition = [], $batchSize = 100)
 * @method Generator batchOrder($condition = [], $batchSize = 100)
 * @method array getOrder($data)
 * @method array createOrder($data)
 * @method array updateOrder($data)
 * @method array deleteOrder($data)
 * @method array cancelOrder($data)
 *
 * @method array listOrderProperties($data = [])
 * @method Generator eachOrderProperty($condition = [], $batchSize = 100)
 * @method Generator batchOrderProperty($condition = [], $batchSize = 100)
 * @method array getOrderProperty($data)
 * @method array createOrderProperty($data)
 * @method array updateOrderProperty($data)
 * @method array deleteOrderProperty($data)
 * @method array cancelOrderProperty($data)
 *
 * @method array listOrderShippingPackages($data)
 * @method Generator eachOrderShippingPackage($condition = [], $batchSize = 100)
 * @method Generator batchOrderShippingPackage($condition = [], $batchSize = 100)
 * @method array getOrderShippingPackage($data)
 * @method array createOrderShippingPackage($data)
 * @method array updateOrderShippingPackage($data)
 * @method array deleteOrderShippingPackage($data)
 *
 * @method array listOrderShippingPallets($data)
 * @method Generator eachOrderShippingPallet($condition = [], $batchSize = 100)
 * @method Generator batchOrderShippingPallet($condition = [], $batchSize = 100)
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
 * @method Generator eachOrderShippingPackageItem($condition = [], $batchSize = 100)
 * @method Generator batchOrderShippingPackageItem($condition = [], $batchSize = 100)
 * @method array getOrderShippingPackageItem($data)
 * @method array createOrderShippingPackageItem($data)
 * @method array updateOrderShippingPackageItem($data)
 * @method array deleteOrderShippingPackageItem($data)
 *
 * @method array listPayments($data = [])
 * @method Generator eachPayment($condition = [], $batchSize = 100)
 * @method Generator batchPayment($condition = [], $batchSize = 100)
 * @method array getPayment($data)
 * @method array createPayment($data)
 * @method array updatePayment($data)
 * @method array deletePayment($data)
 *
 * @method array listPaymentProperties($data)
 * @method Generator eachPaymentProperty($condition = [], $batchSize = 100)
 * @method Generator batchPaymentProperty($condition = [], $batchSize = 100)
 * @method array getPaymentProperty($data)
 * @method array createPaymentProperty($data)
 * @method array updatePaymentProperty($data)
 * @method array deletePaymentProperty($data)
 *
 * @method array listListingMarkets($data = [])
 * @method Generator eachListingMarket($condition = [], $batchSize = 100)
 * @method Generator batchListingMarket($condition = [], $batchSize = 100)
 * @method array getListingMarket($data)
 * @method array createListingMarket($data)
 * @method array updateListingMarket($data)
 * @method array deleteListingMarket($data)
 *
 * @method array listListingMarketTexts($data = [])
 * @method Generator eachListingMarketTexts($condition = [], $batchSize = 100)
 * @method Generator batchListingMarketTexts($condition = [], $batchSize = 100)
 * @method array getListingMarketTexts($data)
 * @method array createListingMarketTexts($data)
 * @method array updateListingMarketTexts($data)
 * @method array deleteListingMarketTexts($data)
 *
 * @method array batchRequest($data)
 * @method array searchItemVariations($data)
 * @method array listVariations($data)
 * @method Generator eachVariation($condition = [])
 * @method Generator batchVariation($condition = [])

 * @method array listStocks($data)
 * @method array listTypeStocks($data)
 * @method array listWarehouseStocks($data)
 * @method array listWarehouseLocationStocks($data)
 * @method array listWarehouseStockMovements($data)

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
 * @package lujie\plentyMarkets
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PlentyMarketsRestClient extends OAuth2
{
    use RestClientTrait, PlentyMarketsBatchRestTrait;

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
        'OrderShippingPackage' => 'orders/{orderId}/shipping/packages',
        'OrderShippingPallet' => 'orders/{orderId}/shipping/pallets',
        'OrderShippingPalletPackages' => 'orders/{orderId}/shipping/pallets/{palletId}/packages',
        'OrderShippingPackageItem' => 'orders/shipping/packages', //see extra action
        'Payment' => 'payments',
        'PaymentProperty' => 'payments/{paymentId}/properties',

        'ListingMarket' => 'listings/markets',
        'ListingMarketTexts' => 'listings/markets/texts'
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
            'update' => ['PUT', '{typeId}'],
            'delete' => ['DELETE', '{typeId}'],
        ],
        'OrderShippingPackageItem' => [
            'list' => ['GET', '{packageId}/items'],
            'create' => ['PUT', '{packageId}/items'],
            'update' => ['PUT', 'items/{id}'],
            'delete' => ['DELETE', 'items/{id}'],
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

        'createPaymentOrderRelation' => ['POST', 'payment/{paymentId}/order/{orderId}']
    ];

    /**
     * @var array
     */
    public $httpClientOptions = [
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
    public function reverse($reverse = true): self
    {
        $this->reverse = $reverse;
        return $this;
    }

    #region BaseOAuth

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
        $request->addHeaders(['Authorization' => 'Bearer ' . $accessToken->getToken()]);
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
     * @param string $name
     * @param $data
     * @return array
     * @inheritdoc
     */
    public function restApi(string $name, $data): array
    {
        $response = $this->callApiMethod($name, $data);
        if ($name === 'listWarehouseLocations') {
            array_walk($response['entries'], static function(&$values) use ($data) {
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
            $responseData = $this->restApi($listMethod, $condition);
            $firstPageItems = $responseData['entries'] ?? $responseData;
            $firstPage = $condition['page'] ?? 1;
            $condition['page'] = $responseData['lastPageNumber'] ?? 1;

            while ($condition['page'] > $firstPage) {
                $responseData = $this->restApi($listMethod, $condition);
                $items = $responseData['entries'] ?? $responseData;

                $items = array_reverse($items);
                yield $items;

                $condition['page']--;
            }

            $firstPageItems = array_reverse($firstPageItems);
            yield $firstPageItems;
        } else {
            do {
                $responseData = $this->restApi($listMethod, $condition);
                $items = $responseData['entries'] ?? $responseData;
                yield $items;

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
}

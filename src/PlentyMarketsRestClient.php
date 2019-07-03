<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\plentyMarkets;

use lujie\extend\authclient\RestOAuth2Client;
use Yii;
use yii\authclient\OAuthToken;
use yii\helpers\Inflector;

/**
 * Class PlentyMarketsRestClient
 *
 * @method array listSalesPrices($data = [])
 * @method \Generator eachSalesPrice($batchSize, $condition = [])
 * @method \Generator batchSalesPrice($batchSize, $condition = [])
 * @method array getSalesPrice($data)
 * @method array createSalesPrice($data)
 * @method array updateSalesPrice($data)
 * @method array deleteSalesPrice($data)
 *
 * @method array listAttributes($data = [])
 * @method \Generator eachAttribute($batchSize, $condition = [])
 * @method \Generator batchAttribute($batchSize, $condition = [])
 * @method array getAttribute($data)
 * @method array createAttribute($data)
 * @method array updateAttribute($data)
 * @method array deleteAttribute($data)
 *
 * @method array listAttributeNames($data)
 * @method \Generator eachAttributeName($batchSize, $condition = [])
 * @method \Generator batchAttributeName($batchSize, $condition = [])
 * @method array getAttributeName($data)
 * @method array createAttributeName($data)
 * @method array updateAttributeName($data)
 * @method array deleteAttributeName($data)
 *
 * @method array listAttributeValues($data)
 * @method \Generator eachAttributeValue($batchSize, $condition = [])
 * @method \Generator batchAttributeValue($batchSize, $condition = [])
 * @method array getAttributeValue($data)
 * @method array createAttributeValue($data)
 * @method array updateAttributeValue($data)
 * @method array deleteAttributeValue($data)
 *
 * @method array listAttributeValueNames($data)
 * @method \Generator eachAttributeValueName($batchSize, $condition = [])
 * @method \Generator batchAttributeValueName($batchSize, $condition = [])
 * @method array getAttributeValueName($data)
 * @method array createAttributeValueName($data)
 * @method array updateAttributeValueName($data)
 * @method array deleteAttributeValueName($data)
 *
 * @method array listItems($data = [])
 * @method \Generator eachItem($batchSize, $condition = [])
 * @method \Generator batchItem($batchSize, $condition = [])
 * @method array getItem($data)
 * @method array createItem($data)
 * @method array updateItem($data)
 * @method array deleteItem($data)
 *
 * @method array listItemImages($data)
 * @method \Generator eachItemImage($batchSize, $condition = [])
 * @method \Generator batchItemImage($batchSize, $condition = [])
 * @method array getItemImage($data)
 * @method array createItemImage($data)
 * @method array updateItemImage($data)
 * @method array deleteItemImage($data)
 *
 * @method array listItemImageNames($data)
 * @method \Generator eachItemImageName($batchSize, $condition = [])
 * @method \Generator batchItemImageName($batchSize, $condition = [])
 * @method array getItemImageName($data)
 * @method array createItemImageName($data)
 * @method array updateItemImageName($data)
 * @method array deleteItemImageName($data)
 *
 * @method array listItemImageAvailabilities($data)
 * @method \Generator eachItemImageAvailability($batchSize, $condition = [])
 * @method \Generator batchItemImageAvailability($batchSize, $condition = [])
 * @method array getItemImageAvailability($data)
 * @method array createItemImageAvailability($data)
 * @method array updateItemImageAvailability($data)
 * @method array deleteItemImageAvailability($data)
 *
 * @method array listItemTexts($data)
 * @method \Generator eachItemText($batchSize, $condition = [])
 * @method \Generator batchItemText($batchSize, $condition = [])
 * @method array getItemText($data)
 * @method array createItemText($data)
 * @method array updateItemText($data)
 * @method array deleteItemText($data)
 *
 * @method array listItemProperties($data)
 * @method \Generator eachItemProperty($batchSize, $condition = [])
 * @method \Generator batchItemProperty($batchSize, $condition = [])
 * @method array getItemProperty($data)
 * @method array createItemProperty($data)
 * @method array updateItemProperty($data)
 * @method array deleteItemProperty($data)
 *
 * @method array listItemPropertyTexts($data)
 * @method \Generator eachItemPropertyText($batchSize, $condition = [])
 * @method \Generator batchItemPropertyText($batchSize, $condition = [])
 * @method array getItemPropertyText($data)
 * @method array createItemPropertyText($data)
 * @method array updateItemPropertyText($data)
 * @method array deleteItemPropertyText($data)
 *
 * @method array listItemShippingProfiles($data)
 * @method \Generator eachItemShippingProfile($batchSize, $condition = [])
 * @method \Generator batchItemShippingProfile($batchSize, $condition = [])
 * @method array getItemShippingProfile($data)
 * @method array createItemShippingProfile($data)
 * @method array updateItemShippingProfile($data)
 * @method array deleteItemShippingProfile($data)
 *
 * @method array listItemVariations($data)
 * @method \Generator eachItemVariation($batchSize, $condition = [])
 * @method \Generator batchItemVariation($batchSize, $condition = [])
 * @method array getItemVariation($data)
 * @method array createItemVariation($data)
 * @method array updateItemVariation($data)
 * @method array deleteItemVariation($data)
 *
 * @method array listItemVariationImages($data)
 * @method \Generator eachItemVariationImage($batchSize, $condition = [])
 * @method \Generator batchItemVariationImage($batchSize, $condition = [])
 * @method array getItemVariationImage($data)
 * @method array createItemVariationImage($data)
 * @method array updateItemVariationImage($data)
 * @method array deleteItemVariationImage($data)
 *
 * @method array listItemVariationSalesPrices($data)
 * @method \Generator eachItemVariationSalesPrice($batchSize, $condition = [])
 * @method \Generator batchItemVariationSalesPrice($batchSize, $condition = [])
 * @method array getItemVariationSalesPrice($data)
 * @method array createItemVariationSalesPrice($data)
 * @method array updateItemVariationSalesPrice($data)
 * @method array deleteItemVariationSalesPrice($data)
 *
 * @method array listItemVariationBundles($data)
 * @method \Generator eachItemVariationBundle($batchSize, $condition = [])
 * @method \Generator batchItemVariationBundle($batchSize, $condition = [])
 * @method array getItemVariationBundle($data)
 * @method array createItemVariationBundle($data)
 * @method array updateItemVariationBundle($data)
 * @method array deleteItemVariationBundle($data)
 *
 * @method array listItemVariationMarkets($data)
 * @method \Generator eachItemVariationMarket($batchSize, $condition = [])
 * @method \Generator batchItemVariationMarket($batchSize, $condition = [])
 * @method array getItemVariationMarket($data)
 * @method array createItemVariationMarket($data)
 * @method array updateItemVariationMarket($data)
 * @method array deleteItemVariationMarket($data)
 *
 * @method array listItemVariationSkus($data)
 * @method \Generator eachItemVariationSku($batchSize, $condition = [])
 * @method \Generator batchItemVariationSku($batchSize, $condition = [])
 * @method array getItemVariationSku($data)
 * @method array createItemVariationSku($data)
 * @method array updateItemVariationSku($data)
 * @method array deleteItemVariationSku($data)
 *
 * @method array listItemVariationBarcodes($data)
 * @method \Generator eachItemVariationBarcode($batchSize, $condition = [])
 * @method \Generator batchItemVariationBarcode($batchSize, $condition = [])
 * @method array getItemVariationBarcode($data)
 * @method array createItemVariationBarcode($data)
 * @method array updateItemVariationBarcode($data)
 * @method array deleteItemVariationBarcode($data)
 *
 * @method array listWarehouses($data = [])
 * @method \Generator eachWarehouse($batchSize, $condition = [])
 * @method \Generator batchWarehouse($batchSize, $condition = [])
 * @method array getWarehouse($data)
 * @method array createWarehouse($data)
 * @method array updateWarehouse($data)
 * @method array deleteWarehouse($data)
 *
 * @method array listWarehouseLocationDimensions($data)
 * @method \Generator eachWarehouseLocationDimension($batchSize, $condition = [])
 * @method \Generator batchWarehouseLocationDimension($batchSize, $condition = [])
 * @method array getWarehouseLocationDimension($data)
 * @method array createWarehouseLocationDimension($data)
 * @method array updateWarehouseLocationDimension($data)
 * @method array deleteWarehouseLocationDimension($data)
 *
 * @method array listWarehouseLocationLevels($data)
 * @method \Generator eachWarehouseLocationLevel($batchSize, $condition = [])
 * @method \Generator batchWarehouseLocationLevel($batchSize, $condition = [])
 * @method array getWarehouseLocationLevel($data)
 * @method array createWarehouseLocationLevel($data)
 * @method array updateWarehouseLocationLevel($data)
 * @method array deleteWarehouseLocationLevel($data)
 *
 * @method array listWarehouseLocations($data)
 * @method \Generator eachWarehouseLocation($batchSize, $condition = [])
 * @method \Generator batchWarehouseLocation($batchSize, $condition = [])
 * @method array getWarehouseLocation($data)
 * @method array createWarehouseLocation($data)
 * @method array updateWarehouseLocation($data)
 * @method array deleteWarehouseLocation($data)
 *
 * @method array listCustomers($data = [])
 * @method \Generator eachCustomer($batchSize, $condition = [])
 * @method \Generator batchCustomer($batchSize, $condition = [])
 * @method array getCustomer($data)
 * @method array createCustomer($data)
 * @method array updateCustomer($data)
 * @method array deleteCustomer($data)
 *
 * @method array listAddresses($data = [])
 * @method \Generator eachAddress($batchSize, $condition = [])
 * @method \Generator batchAddress($batchSize, $condition = [])
 * @method array getAddress($data)
 * @method array createAddress($data)
 * @method array updateAddress($data)
 * @method array deleteAddress($data)
 *
 * @method array listCustomerAddresses($data)
 * @method \Generator eachCustomerAddress($batchSize, $condition = [])
 * @method \Generator batchCustomerAddress($batchSize, $condition = [])
 * @method array getCustomerAddress($data)
 * @method array createCustomerAddress($data)
 * @method array updateCustomerAddress($data)
 * @method array deleteCustomerAddress($data)
 *
 * @method array listCustomerBanks($data)
 * @method \Generator eachCustomerBanks($batchSize, $condition = [])
 * @method \Generator batchCustomerBanks($batchSize, $condition = [])
 * @method array getCustomerBanks($data)
 * @method array createCustomerBanks($data)
 * @method array updateCustomerBanks($data)
 * @method array deleteCustomerBanks($data)
 *
 * @method array listOrders($data = [])
 * @method \Generator eachOrder($batchSize, $condition = [])
 * @method \Generator batchOrder($batchSize, $condition = [])
 * @method array getOrder($data)
 * @method array createOrder($data)
 * @method array updateOrder($data)
 * @method array deleteOrder($data)
 * @method array cancelOrder($data)
 *
 * @method array listOrderShippingPackages($data)
 * @method \Generator eachOrderShippingPackage($batchSize, $condition = [])
 * @method \Generator batchOrderShippingPackage($batchSize, $condition = [])
 * @method array getOrderShippingPackage($data)
 * @method array createOrderShippingPackage($data)
 * @method array updateOrderShippingPackage($data)
 * @method array deleteOrderShippingPackage($data)
 *
 * @method array listOrderShippingPallets($data)
 * @method \Generator eachOrderShippingPallet($batchSize, $condition = [])
 * @method \Generator batchOrderShippingPallet($batchSize, $condition = [])
 * @method array getOrderShippingPallet($data)
 * @method array createOrderShippingPallet($data)
 * @method array updateOrderShippingPallet($data)
 * @method array deleteOrderShippingPallet($data)
 *
 * @method array listOrderShippingPalletPackages($data)
 * @method \Generator eachOrderShippingPalletPackages($batchSize, $condition = [])
 * @method \Generator batchOrderShippingPalletPackages($batchSize, $condition = [])
 * @method array getOrderShippingPalletPackages($data)
 * @method array createOrderShippingPalletPackages($data)
 * @method array updateOrderShippingPalletPackages($data)
 * @method array deleteOrderShippingPalletPackages($data)
 *
 * @method array listOrderShippingPackageItems($data)
 * @method \Generator eachOrderShippingPackageItem($batchSize, $condition = [])
 * @method \Generator batchOrderShippingPackageItem($batchSize, $condition = [])
 * @method array getOrderShippingPackageItem($data)
 * @method array createOrderShippingPackageItem($data)
 * @method array updateOrderShippingPackageItem($data)
 * @method array deleteOrderShippingPackageItem($data)
 *
 * @method array listPayments($data = [])
 * @method \Generator eachPayment($batchSize, $condition = [])
 * @method \Generator batchPayment($batchSize, $condition = [])
 * @method array getPayment($data)
 * @method array createPayment($data)
 * @method array updatePayment($data)
 * @method array deletePayment($data)
 *
 * @method array listPaymentProperties($data)
 * @method \Generator eachPaymentProperty($batchSize, $condition = [])
 * @method \Generator batchPaymentProperty($batchSize, $condition = [])
 * @method array getPaymentProperty($data)
 * @method array createPaymentProperty($data)
 * @method array updatePaymentProperty($data)
 * @method array deletePaymentProperty($data)
 *
 * @method array listListingMarkets($data = [])
 * @method \Generator eachListingMarket($batchSize, $condition = [])
 * @method \Generator batchListingMarket($batchSize, $condition = [])
 * @method array getListingMarket($data)
 * @method array createListingMarket($data)
 * @method array updateListingMarket($data)
 * @method array deleteListingMarket($data)
 *
 * @method array listListingMarketTexts($data = [])
 * @method \Generator eachListingMarketTexts($batchSize, $condition = [])
 * @method \Generator batchListingMarketTexts($batchSize, $condition = [])
 * @method array getListingMarketTexts($data)
 * @method array createListingMarketTexts($data)
 * @method array updateListingMarketTexts($data)
 * @method array deleteListingMarketTexts($data)
 *
 * @package lujie\plentyMarkets
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PlentyMarketsRestClient extends RestOAuth2Client
{
    public $tokenUrl = 'login';

    public $refreshTokenUrl = 'login/refresh?refresh_token=';

    public $username;

    public $password;

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
        'OrderShippingPackage' => 'orders/{orderId}/shipping/packages',
        'OrderShippingPallet' => 'orders/{orderId}/shipping/pallets',
        'OrderShippingPalletPackages' => 'orders/{orderId}/shipping/pallets/{palletId}/packages',
        'OrderShippingPackageItem' => 'orders/shipping/packages', //see extra action
        'Payment' => 'payments',
        'PaymentProperty' => 'payments/{paymentId}/properties',

        'ListingMarket' => 'listings/markets',
        'ListingMarketTexts' => 'listings/markets/texts'
    ];

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
        'OrderShippingPackageItem' => [
            'list' => ['GET', '{packageId}/items'],
            'create' => ['PUT', '{packageId}/items'],
            'update' => ['PUT', 'items/{id}'],
            'delete' => ['DELETE', 'items/{id}'],
        ]
    ];

    public $apiMethods = [
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

    #region OAuth2

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
     * @inheritdoc
     */
    public function refreshAccessToken(OAuthToken $token): OAuthToken
    {
        $request = $this->createRequest()
            ->setMethod('POST')
            ->setUrl($this->refreshTokenUrl . $token->getParam('refresh_token'));

        $this->applyClientCredentialsToRequest($request);

        try {
            $response = $this->sendRequest($request);
        } catch (\Exception $e) {
            Yii::warning($e->getMessage(), __METHOD__);
            return null;
        }

        $token = $this->createToken(['params' => $response]);
        $this->setAccessToken($token);

        return $token;
    }

    /**
     * @param \yii\httpclient\Request $request HTTP request instance.
     * @param OAuthToken $accessToken access token instance.
     * @inheritdoc
     */
    public function applyAccessTokenToRequest($request, $accessToken): void
    {
        $request->addHeaders(['Authorization' => 'Bearer ' . $accessToken->getToken()]);
    }

    /**
     * @param \yii\httpclient\Request $request
     * @inheritdoc
     */
    public function applyClientCredentialsToRequest($request): void
    {
    }

    #endregion OAuth2

    /**
     * @param string $resource
     * @param array $condition
     * @param int $batchSize
     * @return \Iterator
     * @throws \yii\web\NotFoundHttpException
     * @inheritdoc
     */
    public function batch(string $resource, array $condition = [],  int $batchSize = 100): \Iterator
    {
        $condition['itemsPerPage'] = $batchSize;
        $listMethod = 'list' . Inflector::pluralize($resource);
        if ($this->reverse) {
            $responseData = $this->callApiMethod($listMethod, $condition);
            $firstPageItems = $responseData['entries'] ?? $responseData;
            $firstPage = $data['page'] ?? 1;
            $data['page'] = $responseData['lastPageNumber'] ?? 1;

            while ($data['page'] > $firstPage) {
                $responseData = $this->callApiMethod($listMethod, $data);
                $items = $responseData['entries'] ?? $responseData;

                $items = array_reverse($items);
                yield $items;

                $data['page']--;
            }

            $firstPageItems = array_reverse($firstPageItems);
            yield $firstPageItems;
        } else {
            do {
                $responseData = $this->callApiMethod($listMethod, $condition);
                $items = $responseData['entries'] ?? $responseData;
                yield $items;

                $pageCount = $responseData['lastPageNumber'] ?? 1;
                $data['page'] = ($data['page'] ?? 1) + ($data['pageStep'] ?? 1);
            } while ($data['page'] <= $pageCount);
        }
    }

}

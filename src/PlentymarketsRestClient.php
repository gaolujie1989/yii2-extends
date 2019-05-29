<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\plentymarkets;

use lujie\extend\authclient\RestOAuth2Client;
use Yii;
use yii\authclient\OAuthToken;
use yii\helpers\Inflector;

/**
 * Class PlentymarketsRestClient
 * @package lujie\plentymarkets
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PlentymarketsRestClient extends RestOAuth2Client
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
        'listStockMovements' => ['GET', 'stockmanagement/warehouses/{warehouseId}/stock/movements'],

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
        $condition['pageSize'] = $batchSize;
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

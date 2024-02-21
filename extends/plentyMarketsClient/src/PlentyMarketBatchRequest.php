<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\plentyMarkets;

use Iterator;
use yii\authclient\InvalidResponseException;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\httpclient\Response;

/**
 * Class PlentyMarketBatchRequest
 *
 * @method array listSalesPrices($data = [])
 * @method array getSalesPrice($data)
 * @method array createSalesPrice($data)
 * @method array updateSalesPrice($data)
 * @method array deleteSalesPrice($data)
 *
 * @method array listAttributes($data = [])
 * @method array getAttribute($data)
 * @method array createAttribute($data)
 * @method array updateAttribute($data)
 * @method array deleteAttribute($data)
 *
 * @method array listAttributeNames($data)
 * @method array getAttributeName($data)
 * @method array createAttributeName($data)
 * @method array updateAttributeName($data)
 * @method array deleteAttributeName($data)
 *
 * @method array listAttributeValues($data)
 * @method array getAttributeValue($data)
 * @method array createAttributeValue($data)
 * @method array updateAttributeValue($data)
 * @method array deleteAttributeValue($data)
 *
 * @method array listAttributeValueNames($data)
 * @method array getAttributeValueName($data)
 * @method array createAttributeValueName($data)
 * @method array updateAttributeValueName($data)
 * @method array deleteAttributeValueName($data)
 *
 * @method array listItems($data = [])
 * @method array getItem($data)
 * @method array createItem($data)
 * @method array updateItem($data)
 * @method array deleteItem($data)
 *
 * @method array listItemImages($data)
 * @method array getItemImage($data)
 * @method array createItemImage($data)
 * @method array updateItemImage($data)
 * @method array deleteItemImage($data)
 *
 * @method array listItemImageNames($data)
 * @method array getItemImageName($data)
 * @method array createItemImageName($data)
 * @method array updateItemImageName($data)
 * @method array deleteItemImageName($data)
 *
 * @method array listItemImageAvailabilities($data)
 * @method array getItemImageAvailability($data)
 * @method array createItemImageAvailability($data)
 * @method array updateItemImageAvailability($data)
 * @method array deleteItemImageAvailability($data)
 *
 * @method array listItemImageAttributeValueMarkets($data)
 * @method array getItemImageAttributeValueMarket($data)
 * @method array createItemImageAttributeValueMarket($data)
 * @method array updateItemImageAttributeValueMarket($data)
 * @method array deleteItemImageAttributeValueMarket($data)
 *
 * @method array listItemTexts($data)
 * @method array getItemText($data)
 * @method array createItemText($data)
 * @method array updateItemText($data)
 * @method array deleteItemText($data)
 *
 * @method array listItemProperties($data)
 * @method array getItemProperty($data)
 * @method array createItemProperty($data)
 * @method array updateItemProperty($data)
 * @method array deleteItemProperty($data)
 *
 * @method array listItemPropertyTexts($data)
 * @method array getItemPropertyText($data)
 * @method array createItemPropertyText($data)
 * @method array updateItemPropertyText($data)
 * @method array deleteItemPropertyText($data)
 *
 * @method array listItemShippingProfiles($data)
 * @method array getItemShippingProfile($data)
 * @method array createItemShippingProfile($data)
 * @method array updateItemShippingProfile($data)
 * @method array deleteItemShippingProfile($data)
 *
 * @method array listItemVariations($data)
 * @method array getItemVariation($data)
 * @method array createItemVariation($data)
 * @method array updateItemVariation($data)
 * @method array deleteItemVariation($data)
 *
 * @method array listItemVariationImages($data)
 * @method array getItemVariationImage($data)
 * @method array createItemVariationImage($data)
 * @method array updateItemVariationImage($data)
 * @method array deleteItemVariationImage($data)
 *
 * @method array listItemVariationSalesPrices($data)
 * @method array getItemVariationSalesPrice($data)
 * @method array createItemVariationSalesPrice($data)
 * @method array updateItemVariationSalesPrice($data)
 * @method array deleteItemVariationSalesPrice($data)
 *
 * @method array listItemVariationBundles($data)
 * @method array getItemVariationBundle($data)
 * @method array createItemVariationBundle($data)
 * @method array updateItemVariationBundle($data)
 * @method array deleteItemVariationBundle($data)
 *
 * @method array listItemVariationClients($data)
 * @method array getItemVariationClient($data)
 * @method array createItemVariationClient($data)
 * @method array updateItemVariationClient($data)
 * @method array deleteItemVariationClient($data)
 *
 * @method array listItemVariationMarkets($data)
 * @method array getItemVariationMarket($data)
 * @method array createItemVariationMarket($data)
 * @method array updateItemVariationMarket($data)
 * @method array deleteItemVariationMarket($data)
 *
 * @method array listItemVariationSkus($data)
 * @method array getItemVariationSku($data)
 * @method array createItemVariationSku($data)
 * @method array updateItemVariationSku($data)
 * @method array deleteItemVariationSku($data)
 *
 * @method array listItemVariationBarcodes($data)
 * @method array getItemVariationBarcode($data)
 * @method array createItemVariationBarcode($data)
 * @method array updateItemVariationBarcode($data)
 * @method array deleteItemVariationBarcode($data)
 *
 * @method array listWarehouses($data = [])
 * @method array getWarehouse($data)
 * @method array createWarehouse($data)
 * @method array updateWarehouse($data)
 * @method array deleteWarehouse($data)
 *
 * @method array listWarehouseLocationDimensions($data)
 * @method array getWarehouseLocationDimension($data)
 * @method array createWarehouseLocationDimension($data)
 * @method array updateWarehouseLocationDimension($data)
 * @method array deleteWarehouseLocationDimension($data)
 *
 * @method array listWarehouseLocationLevels($data)
 * @method array getWarehouseLocationLevel($data)
 * @method array createWarehouseLocationLevel($data)
 * @method array updateWarehouseLocationLevel($data)
 * @method array deleteWarehouseLocationLevel($data)
 *
 * @method array listWarehouseLocations($data)
 * @method array getWarehouseLocation($data)
 * @method array createWarehouseLocation($data)
 * @method array updateWarehouseLocation($data)
 * @method array deleteWarehouseLocation($data)
 *
 * @method array listCustomers($data = [])
 * @method array getCustomer($data)
 * @method array createCustomer($data)
 * @method array updateCustomer($data)
 * @method array deleteCustomer($data)
 *
 * @method array listAddresses($data = [])
 * @method array getAddress($data)
 * @method array createAddress($data)
 * @method array updateAddress($data)
 * @method array deleteAddress($data)
 *
 * @method array listCustomerAddresses($data)
 * @method array getCustomerAddress($data)
 * @method array createCustomerAddress($data)
 * @method array updateCustomerAddress($data)
 * @method array deleteCustomerAddress($data)
 *
 * @method array listCustomerBanks($data)
 * @method array getCustomerBanks($data)
 * @method array createCustomerBanks($data)
 * @method array updateCustomerBanks($data)
 * @method array deleteCustomerBanks($data)
 *
 * @method array listOrders($data = [])
 * @method array getOrder($data)
 * @method array createOrder($data)
 * @method array updateOrder($data)
 * @method array deleteOrder($data)
 * @method array cancelOrder($data)
 *
 * @method array listOrderProperties($data = [])
 * @method array getOrderProperty($data)
 * @method array createOrderProperty($data)
 * @method array updateOrderProperty($data)
 * @method array deleteOrderProperty($data)
 * @method array cancelOrderProperty($data)
 *
 * @method array listOrderPropertyTypes($data = [])
 * @method array getOrderPropertyType($data)
 * @method array createOrderPropertyType($data)
 * @method array updateOrderPropertyType($data)
 * @method array deleteOrderPropertyType($data)
 * @method array cancelOrderPropertyType($data)
 *
 * @method array listOrderShippingPackages($data)
 * @method array getOrderShippingPackage($data)
 * @method array createOrderShippingPackage($data)
 * @method array updateOrderShippingPackage($data)
 * @method array deleteOrderShippingPackage($data)
 *
 * @method array listOrderShippingPallets($data)
 * @method array getOrderShippingPallet($data)
 * @method array createOrderShippingPallet($data)
 * @method array updateOrderShippingPallet($data)
 * @method array deleteOrderShippingPallet($data)
 *
 * @method array listOrderShippingPalletPackages($data)
 * @method array getOrderShippingPalletPackages($data)
 * @method array createOrderShippingPalletPackages($data)
 * @method array updateOrderShippingPalletPackages($data)
 * @method array deleteOrderShippingPalletPackages($data)
 *
 * @method array listOrderShippingPackageItems($data)
 * @method array getOrderShippingPackageItem($data)
 * @method array createOrderShippingPackageItem($data)
 * @method array updateOrderShippingPackageItem($data)
 * @method array deleteOrderShippingPackageItem($data)
 *
 * @method array deleteOrderItem($data)
 *
 * @method array listOrderItemProperties($data)
 * @method array getOrderItemProperty($data)
 * @method array createOrderItemProperty($data)
 * @method array updateOrderItemProperty($data)
 * @method array deleteOrderItemProperty($data)
 *
 * @method array listOrderItemTransactions($data)
 * @method array getOrderItemTransaction($data)
 * @method array createOrderItemTransaction($data)
 * @method array updateOrderItemTransaction($data)
 * @method array deleteOrderItemTransaction($data)
 *
 * @method array listPayments($data = [])
 * @method array getPayment($data)
 * @method array createPayment($data)
 * @method array updatePayment($data)
 * @method array deletePayment($data)
 *
 * @method array listPaymentProperties($data)
 * @method array getPaymentProperty($data)
 * @method array createPaymentProperty($data)
 * @method array updatePaymentProperty($data)
 * @method array deletePaymentProperty($data)
 *
 * @method array listListings($data = [])
 * @method array getListing($data)
 * @method array createListing($data)
 * @method array updateListing($data)
 * @method array deleteListing($data)
 *
 * @method array listListingMarkets($data = [])
 * @method array getListingMarket($data)
 * @method array createListingMarket($data)
 * @method array updateListingMarket($data)
 * @method array deleteListingMarket($data)
 *
 * @method array listListingMarketHistories($data = [])
 * @method array getListingMarketHistory($data)
 * @method array relistListingMarketHistory($data)
 * @method array updateListingMarketHistory($data)
 * @method array endListingMarketHistory($data)
 *
 * @method array listListingMarketTexts($data = [])
 * @method array getListingMarketTexts($data)
 * @method array createListingMarketTexts($data)
 * @method array updateListingMarketTexts($data)
 * @method array deleteListingMarketTexts($data)
 *
 * @method array listComments($data = [])
 * @method array getComment($data)
 * @method array createComment($data)
 * @method array updateComment($data)
 * @method array deleteComment($data)
 *
 * @method array batchRequest($data)
 * @method array searchItemVariations($data)
 * @method array listVariations($data)
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
class PlentyMarketBatchRequest extends BaseObject
{
    /**
     * @var array
     */
    private $payloads = [];

    /**
     * @var PlentyMarketsRestClient
     */
    public $client;

    /**
     * @param string $name
     * @param array $params
     * @return mixed|void
     * @inheritdoc
     */
    public function __call($name, $params)
    {
        if (isset($this->client->apiMethods[$name])) {
            $data = $params[0] ?? [];
            [$method, $resource] = $this->client->apiMethods[$name];
            $method = strtoupper($method);
            $url = $this->client->getRealPath($resource, $data);
            if ($method === 'GET' && $data) {
                $url .= ((strpos($url, '?') === false) ? '?' : '&') . http_build_query($data);
                $data = [];
            }
            $this->payloads[] = [
                'resource' => 'rest/' . $url,
                'method' => $method,
                'body' => $data
            ];
            return $this;
        }
        parent::__call($name, $params);
    }

    /**
     * @return array
     * @throws InvalidResponseException
     */
    public function send(): array
    {
        if (empty($this->payloads)) {
            return [];
        }
        $chunkedPayloads = array_chunk($this->payloads, 20);
        $chunkedResponses = [];
        foreach ($chunkedPayloads as $payloads) {
            $batchResponse = $this->client->batchRequest(['payloads' => $payloads]);
            foreach ($batchResponse as $key => $response) {
                $batchResponse[$key]['content'] = Json::decode($response['content']);
            }
            $requestKeyFunc = static function ($response) {
                return $response['method'] . ' ' . $response['resource'];
            };
            $errors = ArrayHelper::map($batchResponse, $requestKeyFunc, 'content.error.message');
            $errors = array_unique(array_filter($errors));
            if ($errors) {
                $invalidResponse = new Response();
                $invalidResponse->setHeaders(['http-code' => '422']);
                $invalidResponse->setData($batchResponse);
                throw new InvalidResponseException($invalidResponse, 'Batch request with errors: ' . Json::encode($errors));
            }
            $chunkedResponses[] = $batchResponse;
        }
        return array_merge(...$chunkedResponses);
    }
}

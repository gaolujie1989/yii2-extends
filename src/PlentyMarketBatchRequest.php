<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\plentyMarkets;

use yii\base\BaseObject;
use yii\helpers\Json;

/**
 * Class PlentyMarketBatchRequest
 *
 * @method PlentyMarketBatchRequest listSalesPrices($data = [])
 * @method PlentyMarketBatchRequest getSalesPrice($data)
 * @method PlentyMarketBatchRequest createSalesPrice($data)
 * @method PlentyMarketBatchRequest updateSalesPrice($data)
 * @method PlentyMarketBatchRequest deleteSalesPrice($data)
 *
 * @method PlentyMarketBatchRequest listAttributes($data = [])
 * @method PlentyMarketBatchRequest getAttribute($data)
 * @method PlentyMarketBatchRequest createAttribute($data)
 * @method PlentyMarketBatchRequest updateAttribute($data)
 * @method PlentyMarketBatchRequest deleteAttribute($data)
 *
 * @method PlentyMarketBatchRequest listAttributeNames($data)
 * @method PlentyMarketBatchRequest getAttributeName($data)
 * @method PlentyMarketBatchRequest createAttributeName($data)
 * @method PlentyMarketBatchRequest updateAttributeName($data)
 * @method PlentyMarketBatchRequest deleteAttributeName($data)
 *
 * @method PlentyMarketBatchRequest listAttributeValues($data)
 * @method PlentyMarketBatchRequest getAttributeValue($data)
 * @method PlentyMarketBatchRequest createAttributeValue($data)
 * @method PlentyMarketBatchRequest updateAttributeValue($data)
 * @method PlentyMarketBatchRequest deleteAttributeValue($data)
 *
 * @method PlentyMarketBatchRequest listAttributeValueNames($data)
 * @method PlentyMarketBatchRequest getAttributeValueName($data)
 * @method PlentyMarketBatchRequest createAttributeValueName($data)
 * @method PlentyMarketBatchRequest updateAttributeValueName($data)
 * @method PlentyMarketBatchRequest deleteAttributeValueName($data)
 *
 * @method PlentyMarketBatchRequest listItems($data = [])
 * @method PlentyMarketBatchRequest getItem($data)
 * @method PlentyMarketBatchRequest createItem($data)
 * @method PlentyMarketBatchRequest updateItem($data)
 * @method PlentyMarketBatchRequest deleteItem($data)
 *
 * @method PlentyMarketBatchRequest listItemImages($data)
 * @method PlentyMarketBatchRequest getItemImage($data)
 * @method PlentyMarketBatchRequest createItemImage($data)
 * @method PlentyMarketBatchRequest updateItemImage($data)
 * @method PlentyMarketBatchRequest deleteItemImage($data)
 *
 * @method PlentyMarketBatchRequest listItemImageNames($data)
 * @method PlentyMarketBatchRequest getItemImageName($data)
 * @method PlentyMarketBatchRequest createItemImageName($data)
 * @method PlentyMarketBatchRequest updateItemImageName($data)
 * @method PlentyMarketBatchRequest deleteItemImageName($data)
 *
 * @method PlentyMarketBatchRequest listItemImageAvailabilities($data)
 * @method PlentyMarketBatchRequest getItemImageAvailability($data)
 * @method PlentyMarketBatchRequest createItemImageAvailability($data)
 * @method PlentyMarketBatchRequest updateItemImageAvailability($data)
 * @method PlentyMarketBatchRequest deleteItemImageAvailability($data)
 *
 * @method PlentyMarketBatchRequest listItemTexts($data)
 * @method PlentyMarketBatchRequest getItemText($data)
 * @method PlentyMarketBatchRequest createItemText($data)
 * @method PlentyMarketBatchRequest updateItemText($data)
 * @method PlentyMarketBatchRequest deleteItemText($data)
 *
 * @method PlentyMarketBatchRequest listItemProperties($data)
 * @method PlentyMarketBatchRequest getItemProperty($data)
 * @method PlentyMarketBatchRequest createItemProperty($data)
 * @method PlentyMarketBatchRequest updateItemProperty($data)
 * @method PlentyMarketBatchRequest deleteItemProperty($data)
 *
 * @method PlentyMarketBatchRequest listItemPropertyTexts($data)
 * @method PlentyMarketBatchRequest getItemPropertyText($data)
 * @method PlentyMarketBatchRequest createItemPropertyText($data)
 * @method PlentyMarketBatchRequest updateItemPropertyText($data)
 * @method PlentyMarketBatchRequest deleteItemPropertyText($data)
 *
 * @method PlentyMarketBatchRequest listItemShippingProfiles($data)
 * @method PlentyMarketBatchRequest getItemShippingProfile($data)
 * @method PlentyMarketBatchRequest createItemShippingProfile($data)
 * @method PlentyMarketBatchRequest updateItemShippingProfile($data)
 * @method PlentyMarketBatchRequest deleteItemShippingProfile($data)
 *
 * @method PlentyMarketBatchRequest listItemVariations($data)
 * @method PlentyMarketBatchRequest getItemVariation($data)
 * @method PlentyMarketBatchRequest createItemVariation($data)
 * @method PlentyMarketBatchRequest updateItemVariation($data)
 * @method PlentyMarketBatchRequest deleteItemVariation($data)
 *
 * @method PlentyMarketBatchRequest listItemVariationImages($data)
 * @method PlentyMarketBatchRequest getItemVariationImage($data)
 * @method PlentyMarketBatchRequest createItemVariationImage($data)
 * @method PlentyMarketBatchRequest updateItemVariationImage($data)
 * @method PlentyMarketBatchRequest deleteItemVariationImage($data)
 *
 * @method PlentyMarketBatchRequest listItemVariationSalesPrices($data)
 * @method PlentyMarketBatchRequest getItemVariationSalesPrice($data)
 * @method PlentyMarketBatchRequest createItemVariationSalesPrice($data)
 * @method PlentyMarketBatchRequest updateItemVariationSalesPrice($data)
 * @method PlentyMarketBatchRequest deleteItemVariationSalesPrice($data)
 *
 * @method PlentyMarketBatchRequest listItemVariationBundles($data)
 * @method PlentyMarketBatchRequest getItemVariationBundle($data)
 * @method PlentyMarketBatchRequest createItemVariationBundle($data)
 * @method PlentyMarketBatchRequest updateItemVariationBundle($data)
 * @method PlentyMarketBatchRequest deleteItemVariationBundle($data)
 *
 * @method PlentyMarketBatchRequest listItemVariationMarkets($data)
 * @method PlentyMarketBatchRequest getItemVariationMarket($data)
 * @method PlentyMarketBatchRequest createItemVariationMarket($data)
 * @method PlentyMarketBatchRequest updateItemVariationMarket($data)
 * @method PlentyMarketBatchRequest deleteItemVariationMarket($data)
 *
 * @method PlentyMarketBatchRequest listItemVariationSkus($data)
 * @method PlentyMarketBatchRequest getItemVariationSku($data)
 * @method PlentyMarketBatchRequest createItemVariationSku($data)
 * @method PlentyMarketBatchRequest updateItemVariationSku($data)
 * @method PlentyMarketBatchRequest deleteItemVariationSku($data)
 *
 * @method PlentyMarketBatchRequest listItemVariationBarcodes($data)
 * @method PlentyMarketBatchRequest getItemVariationBarcode($data)
 * @method PlentyMarketBatchRequest createItemVariationBarcode($data)
 * @method PlentyMarketBatchRequest updateItemVariationBarcode($data)
 * @method PlentyMarketBatchRequest deleteItemVariationBarcode($data)
 *
 * @method PlentyMarketBatchRequest listWarehouses($data = [])
 * @method PlentyMarketBatchRequest getWarehouse($data)
 * @method PlentyMarketBatchRequest createWarehouse($data)
 * @method PlentyMarketBatchRequest updateWarehouse($data)
 * @method PlentyMarketBatchRequest deleteWarehouse($data)
 *
 * @method PlentyMarketBatchRequest listWarehouseLocationDimensions($data)
 * @method PlentyMarketBatchRequest getWarehouseLocationDimension($data)
 * @method PlentyMarketBatchRequest createWarehouseLocationDimension($data)
 * @method PlentyMarketBatchRequest updateWarehouseLocationDimension($data)
 * @method PlentyMarketBatchRequest deleteWarehouseLocationDimension($data)
 *
 * @method PlentyMarketBatchRequest listWarehouseLocationLevels($data)
 * @method PlentyMarketBatchRequest getWarehouseLocationLevel($data)
 * @method PlentyMarketBatchRequest createWarehouseLocationLevel($data)
 * @method PlentyMarketBatchRequest updateWarehouseLocationLevel($data)
 * @method PlentyMarketBatchRequest deleteWarehouseLocationLevel($data)
 *
 * @method PlentyMarketBatchRequest listWarehouseLocations($data)
 * @method PlentyMarketBatchRequest getWarehouseLocation($data)
 * @method PlentyMarketBatchRequest createWarehouseLocation($data)
 * @method PlentyMarketBatchRequest updateWarehouseLocation($data)
 * @method PlentyMarketBatchRequest deleteWarehouseLocation($data)
 *
 * @method PlentyMarketBatchRequest listCustomers($data = [])
 * @method PlentyMarketBatchRequest getCustomer($data)
 * @method PlentyMarketBatchRequest createCustomer($data)
 * @method PlentyMarketBatchRequest updateCustomer($data)
 * @method PlentyMarketBatchRequest deleteCustomer($data)
 *
 * @method PlentyMarketBatchRequest listAddresses($data = [])
 * @method PlentyMarketBatchRequest getAddress($data)
 * @method PlentyMarketBatchRequest createAddress($data)
 * @method PlentyMarketBatchRequest updateAddress($data)
 * @method PlentyMarketBatchRequest deleteAddress($data)
 *
 * @method PlentyMarketBatchRequest listCustomerAddresses($data)
 * @method PlentyMarketBatchRequest getCustomerAddress($data)
 * @method PlentyMarketBatchRequest createCustomerAddress($data)
 * @method PlentyMarketBatchRequest updateCustomerAddress($data)
 * @method PlentyMarketBatchRequest deleteCustomerAddress($data)
 *
 * @method PlentyMarketBatchRequest listCustomerBanks($data)
 * @method PlentyMarketBatchRequest getCustomerBanks($data)
 * @method PlentyMarketBatchRequest createCustomerBanks($data)
 * @method PlentyMarketBatchRequest updateCustomerBanks($data)
 * @method PlentyMarketBatchRequest deleteCustomerBanks($data)
 *
 * @method PlentyMarketBatchRequest listOrders($data = [])
 * @method PlentyMarketBatchRequest getOrder($data)
 * @method PlentyMarketBatchRequest createOrder($data)
 * @method PlentyMarketBatchRequest updateOrder($data)
 * @method PlentyMarketBatchRequest deleteOrder($data)
 * @method PlentyMarketBatchRequest cancelOrder($data)
 *
 * @method PlentyMarketBatchRequest listOrderShippingPackages($data)
 * @method PlentyMarketBatchRequest getOrderShippingPackage($data)
 * @method PlentyMarketBatchRequest createOrderShippingPackage($data)
 * @method PlentyMarketBatchRequest updateOrderShippingPackage($data)
 * @method PlentyMarketBatchRequest deleteOrderShippingPackage($data)
 *
 * @method PlentyMarketBatchRequest listOrderShippingPallets($data)
 * @method PlentyMarketBatchRequest getOrderShippingPallet($data)
 * @method PlentyMarketBatchRequest createOrderShippingPallet($data)
 * @method PlentyMarketBatchRequest updateOrderShippingPallet($data)
 * @method PlentyMarketBatchRequest deleteOrderShippingPallet($data)
 *
 * @method PlentyMarketBatchRequest listOrderShippingPalletPackages($data)
 * @method PlentyMarketBatchRequest getOrderShippingPalletPackages($data)
 * @method PlentyMarketBatchRequest createOrderShippingPalletPackages($data)
 * @method PlentyMarketBatchRequest updateOrderShippingPalletPackages($data)
 * @method PlentyMarketBatchRequest deleteOrderShippingPalletPackages($data)
 *
 * @method PlentyMarketBatchRequest listOrderShippingPackageItems($data)
 * @method PlentyMarketBatchRequest getOrderShippingPackageItem($data)
 * @method PlentyMarketBatchRequest createOrderShippingPackageItem($data)
 * @method PlentyMarketBatchRequest updateOrderShippingPackageItem($data)
 * @method PlentyMarketBatchRequest deleteOrderShippingPackageItem($data)
 *
 * @method PlentyMarketBatchRequest listPayments($data = [])
 * @method PlentyMarketBatchRequest getPayment($data)
 * @method PlentyMarketBatchRequest createPayment($data)
 * @method PlentyMarketBatchRequest updatePayment($data)
 * @method PlentyMarketBatchRequest deletePayment($data)
 *
 * @method PlentyMarketBatchRequest listPaymentProperties($data)
 * @method PlentyMarketBatchRequest getPaymentProperty($data)
 * @method PlentyMarketBatchRequest createPaymentProperty($data)
 * @method PlentyMarketBatchRequest updatePaymentProperty($data)
 * @method PlentyMarketBatchRequest deletePaymentProperty($data)
 *
 * @method PlentyMarketBatchRequest listListingMarkets($data = [])
 * @method PlentyMarketBatchRequest getListingMarket($data)
 * @method PlentyMarketBatchRequest createListingMarket($data)
 * @method PlentyMarketBatchRequest updateListingMarket($data)
 * @method PlentyMarketBatchRequest deleteListingMarket($data)
 *
 * @method PlentyMarketBatchRequest listListingMarketTexts($data = [])
 * @method PlentyMarketBatchRequest getListingMarketTexts($data)
 * @method PlentyMarketBatchRequest createListingMarketTexts($data)
 * @method PlentyMarketBatchRequest updateListingMarketTexts($data)
 * @method PlentyMarketBatchRequest deleteListingMarketTexts($data)
 *
 * @method PlentyMarketBatchRequest batchRequest($data)
 * @method PlentyMarketBatchRequest searchItemVariations($data)
 * @method PlentyMarketBatchRequest listVariations($data)

 * @method PlentyMarketBatchRequest listStocks($data)
 * @method PlentyMarketBatchRequest listTypeStocks($data)
 * @method PlentyMarketBatchRequest listWarehouseStocks($data)
 * @method PlentyMarketBatchRequest listWarehouseLocationStocks($data)
 * @method PlentyMarketBatchRequest listWarehouseStockMovements($data)

 * @method PlentyMarketBatchRequest correctStock($data)
 * @method PlentyMarketBatchRequest bookIncomingStock($data)
 * @method PlentyMarketBatchRequest redistributeStock($data)

 * @method PlentyMarketBatchRequest bookOrderOutgoingStocks($data)
 * @method PlentyMarketBatchRequest revertOrderOutgoingStocks($data)

 * @method PlentyMarketBatchRequest listOrderDates($data)
 * @method PlentyMarketBatchRequest listOrderContracts($data)
 * @method PlentyMarketBatchRequest getOrderPackageNumbers($data)
 * @method PlentyMarketBatchRequest getOrderShippingInformation($data)
 * @method PlentyMarketBatchRequest getOrderShippingPackageItems($data)
 * @method PlentyMarketBatchRequest getOrderShippingPackagePackedItems($data)
 * @method PlentyMarketBatchRequest getOrderShippingPackageUnpackedItems($data)
 * @method PlentyMarketBatchRequest updateShippingPackageItemByUnionId($data)
 * @method PlentyMarketBatchRequest deleteShippingPackageItemByUnionId($data)

 * @method PlentyMarketBatchRequest listOrderPayments($data)
 * @method PlentyMarketBatchRequest listPaymentsByProperty($data)
 * @method PlentyMarketBatchRequest listPaymentsByTransactionId($data)

 * @method PlentyMarketBatchRequest createPaymentOrderRelation($data)
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
     * @inheritdoc
     */
    public function send(): array
    {
        if (empty($this->payloads)) {
            return [];
        }
        $batchResponse = $this->client->batchRequest(['payloads' => $this->payloads]);
        foreach ($batchResponse as $key => $response) {
            $batchResponse[$key]['content'] = Json::decode($response['content']);
        }
        return $batchResponse;
    }
}

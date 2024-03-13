<?php

namespace lujie\amazon\sp\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description This section provides APIs for selling partners to work with External Fulfillment inventory services.
*/
class ExternalFulfillmentInventory20210106 extends \lujie\amazon\sp\BaseAmazonSPClient
{

                
    /**
     * @description Get the current inventory for a given SKU at a given location.
     * @tag 
     * @param String $locationId String
     * @param String $skuId String
     * @return array
     *      - *sellableQuantity* - integer
     *          - The number of items of the specified SKU that are available for being purchased.
     *      - *reservedQuantity* - integer
     *          - The number of items of the specified SKU that have been reserved for shipment dropped from any marketplace which are yet to be fulfilled.
     *      - *marketplaceChannelInventories* - array
     *          - Provides a break-up of how many items of the specified SKU are available in different channels.
     */
    public function getInventory(String $locationId, String $skuId): array
    {
        return $this->api("/externalFulfillment/inventory/2021-01-06/locations/{$locationId}/skus/{$skuId}");
    }
                
    /**
     * @description Update the inventory quantity of the given SKU in the specified location to the provided value across all channel where listing exists.
     * @tag 
     * @param String $locationId String
     * @param String $skuId String
     * @param array $query
     *      - *quantity* - Integer - required
     *          - Integer
     * @return array
     *      - *sellableQuantity* - integer
     *          - The number of items of the specified SKU that are available for being purchased.
     *      - *reservedQuantity* - integer
     *          - The number of items of the specified SKU that have been reserved for shipment dropped from any marketplace which are yet to be fulfilled.
     *      - *marketplaceChannelInventories* - array
     *          - Provides a break-up of how many items of the specified SKU are available in different channels.
     */
    public function updateInventory(String $locationId, String $skuId, array $query): array
    {
        return $this->api(array_merge(["/externalFulfillment/inventory/2021-01-06/locations/{$locationId}/skus/{$skuId}"], $query), 'PUT');
    }
    
}

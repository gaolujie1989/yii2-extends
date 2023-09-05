<?php

namespace lujie\plentyMarkets\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The plentymarkets REST API expands the functionality of the plentymarkets CMS and allows access to resources, i.e. data records, via unique URI paths
*/
class Fulfillment extends \lujie\plentyMarkets\BasePlentyMarketsRestClient
{

                
    /**
     * @description Get all pick lists for the given filters.
     * @tag Fulfillment

     */
    public function getFulfillmentPicklist(): void
    {
        $this->api("/rest/fulfillment/picklist");
    }
                    
    /**
     * @description Gets a collection of pick list items.
     * @tag Fulfillment
     * @return array
     */
    public function getFulfillmentPicklistPickingOrderItem(): array
    {
        return $this->api("/rest/fulfillment/picklist/picking_order_item");
    }
                    
    /**
     * @description Gets a pick list item specified by its pickingOrderItemId.
     * @tag Fulfillment
     * @param int $pickingOrderItemId The PickingOrderItemId
     * @return array
     *      - *id* - integer
     *      - *pickingOrderId* - integer
     *      - *orderItemId* - integer
     *      - *processState* - string
     *      - *processDate* - string
     *      - *processUserId* - integer
     *      - *comment* - string
     *      - *quantity* - number
     *      - *itemId* - integer
     *      - *holdingArea* - integer
     *      - *warehouseId* - integer
     *      - *orderIdList* - string
     */
    public function getFulfillmentPicklistPickingOrderItemByPickingOrderItemId(int $pickingOrderItemId): array
    {
        return $this->api("/rest/fulfillment/picklist/picking_order_item/{$pickingOrderItemId}");
    }
                    
    /**
     * @description Set state of a pick list item specified by its pickingOrderItemId.
     * @tag Fulfillment
     * @param int $pickingOrderItemId The pick list item id
     * @param array $query
     *      - *state* - string - required
     *          - The state
     * @return array
     *      - *id* - integer
     *      - *pickingOrderId* - integer
     *      - *orderItemId* - integer
     *      - *processState* - string
     *      - *processDate* - string
     *      - *processUserId* - integer
     *      - *comment* - string
     *      - *quantity* - number
     *      - *itemId* - integer
     *      - *holdingArea* - integer
     *      - *warehouseId* - integer
     *      - *orderIdList* - string
     */
    public function createFulfillmentPicklistPickingOrderItemStatusByPickingOrderItemId(int $pickingOrderItemId, array $query): array
    {
        return $this->api(array_merge(["/rest/fulfillment/picklist/picking_order_item/{$pickingOrderItemId}/status"], $query), 'POST');
    }
                    
    /**
     * @description Create an trolley tag for a pick list.
     * @tag Fulfillment

     */
    public function createFulfillmentPicklistTrolleyTag(): void
    {
        $this->api("/rest/fulfillment/picklist/trolley_tags", 'POST');
    }
                    
    /**
     * @description Delete an trolley tag for a pick list.
     * @tag Fulfillment
     * @param string $trolleyTag The trolley tag for the pick list
     */
    public function deleteFulfillmentPicklistTrolleyTagByTrolleyTag(string $trolleyTag): void
    {
        $this->api("/rest/fulfillment/picklist/trolley_tags/{$trolleyTag}", 'DELETE');
    }
                
    /**
     * @description Get a pick list specified by its pick list alias.
     * @tag Fulfillment
     * @param int $trolleyTag 
     * @return array
     *      - *id* - integer
     *      - *createdAt* - string
     *      - *processDate* - string
     *      - *doneDate* - string
     *      - *ownerId* - integer
     *      - *processUserId* - integer
     *      - *processState* - string
     *      - *webstoreId* - integer
     *      - *warehouseId* - integer
     *      - *comment* - string
     *      - *filterOptions* - array
     */
    public function getFulfillmentPicklistTrolleyTagByTrolleyTag(int $trolleyTag): array
    {
        return $this->api("/rest/fulfillment/picklist/trolley_tags/{$trolleyTag}");
    }
                    
    /**
     * @description Get a pick list specified by its pick list ID.
     * @tag Fulfillment
     * @param int $id 
     * @return array
     *      - *id* - integer
     *      - *createdAt* - string
     *      - *processDate* - string
     *      - *doneDate* - string
     *      - *ownerId* - integer
     *      - *processUserId* - integer
     *      - *processState* - string
     *      - *webstoreId* - integer
     *      - *warehouseId* - integer
     *      - *comment* - string
     *      - *filterOptions* - array
     */
    public function getFulfillmentPicklistById(int $id): array
    {
        return $this->api("/rest/fulfillment/picklist/{$id}");
    }
                    
    /**
     * @description Execute an action for the given pick list.
     * @tag Fulfillment
     * @param int $id The pick list ID
     * @param string $action The action to execute (start, continue, reopen, close, pause) 
     * @return array
     *      - *id* - integer
     *      - *createdAt* - string
     *      - *processDate* - string
     *      - *doneDate* - string
     *      - *ownerId* - integer
     *      - *processUserId* - integer
     *      - *processState* - string
     *      - *webstoreId* - integer
     *      - *warehouseId* - integer
     *      - *comment* - string
     *      - *filterOptions* - array
     */
    public function createFulfillmentPicklistByIdAction(int $id, string $action): array
    {
        return $this->api("/rest/fulfillment/picklist/{$id}/{$action}", 'POST');
    }
                    
    /**
     * @description Delete a trolley tag by picking order id.
     * @tag Fulfillment
     * @param int $pickingOrderId 
     */
    public function deleteFulfillmentPicklistTrolleyTagByPickingOrderId(int $pickingOrderId): void
    {
        $this->api("/rest/fulfillment/picklist/{$pickingOrderId}/trolley_tags", 'DELETE');
    }
    
}

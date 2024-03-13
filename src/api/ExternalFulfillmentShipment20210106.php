<?php

namespace lujie\amazon\sp\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description This section provides APIs for selling partners to work with Amazon External Fulfillment shipments management/processing services.
*/
class ExternalFulfillmentShipment20210106 extends \lujie\amazon\sp\BaseAmazonSPClient
{

                
    /**
     * @description Provides details about the packages that will be used to fulfill the specified shipment.
     * @tag 
     * @param String $shipmentId String
     * @param array $data 
     * @return array
     *      - *errors* - array
     */
    public function createPackages(String $shipmentId, array $data): array
    {
        return $this->api("/externalFulfillment/shipments/2021-01-06/shipments/{$shipmentId}/packages", 'POST', $data);
    }
                    
    /**
     * @description Generates and retrieves the invoice for the specified shipment and package.
     * @tag 
     * @param String $shipmentId String
     * @param String $packageId String
     * @return array
     *      - *document* - 
     *          - The invoice content.
     */
    public function generateInvoice(String $shipmentId, String $packageId): array
    {
        return $this->api("/externalFulfillment/shipments/2021-01-06/shipments/{$shipmentId}/packages/{$packageId}/invoice", 'POST');
    }
                
    /**
     * @description Retrieves invoice for the specified shipment.
     * @tag 
     * @param String $shipmentId String
     * @param String $packageId String
     * @return array
     *      - *document* - 
     *          - The invoice content.
     */
    public function retrieveInvoice(String $shipmentId, String $packageId): array
    {
        return $this->api("/externalFulfillment/shipments/2021-01-06/shipments/{$shipmentId}/packages/{$packageId}/invoice");
    }
                    
    /**
     * @description Generates and retrieves a ship-label for the specified package in the specified shipment.
     * @tag 
     * @param String $shipmentId String
     * @param String $packageId String
     * @param array $query
     *      - *shippingOptionId* - String - optional
     *          - String
     *      - *operation* - String - required
     *          - String
     * @param array $data 
     * @return array
     *      - *document* - 
     *          - The ship label content.
     *      - *metadata* - 
     *          - Contains Metadata about the ship label document.
     */
    public function generateShipLabel(String $shipmentId, String $packageId, array $query, array $data): array
    {
        return $this->api(array_merge(["/externalFulfillment/shipments/2021-01-06/shipments/{$shipmentId}/packages/{$packageId}/shipLabel"], $query), 'POST', $data);
    }
                
    /**
     * @description retrieves a ship-label for the specified package in the specified shipment.
     * @tag 
     * @param String $shipmentId String
     * @param String $packageId String
     * @return array
     *      - *document* - 
     *          - The ship label content.
     *      - *metadata* - 
     *          - Contains Metadata about the ship label document.
     */
    public function retrieveShipLabel(String $shipmentId, String $packageId): array
    {
        return $this->api("/externalFulfillment/shipments/2021-01-06/shipments/{$shipmentId}/packages/{$packageId}/shipLabel");
    }
                    
    /**
     * @description Confirms/Rejects that a seller will be fulfilling or cancelling the specified shipment.
     * @tag 
     * @param String $shipmentId String
     * @param array $query
     *      - *operation* - String - required
     *          - String
     * @param array $data 
     * @return array
     *      - *errors* - array
     */
    public function processShipment(String $shipmentId, array $query, array $data): array
    {
        return $this->api(array_merge(["/externalFulfillment/shipments/2021-01-06/shipments/{$shipmentId}"], $query), 'POST', $data);
    }
                
    /**
     * @description Get a single shipment with the specified id.
     * @tag 
     * @param String $shipmentId String
     * @return array
     *      - *id* - string
     *          - The shipment's id.
     *      - *locationId* - string
     *          - The SmartConnect identifier for location to which shipment has been dropped for fulfillment.
     *      - *channelName* - string
     *          - The name of marketplace channel from which shipment has been dropped for fulfillment.
     *      - *channelLocationId* - string
     *          - The location identifier of Seller's location in marketplace channel to which shipment has been dropped for fulfillment.
     *      - *metadata* - 
     *      - *charges* - 
     *          - The charges associated with the shipment. The charge amount does not include the tax amount.
     *      - *status* - string
     *          - The current status of the shipment.
     *      - *lineItems* - array
     *          - The line items in the shipment.
     *      - *shippingInfo* - 
     *      - *packages* - array
     *          - The list of packages and information about each package that will be used to fulfill this shipment.
     *      - *creationDateTime* - 
     *          - The date/time at which the shipment was created.
     *      - *lastUpdatedDateTime* - 
     *          - The date/time at which the shipment was last updated.
     */
    public function getShipment(String $shipmentId): array
    {
        return $this->api("/externalFulfillment/shipments/2021-01-06/shipments/{$shipmentId}");
    }
                    
    /**
     * @description An API for a client to retrieve an optional list of shippingOptions that marketplace/channel provides for the pickup of the packages of an shipment. This API will return a list of shippingOptions if the marketplace/channel provides transportation and allows the seller to choose a shippingOption. If the marketplace/channel does not allow for a shippingOption to be selected, but has a pre-determined shippingOption, then this API will return an empty response.
     * @tag 
     * @param array $query
     *      - *shipmentId* - String - required
     *          - String
     *      - *packageId* - String - required
     *          - String
     * @return array
     *      - *shippingOptions* - array
     *          - The list of shipping options in the response.
     */
    public function retrieveShippingOptions(array $query): array
    {
        return $this->api(array_merge(["/externalFulfillment/shipments/2021-01-06/shippingOptions"], $query));
    }
                    
    /**
     * @description Updates the details about the packages that will be used to fulfill the specified shipment.
     * @tag 
     * @param String $shipmentId String
     * @param String $packageId String
     * @param array $data 
     * @return array
     *      - *errors* - array
     */
    public function updatePackage(String $shipmentId, String $packageId, array $data): array
    {
        return $this->api("/externalFulfillment/shipments/2021-01-06/shipments/{$shipmentId}/packages/{$packageId}", 'PUT', $data);
    }
                
    /**
     * @description Updates the status of the packages.
     * @tag 
     * @param String $shipmentId String
     * @param String $packageId String
     * @param array $query
     *      - *status* - String - required
     *          - String
     * @return array
     *      - *errors* - array
     */
    public function updatePackageStatus(String $shipmentId, String $packageId, array $query): array
    {
        return $this->api(array_merge(["/externalFulfillment/shipments/2021-01-06/shipments/{$shipmentId}/packages/{$packageId}"], $query), 'PATCH');
    }
                
    /**
     * @description Get a list of shipments dropped for the seller in the specified status. Shipments can be further filtered based on the fulfillment node and/or shipments' last updated date and time.
     * @tag 
     * @param array $query
     *      - *locationId* - String - optional
     *          - String
     *      - *status* - String - required
     *          - String
     *      - *lastUpdatedAfter* - Date - optional
     *          - Date
     *      - *lastUpdatedBefore* - Date - optional
     *          - Date
     *      - *maxResults* - Integer - optional
     *          - Integer
     *      - *nextToken* - String - optional
     *          - String
     * @return Iterator
     *      - *shipments* - array
     *          - The list of shipments in the response.
     *      - *pagination* - 
     *          - Indicates if one or more pages of shipments are available.
     */
    public function eachShipments(array $query): Iterator
    {
        return $this->eachInternal('getShipments', func_get_args());
    }
        
    /**
     * @description Get a list of shipments dropped for the seller in the specified status. Shipments can be further filtered based on the fulfillment node and/or shipments' last updated date and time.
     * @tag 
     * @param array $query
     *      - *locationId* - String - optional
     *          - String
     *      - *status* - String - required
     *          - String
     *      - *lastUpdatedAfter* - Date - optional
     *          - Date
     *      - *lastUpdatedBefore* - Date - optional
     *          - Date
     *      - *maxResults* - Integer - optional
     *          - Integer
     *      - *nextToken* - String - optional
     *          - String
     * @return Iterator
     *      - *shipments* - array
     *          - The list of shipments in the response.
     *      - *pagination* - 
     *          - Indicates if one or more pages of shipments are available.
     */
    public function batchShipments(array $query): Iterator
    {
        return $this->batchInternal('getShipments', func_get_args());
    }
    
    /**
     * @description Get a list of shipments dropped for the seller in the specified status. Shipments can be further filtered based on the fulfillment node and/or shipments' last updated date and time.
     * @tag 
     * @param array $query
     *      - *locationId* - String - optional
     *          - String
     *      - *status* - String - required
     *          - String
     *      - *lastUpdatedAfter* - Date - optional
     *          - Date
     *      - *lastUpdatedBefore* - Date - optional
     *          - Date
     *      - *maxResults* - Integer - optional
     *          - Integer
     *      - *nextToken* - String - optional
     *          - String
     * @return array
     *      - *shipments* - array
     *          - The list of shipments in the response.
     *      - *pagination* - 
     *          - Indicates if one or more pages of shipments are available.
     */
    public function getShipments(array $query): array
    {
        return $this->api(array_merge(["/externalFulfillment/shipments/2021-01-06/shipments"], $query));
    }
    
}

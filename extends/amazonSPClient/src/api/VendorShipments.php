<?php

namespace lujie\amazon\sp\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The Selling Partner API for Retail Procurement Shipments provides programmatic access to retail shipping data for vendors.
*/
class VendorShipments extends \lujie\amazon\sp\BaseAmazonSPClient
{

                
    /**
     * @description Submits one or more shipment confirmations for vendor orders.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag Vendor Shipments
     * @param array $data 
     * @return array
     *      - *payload* - 
     *          - The response payload for the SubmitShipmentConfirmations operation.
     *      - *errors* - 
     */
    public function SubmitShipmentConfirmations(array $data): array
    {
        return $this->api("/vendor/shipping/v1/shipmentConfirmations", 'POST', $data);
    }
                    
    /**
     * @description Submits one or more shipment request for vendor Orders.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag Vendor Shipments
     * @param array $data 
     * @return array
     *      - *payload* - 
     *          - The response payload for the SubmitShipmentConfirmations operation.
     *      - *errors* - 
     */
    public function SubmitShipments(array $data): array
    {
        return $this->api("/vendor/shipping/v1/shipments", 'POST', $data);
    }
            
    /**
     * @description Returns the Details about Shipment, Carrier Details,  status of the shipment, container details and other details related to shipment based on the filter parameters value that you specify.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag Vendor Shipments
     * @param array $query
     *      - *limit* - integer - optional
     *          - The limit to the number of records returned. Default value is 50 records.
     *      - *sortOrder* - string - optional
     *          - Sort in ascending or descending order by purchase order creation date.
     *      - *nextToken* - string - optional
     *          - Used for pagination when there are more shipments than the specified result size limit.
     *      - *createdAfter* - string - optional
     *          - Get Shipment Details that became available after this timestamp will be included in the result. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *createdBefore* - string - optional
     *          - Get Shipment Details that became available before this timestamp will be included in the result. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *shipmentConfirmedBefore* - string - optional
     *          - Get Shipment Details by passing Shipment confirmed create Date Before. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *shipmentConfirmedAfter* - string - optional
     *          - Get Shipment Details by passing Shipment confirmed create Date After. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *packageLabelCreatedBefore* - string - optional
     *          - Get Shipment Details by passing Package label create Date by buyer. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *packageLabelCreatedAfter* - string - optional
     *          - Get Shipment Details by passing Package label create Date After by buyer. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *shippedBefore* - string - optional
     *          - Get Shipment Details by passing Shipped Date Before. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *shippedAfter* - string - optional
     *          - Get Shipment Details by passing Shipped Date After. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *estimatedDeliveryBefore* - string - optional
     *          - Get Shipment Details by passing Estimated Delivery Date Before. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *estimatedDeliveryAfter* - string - optional
     *          - Get Shipment Details by passing Estimated Delivery Date Before. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *shipmentDeliveryBefore* - string - optional
     *          - Get Shipment Details by passing Shipment Delivery Date Before. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *shipmentDeliveryAfter* - string - optional
     *          - Get Shipment Details by passing Shipment Delivery Date After. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *requestedPickUpBefore* - string - optional
     *          - Get Shipment Details by passing Before Requested pickup date. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *requestedPickUpAfter* - string - optional
     *          - Get Shipment Details by passing After Requested pickup date. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *scheduledPickUpBefore* - string - optional
     *          - Get Shipment Details by passing Before scheduled pickup date. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *scheduledPickUpAfter* - string - optional
     *          - Get Shipment Details by passing After Scheduled pickup date. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *currentShipmentStatus* - string - optional
     *          - Get Shipment Details by passing Current shipment status.
     *      - *vendorShipmentIdentifier* - string - optional
     *          - Get Shipment Details by passing Vendor Shipment ID
     *      - *buyerReferenceNumber* - string - optional
     *          - Get Shipment Details by passing buyer Reference ID
     *      - *buyerWarehouseCode* - string - optional
     *          - Get Shipping Details based on buyer warehouse code. This value should be same as 'shipToParty.partyId' in the Shipment.
     *      - *sellerWarehouseCode* - string - optional
     *          - Get Shipping Details based on vendor warehouse code. This value should be same as 'sellingParty.partyId' in the Shipment.
     * @return Iterator
     *      - *payload* - 
     *      - *errors* - 
     */
    public function eachShipmentDetails(array $query = []): Iterator
    {
        return $this->eachInternal('GetShipmentDetails', func_get_args());
    }
        
    /**
     * @description Returns the Details about Shipment, Carrier Details,  status of the shipment, container details and other details related to shipment based on the filter parameters value that you specify.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag Vendor Shipments
     * @param array $query
     *      - *limit* - integer - optional
     *          - The limit to the number of records returned. Default value is 50 records.
     *      - *sortOrder* - string - optional
     *          - Sort in ascending or descending order by purchase order creation date.
     *      - *nextToken* - string - optional
     *          - Used for pagination when there are more shipments than the specified result size limit.
     *      - *createdAfter* - string - optional
     *          - Get Shipment Details that became available after this timestamp will be included in the result. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *createdBefore* - string - optional
     *          - Get Shipment Details that became available before this timestamp will be included in the result. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *shipmentConfirmedBefore* - string - optional
     *          - Get Shipment Details by passing Shipment confirmed create Date Before. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *shipmentConfirmedAfter* - string - optional
     *          - Get Shipment Details by passing Shipment confirmed create Date After. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *packageLabelCreatedBefore* - string - optional
     *          - Get Shipment Details by passing Package label create Date by buyer. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *packageLabelCreatedAfter* - string - optional
     *          - Get Shipment Details by passing Package label create Date After by buyer. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *shippedBefore* - string - optional
     *          - Get Shipment Details by passing Shipped Date Before. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *shippedAfter* - string - optional
     *          - Get Shipment Details by passing Shipped Date After. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *estimatedDeliveryBefore* - string - optional
     *          - Get Shipment Details by passing Estimated Delivery Date Before. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *estimatedDeliveryAfter* - string - optional
     *          - Get Shipment Details by passing Estimated Delivery Date Before. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *shipmentDeliveryBefore* - string - optional
     *          - Get Shipment Details by passing Shipment Delivery Date Before. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *shipmentDeliveryAfter* - string - optional
     *          - Get Shipment Details by passing Shipment Delivery Date After. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *requestedPickUpBefore* - string - optional
     *          - Get Shipment Details by passing Before Requested pickup date. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *requestedPickUpAfter* - string - optional
     *          - Get Shipment Details by passing After Requested pickup date. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *scheduledPickUpBefore* - string - optional
     *          - Get Shipment Details by passing Before scheduled pickup date. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *scheduledPickUpAfter* - string - optional
     *          - Get Shipment Details by passing After Scheduled pickup date. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *currentShipmentStatus* - string - optional
     *          - Get Shipment Details by passing Current shipment status.
     *      - *vendorShipmentIdentifier* - string - optional
     *          - Get Shipment Details by passing Vendor Shipment ID
     *      - *buyerReferenceNumber* - string - optional
     *          - Get Shipment Details by passing buyer Reference ID
     *      - *buyerWarehouseCode* - string - optional
     *          - Get Shipping Details based on buyer warehouse code. This value should be same as 'shipToParty.partyId' in the Shipment.
     *      - *sellerWarehouseCode* - string - optional
     *          - Get Shipping Details based on vendor warehouse code. This value should be same as 'sellingParty.partyId' in the Shipment.
     * @return Iterator
     *      - *payload* - 
     *      - *errors* - 
     */
    public function batchShipmentDetails(array $query = []): Iterator
    {
        return $this->batchInternal('GetShipmentDetails', func_get_args());
    }
    
    /**
     * @description Returns the Details about Shipment, Carrier Details,  status of the shipment, container details and other details related to shipment based on the filter parameters value that you specify.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag Vendor Shipments
     * @param array $query
     *      - *limit* - integer - optional
     *          - The limit to the number of records returned. Default value is 50 records.
     *      - *sortOrder* - string - optional
     *          - Sort in ascending or descending order by purchase order creation date.
     *      - *nextToken* - string - optional
     *          - Used for pagination when there are more shipments than the specified result size limit.
     *      - *createdAfter* - string - optional
     *          - Get Shipment Details that became available after this timestamp will be included in the result. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *createdBefore* - string - optional
     *          - Get Shipment Details that became available before this timestamp will be included in the result. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *shipmentConfirmedBefore* - string - optional
     *          - Get Shipment Details by passing Shipment confirmed create Date Before. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *shipmentConfirmedAfter* - string - optional
     *          - Get Shipment Details by passing Shipment confirmed create Date After. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *packageLabelCreatedBefore* - string - optional
     *          - Get Shipment Details by passing Package label create Date by buyer. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *packageLabelCreatedAfter* - string - optional
     *          - Get Shipment Details by passing Package label create Date After by buyer. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *shippedBefore* - string - optional
     *          - Get Shipment Details by passing Shipped Date Before. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *shippedAfter* - string - optional
     *          - Get Shipment Details by passing Shipped Date After. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *estimatedDeliveryBefore* - string - optional
     *          - Get Shipment Details by passing Estimated Delivery Date Before. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *estimatedDeliveryAfter* - string - optional
     *          - Get Shipment Details by passing Estimated Delivery Date Before. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *shipmentDeliveryBefore* - string - optional
     *          - Get Shipment Details by passing Shipment Delivery Date Before. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *shipmentDeliveryAfter* - string - optional
     *          - Get Shipment Details by passing Shipment Delivery Date After. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *requestedPickUpBefore* - string - optional
     *          - Get Shipment Details by passing Before Requested pickup date. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *requestedPickUpAfter* - string - optional
     *          - Get Shipment Details by passing After Requested pickup date. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *scheduledPickUpBefore* - string - optional
     *          - Get Shipment Details by passing Before scheduled pickup date. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *scheduledPickUpAfter* - string - optional
     *          - Get Shipment Details by passing After Scheduled pickup date. Must be in <a href='https://developer-docs.amazon.com/sp-api/docs/iso-8601'>ISO 8601</a> format.
     *      - *currentShipmentStatus* - string - optional
     *          - Get Shipment Details by passing Current shipment status.
     *      - *vendorShipmentIdentifier* - string - optional
     *          - Get Shipment Details by passing Vendor Shipment ID
     *      - *buyerReferenceNumber* - string - optional
     *          - Get Shipment Details by passing buyer Reference ID
     *      - *buyerWarehouseCode* - string - optional
     *          - Get Shipping Details based on buyer warehouse code. This value should be same as 'shipToParty.partyId' in the Shipment.
     *      - *sellerWarehouseCode* - string - optional
     *          - Get Shipping Details based on vendor warehouse code. This value should be same as 'sellingParty.partyId' in the Shipment.
     * @return array
     *      - *payload* - 
     *      - *errors* - 
     */
    public function GetShipmentDetails(array $query = []): array
    {
        return $this->api(array_merge(["/vendor/shipping/v1/shipments"], $query));
    }
                
    /**
     * @description Returns transport Labels based on the filters that you specify.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag Vendor Shipments
     * @param array $query
     *      - *limit* - integer - optional
     *          - The limit to the number of records returned. Default value is 50 records.
     *      - *sortOrder* - string - optional
     *          - Sort in ascending or descending order by transport label creation date.
     *      - *nextToken* - string - optional
     *          - Used for pagination when there are more transport label than the specified result size limit.
     *      - *labelCreatedAfter* - string - optional
     *          - transport Labels that became available after this timestamp will be included in the result. Must be in ISO-8601 date/time format.
     *      - *labelcreatedBefore* - string - optional
     *          - transport Labels that became available before this timestamp will be included in the result. Must be in ISO-8601 date/time format.
     *      - *buyerReferenceNumber* - string - optional
     *          - Get transport labels by passing Buyer Reference Number to retreive the corresponding transport label.
     *      - *vendorShipmentIdentifier* - string - optional
     *          - Get transport labels by passing Vendor Shipment ID to retreive the corresponding transport label.
     *      - *sellerWarehouseCode* - string - optional
     *          - Get Shipping labels based Vendor Warehouse code. This value should be same as 'shipFromParty.partyId' in the Shipment.
     * @return Iterator
     *      - *payload* - 
     *      - *errors* - 
     */
    public function eachShipmentLabels(array $query = []): Iterator
    {
        return $this->eachInternal('GetShipmentLabels', func_get_args());
    }
        
    /**
     * @description Returns transport Labels based on the filters that you specify.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag Vendor Shipments
     * @param array $query
     *      - *limit* - integer - optional
     *          - The limit to the number of records returned. Default value is 50 records.
     *      - *sortOrder* - string - optional
     *          - Sort in ascending or descending order by transport label creation date.
     *      - *nextToken* - string - optional
     *          - Used for pagination when there are more transport label than the specified result size limit.
     *      - *labelCreatedAfter* - string - optional
     *          - transport Labels that became available after this timestamp will be included in the result. Must be in ISO-8601 date/time format.
     *      - *labelcreatedBefore* - string - optional
     *          - transport Labels that became available before this timestamp will be included in the result. Must be in ISO-8601 date/time format.
     *      - *buyerReferenceNumber* - string - optional
     *          - Get transport labels by passing Buyer Reference Number to retreive the corresponding transport label.
     *      - *vendorShipmentIdentifier* - string - optional
     *          - Get transport labels by passing Vendor Shipment ID to retreive the corresponding transport label.
     *      - *sellerWarehouseCode* - string - optional
     *          - Get Shipping labels based Vendor Warehouse code. This value should be same as 'shipFromParty.partyId' in the Shipment.
     * @return Iterator
     *      - *payload* - 
     *      - *errors* - 
     */
    public function batchShipmentLabels(array $query = []): Iterator
    {
        return $this->batchInternal('GetShipmentLabels', func_get_args());
    }
    
    /**
     * @description Returns transport Labels based on the filters that you specify.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag Vendor Shipments
     * @param array $query
     *      - *limit* - integer - optional
     *          - The limit to the number of records returned. Default value is 50 records.
     *      - *sortOrder* - string - optional
     *          - Sort in ascending or descending order by transport label creation date.
     *      - *nextToken* - string - optional
     *          - Used for pagination when there are more transport label than the specified result size limit.
     *      - *labelCreatedAfter* - string - optional
     *          - transport Labels that became available after this timestamp will be included in the result. Must be in ISO-8601 date/time format.
     *      - *labelcreatedBefore* - string - optional
     *          - transport Labels that became available before this timestamp will be included in the result. Must be in ISO-8601 date/time format.
     *      - *buyerReferenceNumber* - string - optional
     *          - Get transport labels by passing Buyer Reference Number to retreive the corresponding transport label.
     *      - *vendorShipmentIdentifier* - string - optional
     *          - Get transport labels by passing Vendor Shipment ID to retreive the corresponding transport label.
     *      - *sellerWarehouseCode* - string - optional
     *          - Get Shipping labels based Vendor Warehouse code. This value should be same as 'shipFromParty.partyId' in the Shipment.
     * @return array
     *      - *payload* - 
     *      - *errors* - 
     */
    public function GetShipmentLabels(array $query = []): array
    {
        return $this->api(array_merge(["/vendor/shipping/v1/transportLabels"], $query));
    }
    
}

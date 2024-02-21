<?php

namespace lujie\amazon\sp\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The Selling Partner API for Direct Fulfillment Shipping provides programmatic access to a direct fulfillment vendor's shipping data.
*/
class VendorDirectFulfillmentShippingV1 extends \lujie\amazon\sp\BaseAmazonSPClient
{

            
    /**
     * @description Returns a list of shipping labels created during the time frame that you specify. You define that time frame using the createdAfter and createdBefore parameters. You must use both of these parameters. The date range to search must not be more than 7 days.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag vendorShippingLabels
     * @param array $query
     *      - *shipFromPartyId* - string - optional
     *          - The vendor warehouseId for order fulfillment. If not specified, the result will contain orders for all warehouses.
     *      - *limit* - integer - optional
     *          - The limit to the number of records returned.
     *      - *createdAfter* - string - required
     *          - Shipping labels that became available after this date and time will be included in the result. Must be in ISO-8601 date/time format.
     *      - *createdBefore* - string - required
     *          - Shipping labels that became available before this date and time will be included in the result. Must be in ISO-8601 date/time format.
     *      - *sortOrder* - string - optional
     *          - Sort ASC or DESC by order creation date.
     *      - *nextToken* - string - optional
     *          - Used for pagination when there are more ship labels than the specified result size limit. The token value is returned in the previous API call.
     * @return Iterator
     *      - *payload* - 
     *          - List of ship labels.
     *      - *errors* - 
     */
    public function eachShippingLabels(array $query): Iterator
    {
        return $this->eachInternal('getShippingLabels', func_get_args());
    }
        
    /**
     * @description Returns a list of shipping labels created during the time frame that you specify. You define that time frame using the createdAfter and createdBefore parameters. You must use both of these parameters. The date range to search must not be more than 7 days.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag vendorShippingLabels
     * @param array $query
     *      - *shipFromPartyId* - string - optional
     *          - The vendor warehouseId for order fulfillment. If not specified, the result will contain orders for all warehouses.
     *      - *limit* - integer - optional
     *          - The limit to the number of records returned.
     *      - *createdAfter* - string - required
     *          - Shipping labels that became available after this date and time will be included in the result. Must be in ISO-8601 date/time format.
     *      - *createdBefore* - string - required
     *          - Shipping labels that became available before this date and time will be included in the result. Must be in ISO-8601 date/time format.
     *      - *sortOrder* - string - optional
     *          - Sort ASC or DESC by order creation date.
     *      - *nextToken* - string - optional
     *          - Used for pagination when there are more ship labels than the specified result size limit. The token value is returned in the previous API call.
     * @return Iterator
     *      - *payload* - 
     *          - List of ship labels.
     *      - *errors* - 
     */
    public function batchShippingLabels(array $query): Iterator
    {
        return $this->batchInternal('getShippingLabels', func_get_args());
    }
    
    /**
     * @description Returns a list of shipping labels created during the time frame that you specify. You define that time frame using the createdAfter and createdBefore parameters. You must use both of these parameters. The date range to search must not be more than 7 days.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag vendorShippingLabels
     * @param array $query
     *      - *shipFromPartyId* - string - optional
     *          - The vendor warehouseId for order fulfillment. If not specified, the result will contain orders for all warehouses.
     *      - *limit* - integer - optional
     *          - The limit to the number of records returned.
     *      - *createdAfter* - string - required
     *          - Shipping labels that became available after this date and time will be included in the result. Must be in ISO-8601 date/time format.
     *      - *createdBefore* - string - required
     *          - Shipping labels that became available before this date and time will be included in the result. Must be in ISO-8601 date/time format.
     *      - *sortOrder* - string - optional
     *          - Sort ASC or DESC by order creation date.
     *      - *nextToken* - string - optional
     *          - Used for pagination when there are more ship labels than the specified result size limit. The token value is returned in the previous API call.
     * @return array
     *      - *payload* - 
     *          - List of ship labels.
     *      - *errors* - 
     */
    public function getShippingLabels(array $query): array
    {
        return $this->api(array_merge(["/vendor/directFulfillment/shipping/v1/shippingLabels"], $query));
    }
                
    /**
     * @description Creates a shipping label for a purchase order and returns a transactionId for reference.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag vendorShippingLabels
     * @param array $data 
     * @return array
     *      - *payload* - 
     *          - The response payload for the submitShippingLabelRequest operation.
     *      - *errors* - 
     */
    public function submitShippingLabelRequest(array $data): array
    {
        return $this->api("/vendor/directFulfillment/shipping/v1/shippingLabels", 'POST', $data);
    }
                    
    /**
     * @description Returns a shipping label for the purchaseOrderNumber that you specify.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag vendorShippingLabels
     * @param string $purchaseOrderNumber The purchase order number for which you want to return the shipping label. It should be the same purchaseOrderNumber as received in the order.
     * @return array
     *      - *payload* - 
     *          - The payload for the getShippingLabel operation.
     *      - *errors* - 
     */
    public function getShippingLabel(string $purchaseOrderNumber): array
    {
        return $this->api("/vendor/directFulfillment/shipping/v1/shippingLabels/{$purchaseOrderNumber}");
    }
                    
    /**
     * @description Submits one or more shipment confirmations for vendor orders.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag vendorShipping
     * @param array $data 
     * @return array
     *      - *payload* - 
     *          - The response payload for the submitShipmentConfirmations operation.
     *      - *errors* - 
     */
    public function submitShipmentConfirmations(array $data): array
    {
        return $this->api("/vendor/directFulfillment/shipping/v1/shipmentConfirmations", 'POST', $data);
    }
                    
    /**
     * @description This API call is only to be used by Vendor-Own-Carrier (VOC) vendors. Calling this API will submit a shipment status update for the package that a vendor has shipped. It will provide the Amazon customer visibility on their order, when the package is outside of Amazon Network visibility.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag vendorShipping
     * @param array $data 
     * @return array
     *      - *payload* - 
     *          - The response payload for the submitShipmentStatusUpdates operation.
     *      - *errors* - 
     */
    public function submitShipmentStatusUpdates(array $data): array
    {
        return $this->api("/vendor/directFulfillment/shipping/v1/shipmentStatusUpdates", 'POST', $data);
    }
                
    /**
     * @description Returns a list of customer invoices created during a time frame that you specify. You define the  time frame using the createdAfter and createdBefore parameters. You must use both of these parameters. The date range to search must be no more than 7 days.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag customerInvoices
     * @param array $query
     *      - *shipFromPartyId* - string - optional
     *          - The vendor warehouseId for order fulfillment. If not specified, the result will contain orders for all warehouses.
     *      - *limit* - integer - optional
     *          - The limit to the number of records returned
     *      - *createdAfter* - string - required
     *          - Orders that became available after this date and time will be included in the result. Must be in ISO-8601 date/time format.
     *      - *createdBefore* - string - required
     *          - Orders that became available before this date and time will be included in the result. Must be in ISO-8601 date/time format.
     *      - *sortOrder* - string - optional
     *          - Sort ASC or DESC by order creation date.
     *      - *nextToken* - string - optional
     *          - Used for pagination when there are more orders than the specified result size limit. The token value is returned in the previous API call.
     * @return Iterator
     *      - *payload* - 
     *          - List of customer invoices.
     *      - *errors* - 
     */
    public function eachCustomerInvoices(array $query): Iterator
    {
        return $this->eachInternal('getCustomerInvoices', func_get_args());
    }
        
    /**
     * @description Returns a list of customer invoices created during a time frame that you specify. You define the  time frame using the createdAfter and createdBefore parameters. You must use both of these parameters. The date range to search must be no more than 7 days.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag customerInvoices
     * @param array $query
     *      - *shipFromPartyId* - string - optional
     *          - The vendor warehouseId for order fulfillment. If not specified, the result will contain orders for all warehouses.
     *      - *limit* - integer - optional
     *          - The limit to the number of records returned
     *      - *createdAfter* - string - required
     *          - Orders that became available after this date and time will be included in the result. Must be in ISO-8601 date/time format.
     *      - *createdBefore* - string - required
     *          - Orders that became available before this date and time will be included in the result. Must be in ISO-8601 date/time format.
     *      - *sortOrder* - string - optional
     *          - Sort ASC or DESC by order creation date.
     *      - *nextToken* - string - optional
     *          - Used for pagination when there are more orders than the specified result size limit. The token value is returned in the previous API call.
     * @return Iterator
     *      - *payload* - 
     *          - List of customer invoices.
     *      - *errors* - 
     */
    public function batchCustomerInvoices(array $query): Iterator
    {
        return $this->batchInternal('getCustomerInvoices', func_get_args());
    }
    
    /**
     * @description Returns a list of customer invoices created during a time frame that you specify. You define the  time frame using the createdAfter and createdBefore parameters. You must use both of these parameters. The date range to search must be no more than 7 days.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag customerInvoices
     * @param array $query
     *      - *shipFromPartyId* - string - optional
     *          - The vendor warehouseId for order fulfillment. If not specified, the result will contain orders for all warehouses.
     *      - *limit* - integer - optional
     *          - The limit to the number of records returned
     *      - *createdAfter* - string - required
     *          - Orders that became available after this date and time will be included in the result. Must be in ISO-8601 date/time format.
     *      - *createdBefore* - string - required
     *          - Orders that became available before this date and time will be included in the result. Must be in ISO-8601 date/time format.
     *      - *sortOrder* - string - optional
     *          - Sort ASC or DESC by order creation date.
     *      - *nextToken* - string - optional
     *          - Used for pagination when there are more orders than the specified result size limit. The token value is returned in the previous API call.
     * @return array
     *      - *payload* - 
     *          - List of customer invoices.
     *      - *errors* - 
     */
    public function getCustomerInvoices(array $query): array
    {
        return $this->api(array_merge(["/vendor/directFulfillment/shipping/v1/customerInvoices"], $query));
    }
                    
    /**
     * @description Returns a customer invoice based on the purchaseOrderNumber that you specify.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag customerInvoices
     * @param string $purchaseOrderNumber Purchase order number of the shipment for which to return the invoice.
     * @return array
     *      - *payload* - 
     *          - The payload for the getCustomerInvoice operation.
     *      - *errors* - 
     */
    public function getCustomerInvoice(string $purchaseOrderNumber): array
    {
        return $this->api("/vendor/directFulfillment/shipping/v1/customerInvoices/{$purchaseOrderNumber}");
    }
                
    /**
     * @description Returns a list of packing slips for the purchase orders that match the criteria specified. Date range to search must not be more than 7 days.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag vendorShipping
     * @param array $query
     *      - *shipFromPartyId* - string - optional
     *          - The vendor warehouseId for order fulfillment. If not specified the result will contain orders for all warehouses.
     *      - *limit* - integer - optional
     *          - The limit to the number of records returned
     *      - *createdAfter* - string - required
     *          - Packing slips that became available after this date and time will be included in the result. Must be in ISO-8601 date/time format.
     *      - *createdBefore* - string - required
     *          - Packing slips that became available before this date and time will be included in the result. Must be in ISO-8601 date/time format.
     *      - *sortOrder* - string - optional
     *          - Sort ASC or DESC by packing slip creation date.
     *      - *nextToken* - string - optional
     *          - Used for pagination when there are more packing slips than the specified result size limit. The token value is returned in the previous API call.
     * @return Iterator
     *      - *payload* - 
     *      - *errors* - 
     */
    public function eachPackingSlips(array $query): Iterator
    {
        return $this->eachInternal('getPackingSlips', func_get_args());
    }
        
    /**
     * @description Returns a list of packing slips for the purchase orders that match the criteria specified. Date range to search must not be more than 7 days.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag vendorShipping
     * @param array $query
     *      - *shipFromPartyId* - string - optional
     *          - The vendor warehouseId for order fulfillment. If not specified the result will contain orders for all warehouses.
     *      - *limit* - integer - optional
     *          - The limit to the number of records returned
     *      - *createdAfter* - string - required
     *          - Packing slips that became available after this date and time will be included in the result. Must be in ISO-8601 date/time format.
     *      - *createdBefore* - string - required
     *          - Packing slips that became available before this date and time will be included in the result. Must be in ISO-8601 date/time format.
     *      - *sortOrder* - string - optional
     *          - Sort ASC or DESC by packing slip creation date.
     *      - *nextToken* - string - optional
     *          - Used for pagination when there are more packing slips than the specified result size limit. The token value is returned in the previous API call.
     * @return Iterator
     *      - *payload* - 
     *      - *errors* - 
     */
    public function batchPackingSlips(array $query): Iterator
    {
        return $this->batchInternal('getPackingSlips', func_get_args());
    }
    
    /**
     * @description Returns a list of packing slips for the purchase orders that match the criteria specified. Date range to search must not be more than 7 days.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag vendorShipping
     * @param array $query
     *      - *shipFromPartyId* - string - optional
     *          - The vendor warehouseId for order fulfillment. If not specified the result will contain orders for all warehouses.
     *      - *limit* - integer - optional
     *          - The limit to the number of records returned
     *      - *createdAfter* - string - required
     *          - Packing slips that became available after this date and time will be included in the result. Must be in ISO-8601 date/time format.
     *      - *createdBefore* - string - required
     *          - Packing slips that became available before this date and time will be included in the result. Must be in ISO-8601 date/time format.
     *      - *sortOrder* - string - optional
     *          - Sort ASC or DESC by packing slip creation date.
     *      - *nextToken* - string - optional
     *          - Used for pagination when there are more packing slips than the specified result size limit. The token value is returned in the previous API call.
     * @return array
     *      - *payload* - 
     *      - *errors* - 
     */
    public function getPackingSlips(array $query): array
    {
        return $this->api(array_merge(["/vendor/directFulfillment/shipping/v1/packingSlips"], $query));
    }
                    
    /**
     * @description Returns a packing slip based on the purchaseOrderNumber that you specify.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag vendorShipping
     * @param string $purchaseOrderNumber The purchaseOrderNumber for the packing slip you want.
     * @return array
     *      - *payload* - 
     *      - *errors* - 
     */
    public function getPackingSlip(string $purchaseOrderNumber): array
    {
        return $this->api("/vendor/directFulfillment/shipping/v1/packingSlips/{$purchaseOrderNumber}");
    }
    
}
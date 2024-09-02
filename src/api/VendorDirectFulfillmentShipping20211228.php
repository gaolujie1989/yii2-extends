<?php

namespace lujie\amazon\sp\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The Selling Partner API for Direct Fulfillment Shipping provides programmatic access to a direct fulfillment vendor's shipping data.
*/
class VendorDirectFulfillmentShipping20211228 extends \lujie\amazon\sp\BaseAmazonSPClient
{

            
    /**
     * @description Returns a list of shipping labels created during the time frame that you specify. You define that time frame using the createdAfter and createdBefore parameters. You must use both of these parameters. The date range to search must not be more than 7 days.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values then those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](doc:usage-plans-and-rate-limits-in-the-sp-api).
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
     *      - *pagination* - 
     *      - *shippingLabels* - array
     *          - An array that contains the details of the generated shipping labels.
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

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values then those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](doc:usage-plans-and-rate-limits-in-the-sp-api).
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
     *      - *pagination* - 
     *      - *shippingLabels* - array
     *          - An array that contains the details of the generated shipping labels.
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

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values then those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](doc:usage-plans-and-rate-limits-in-the-sp-api).
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
     *      - *pagination* - 
     *      - *shippingLabels* - array
     *          - An array that contains the details of the generated shipping labels.
     */
    public function getShippingLabels(array $query): array
    {
        return $this->api(array_merge(["/vendor/directFulfillment/shipping/2021-12-28/shippingLabels"], $query));
    }
                
    /**
     * @description Creates a shipping label for a purchase order and returns a transactionId for reference.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values then those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](doc:usage-plans-and-rate-limits-in-the-sp-api).
     * @tag vendorShippingLabels
     * @param array $data 
     * @return array
     *      - *transactionId* - string
     *          - GUID to identify this transaction. This value can be used with the Transaction Status API to return the status of this transaction.
     */
    public function submitShippingLabelRequest(array $data): array
    {
        return $this->api("/vendor/directFulfillment/shipping/2021-12-28/shippingLabels", 'POST', $data);
    }
                    
    /**
     * @description Returns a shipping label for the purchaseOrderNumber that you specify.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values then those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](doc:usage-plans-and-rate-limits-in-the-sp-api).
     * @tag vendorShippingLabels
     * @param string $purchaseOrderNumber The purchase order number for which you want to return the shipping label. Should be the same `purchaseOrderNumber` as received in the order.
     * @return array
     *      - *purchaseOrderNumber* - string
     *          - This field will contain the Purchase Order Number for this order.
     *      - *sellingParty* - 
     *          - ID of the selling party or vendor.
     *      - *shipFromParty* - 
     *          - Warehouse code of vendor.
     *      - *labelFormat* - string
     *          - Format of the label.
     *      - *labelData* - array
     *          - Provides the details of the packages in this shipment.
     */
    public function getShippingLabel(string $purchaseOrderNumber): array
    {
        return $this->api("/vendor/directFulfillment/shipping/2021-12-28/shippingLabels/{$purchaseOrderNumber}");
    }
                
    /**
     * @description Creates shipping labels for a purchase order and returns the labels.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values then those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](doc:usage-plans-and-rate-limits-in-the-sp-api).
     * @tag vendorShippingLabels
     * @param string $purchaseOrderNumber The purchase order number for which you want to return the shipping labels. It should be the same purchaseOrderNumber as received in the order.
     * @param array $data 
     * @return array
     *      - *purchaseOrderNumber* - string
     *          - This field will contain the Purchase Order Number for this order.
     *      - *sellingParty* - 
     *          - ID of the selling party or vendor.
     *      - *shipFromParty* - 
     *          - Warehouse code of vendor.
     *      - *labelFormat* - string
     *          - Format of the label.
     *      - *labelData* - array
     *          - Provides the details of the packages in this shipment.
     */
    public function createShippingLabels(string $purchaseOrderNumber, array $data): array
    {
        return $this->api("/vendor/directFulfillment/shipping/2021-12-28/shippingLabels/{$purchaseOrderNumber}", 'POST', $data);
    }
                    
    /**
     * @description Submits one or more shipment confirmations for vendor orders.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values then those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](doc:usage-plans-and-rate-limits-in-the-sp-api).
     * @tag vendorShipping
     * @param array $data 
     * @return array
     *      - *transactionId* - string
     *          - GUID to identify this transaction. This value can be used with the Transaction Status API to return the status of this transaction.
     */
    public function submitShipmentConfirmations(array $data): array
    {
        return $this->api("/vendor/directFulfillment/shipping/2021-12-28/shipmentConfirmations", 'POST', $data);
    }
                    
    /**
     * @description This operation is only to be used by Vendor-Own-Carrier (VOC) vendors. Calling this API submits a shipment status update for the package that a vendor has shipped. It will provide the Amazon customer visibility on their order, when the package is outside of Amazon Network visibility.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values then those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](doc:usage-plans-and-rate-limits-in-the-sp-api).
     * @tag vendorShipping
     * @param array $data 
     * @return array
     *      - *transactionId* - string
     *          - GUID to identify this transaction. This value can be used with the Transaction Status API to return the status of this transaction.
     */
    public function submitShipmentStatusUpdates(array $data): array
    {
        return $this->api("/vendor/directFulfillment/shipping/2021-12-28/shipmentStatusUpdates", 'POST', $data);
    }
                
    /**
     * @description Returns a list of customer invoices created during a time frame that you specify. You define the time frame using the createdAfter and createdBefore parameters. You must use both of these parameters. The date range to search must be no more than 7 days.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values then those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](doc:usage-plans-and-rate-limits-in-the-sp-api).
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
     *      - *pagination* - 
     *          - The pagination elements required to retrieve the remaining data.
     *      - *customerInvoices* - array
     *          - Represents a customer invoice within the `CustomerInvoiceList`.
     */
    public function eachCustomerInvoices(array $query): Iterator
    {
        return $this->eachInternal('getCustomerInvoices', func_get_args());
    }
        
    /**
     * @description Returns a list of customer invoices created during a time frame that you specify. You define the time frame using the createdAfter and createdBefore parameters. You must use both of these parameters. The date range to search must be no more than 7 days.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values then those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](doc:usage-plans-and-rate-limits-in-the-sp-api).
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
     *      - *pagination* - 
     *          - The pagination elements required to retrieve the remaining data.
     *      - *customerInvoices* - array
     *          - Represents a customer invoice within the `CustomerInvoiceList`.
     */
    public function batchCustomerInvoices(array $query): Iterator
    {
        return $this->batchInternal('getCustomerInvoices', func_get_args());
    }
    
    /**
     * @description Returns a list of customer invoices created during a time frame that you specify. You define the time frame using the createdAfter and createdBefore parameters. You must use both of these parameters. The date range to search must be no more than 7 days.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values then those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](doc:usage-plans-and-rate-limits-in-the-sp-api).
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
     *      - *pagination* - 
     *          - The pagination elements required to retrieve the remaining data.
     *      - *customerInvoices* - array
     *          - Represents a customer invoice within the `CustomerInvoiceList`.
     */
    public function getCustomerInvoices(array $query): array
    {
        return $this->api(array_merge(["/vendor/directFulfillment/shipping/2021-12-28/customerInvoices"], $query));
    }
                    
    /**
     * @description Returns a customer invoice based on the purchaseOrderNumber that you specify.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values then those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](doc:usage-plans-and-rate-limits-in-the-sp-api).
     * @tag customerInvoices
     * @param string $purchaseOrderNumber Purchase order number of the shipment for which to return the invoice.
     * @return array
     *      - *purchaseOrderNumber* - string
     *          - The purchase order number for this order.
     *      - *content* - string
     *          - The Base64encoded customer invoice.
     */
    public function getCustomerInvoice(string $purchaseOrderNumber): array
    {
        return $this->api("/vendor/directFulfillment/shipping/2021-12-28/customerInvoices/{$purchaseOrderNumber}");
    }
                
    /**
     * @description Returns a list of packing slips for the purchase orders that match the criteria specified. Date range to search must not be more than 7 days.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values then those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](doc:usage-plans-and-rate-limits-in-the-sp-api).
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
     *      - *pagination* - 
     *          - The pagination elements required to retrieve the remaining data.
     *      - *packingSlips* - array
     *          - An array of packing slip objects.
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

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values then those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](doc:usage-plans-and-rate-limits-in-the-sp-api).
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
     *      - *pagination* - 
     *          - The pagination elements required to retrieve the remaining data.
     *      - *packingSlips* - array
     *          - An array of packing slip objects.
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

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values then those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](doc:usage-plans-and-rate-limits-in-the-sp-api).
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
     *      - *pagination* - 
     *          - The pagination elements required to retrieve the remaining data.
     *      - *packingSlips* - array
     *          - An array of packing slip objects.
     */
    public function getPackingSlips(array $query): array
    {
        return $this->api(array_merge(["/vendor/directFulfillment/shipping/2021-12-28/packingSlips"], $query));
    }
                    
    /**
     * @description Returns a packing slip based on the purchaseOrderNumber that you specify.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values then those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](doc:usage-plans-and-rate-limits-in-the-sp-api).
     * @tag vendorShipping
     * @param string $purchaseOrderNumber The purchaseOrderNumber for the packing slip you want.
     * @return array
     *      - *purchaseOrderNumber* - string
     *          - Purchase order number of the shipment that the packing slip is for.
     *      - *content* - string
     *          - A Base64encoded string of the packing slip PDF.
     *      - *contentType* - string
     *          - The format of the file such as PDF, JPEG etc.
     */
    public function getPackingSlip(string $purchaseOrderNumber): array
    {
        return $this->api("/vendor/directFulfillment/shipping/2021-12-28/packingSlips/{$purchaseOrderNumber}");
    }
    
}

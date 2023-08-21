<?php

namespace lujie\amazon\sp\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The Selling Partner API for Easy Ship helps you build applications that help sellers manage and ship Amazon Easy Ship orders.

Your Easy Ship applications can:

* Get available time slots for packages to be scheduled for delivery.

* Schedule, reschedule, and cancel Easy Ship orders.

* Print labels, invoices, and warranties.

See the [Marketplace Support Table](doc:easyship-api-v2022-03-23-use-case-guide#marketplace-support-table) for the differences in Easy Ship operations by marketplace.
*/
class EasyShip20220323 extends \lujie\amazon\sp\BaseAmazonSPClient
{

                
    /**
     * @description Returns time slots available for Easy Ship orders to be scheduled based on the package weight and dimensions that the seller specifies.

This operation is available for scheduled and unscheduled orders based on marketplace support. See **Get Time Slots** in the [Marketplace Support Table](doc:easyship-api-v2022-03-23-use-case-guide#marketplace-support-table).

This operation can return time slots that have either pickup or drop-off handover methods - see **Supported Handover Methods** in the [Marketplace Support Table](doc:easyship-api-v2022-03-23-use-case-guide#marketplace-support-table).

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 1 | 5 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](doc:usage-plans-and-rate-limits-in-the-sp-api).
     * @tag easyShip
     * @param array $data 
     * @return array
     *      - *amazonOrderId* - 
     *      - *timeSlots* - 
     */
    public function listHandoverSlots(array $data): array
    {
        return $this->api("/easyShip/2022-03-23/timeSlot", 'POST', $data);
    }
                    
    /**
     * @description Returns information about a package, including dimensions, weight, time slot information for handover, invoice and item information, and status.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 1 | 5 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](doc:usage-plans-and-rate-limits-in-the-sp-api).
     * @tag easyShip
     * @param array $query
     *      - *amazonOrderId* - string - required
     *          - An Amazon-defined order identifier. Identifies the order that the seller wants to deliver using Amazon Easy Ship.
     *      - *marketplaceId* - string - required
     *          - An identifier for the marketplace in which the seller is selling.
     * @return array
     *      - *scheduledPackageId* - 
     *      - *packageDimensions* - 
     *      - *packageWeight* - 
     *      - *packageItems* - 
     *      - *packageTimeSlot* - 
     *      - *packageIdentifier* - 
     *      - *invoice* - 
     *      - *packageStatus* - 
     *      - *trackingDetails* - 
     */
    public function getScheduledPackage(array $query): array
    {
        return $this->api(array_merge(["/easyShip/2022-03-23/package"], $query));
    }
                
    /**
     * @description Schedules an Easy Ship order and returns the scheduled package information.

This operation does the following:

*  Specifies the time slot and handover method for the order to be scheduled for delivery.

* Updates the Easy Ship order status.

* Generates a shipping label and an invoice. Calling `createScheduledPackage` also generates a warranty document if you specify a `SerialNumber` value. To get these documents, see [How to get invoice, shipping label, and warranty documents](doc:easyship-api-v2022-03-23-use-case-guide).

* Shows the status of Easy Ship orders when you call the `getOrders` operation of the Selling Partner API for Orders and examine the `EasyShipShipmentStatus` property in the response body.

See the **Shipping Label**, **Invoice**, and **Warranty** columns in the [Marketplace Support Table](doc:easyship-api-v2022-03-23-use-case-guide#marketplace-support-table) to see which documents are supported in each marketplace.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 1 | 5 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](doc:usage-plans-and-rate-limits-in-the-sp-api).
     * @tag easyShip
     * @param array $data 
     * @return array
     *      - *scheduledPackageId* - 
     *      - *packageDimensions* - 
     *      - *packageWeight* - 
     *      - *packageItems* - 
     *      - *packageTimeSlot* - 
     *      - *packageIdentifier* - 
     *      - *invoice* - 
     *      - *packageStatus* - 
     *      - *trackingDetails* - 
     */
    public function createScheduledPackage(array $data): array
    {
        return $this->api("/easyShip/2022-03-23/package", 'POST', $data);
    }
                
    /**
     * @description Updates the time slot for handing over the package indicated by the specified `scheduledPackageId`. You can get the new `slotId` value for the time slot by calling the `listHandoverSlots` operation before making another `patch` call.

See the **Update Package** column in the [Marketplace Support Table](doc:easyship-api-v2022-03-23-use-case-guide#marketplace-support-table) to see which marketplaces this operation is supported in.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 1 | 5 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](doc:usage-plans-and-rate-limits-in-the-sp-api).
     * @tag easyShip
     * @param array $data 
     * @return array
     *      - *packages* - array
     */
    public function updateScheduledPackages(array $data): array
    {
        return $this->api("/easyShip/2022-03-23/package", 'PATCH', $data);
    }
                    
    /**
     * @description This operation automatically schedules a time slot for all the `amazonOrderId`s given as input, generating the associated shipping labels, along with other compliance documents according to the marketplace (refer to the [marketplace document support table](doc:easyship-api-v2022-03-23-use-case-guide#marketplace-support-table)).

Developers calling this operation may optionally assign a `packageDetails` object, allowing them to input a preferred time slot for each order in ther request. In this case, Amazon will try to schedule the respective packages using their optional settings. On the other hand, *i.e.*, if the time slot is not provided, Amazon will then pick the earliest time slot possible. 

Regarding the shipping label's file format, external developers are able to choose between PDF or ZPL, and Amazon will create the label accordingly.

This operation returns an array composed of the scheduled packages, and a short-lived URL pointing to a zip file containing the generated shipping labels and the other documents enabled for your marketplace. If at least an order couldn't be scheduled, then Amazon adds the `rejectedOrders` list into the response, which contains an entry for each order we couldn't process. Each entry is composed of an error message describing the reason of the failure, so that sellers can take action.

The table below displays the supported request and burst maximum rates:

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 1 | 5 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](doc:usage-plans-and-rate-limits-in-the-sp-api).
     * @tag easyShip
     * @param array $data 
     * @return array
     *      - *scheduledPackages* - array
     *          - A list of packages. Refer to the `Package` object.
     *      - *rejectedOrders* - array
     *          - A list of orders we couldn't scheduled on your behalf. Each element contains the reason and details on the error.
     *      - *printableDocumentsUrl* - 
     */
    public function createScheduledPackageBulk(array $data): array
    {
        return $this->api("/easyShip/2022-03-23/packages/bulk", 'POST', $data);
    }
    
}

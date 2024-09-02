<?php

namespace lujie\amazon\sp\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The Selling Partner API for Retail Procurement Payments provides programmatic access to vendors payments data.
*/
class VendorInvoices extends \lujie\amazon\sp\BaseAmazonSPClient
{

                
    /**
     * @description Submit new invoices to Amazon.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, refer to [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag vendorPayments
     * @param array $data 
     * @return array
     *      - *payload* - 
     *          - The response payload for the `submitInvoices` operation.
     *      - *errors* - 
     */
    public function submitInvoices(array $data): array
    {
        return $this->api("/vendor/payments/v1/invoices", 'POST', $data);
    }
    
}

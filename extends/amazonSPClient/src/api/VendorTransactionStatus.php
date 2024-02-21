<?php

namespace lujie\amazon\sp\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The Selling Partner API for Retail Procurement Transaction Status provides programmatic access to status information on specific asynchronous POST transactions for vendors.
*/
class VendorTransactionStatus extends \lujie\amazon\sp\BaseAmazonSPClient
{

                
    /**
     * @description Returns the status of the transaction that you specify.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 10 | 20 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag vendorTransaction
     * @param string $transactionId The GUID provided by Amazon in the 'transactionId' field in response to the post request of a specific transaction.
     * @return array
     *      - *payload* - 
     *          - The response payload for the getTransaction operation.
     *      - *errors* - 
     */
    public function getTransaction(string $transactionId): array
    {
        return $this->api("/vendor/transactions/v1/transactions/{$transactionId}");
    }
        
}
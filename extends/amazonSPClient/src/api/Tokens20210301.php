<?php

namespace lujie\amazon\sp\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The Selling Partner API for Tokens provides a secure way to access a customer's PII (Personally Identifiable Information). You can call the Tokens API to get a Restricted Data Token (RDT) for one or more restricted resources that you specify. The RDT authorizes subsequent calls to restricted operations that correspond to the restricted resources that you specified.

For more information, see the [Tokens API Use Case Guide](doc:tokens-api-use-case-guide).
*/
class Tokens20210301 extends \lujie\amazon\sp\BaseAmazonSPClient
{

                
    /**
     * @description Returns a Restricted Data Token (RDT) for one or more restricted resources that you specify. A restricted resource is the HTTP method and path from a restricted operation that returns Personally Identifiable Information (PII), plus a dataElements value that indicates the type of PII requested. See the Tokens API Use Case Guide for a list of restricted operations. Use the RDT returned here as the access token in subsequent calls to the corresponding restricted operations.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 1 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag tokens
     * @param array $data 
     * @return array
     *      - *restrictedDataToken* - string
     *          - A Restricted Data Token (RDT). This is a short-lived access token that authorizes calls to restricted operations. Pass this value with the x-amz-access-token header when making subsequent calls to these restricted resources.
     *      - *expiresIn* - integer
     *          - The lifetime of the Restricted Data Token, in seconds.
     */
    public function createRestrictedDataToken(array $data): array
    {
        return $this->api("/tokens/2021-03-01/restrictedDataToken", 'POST', $data);
    }
    
}

<?php

namespace lujie\amazon\sp\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The Selling Partner API for Pricing helps you programmatically retrieve product pricing and offer pricing information for Amazon Marketplace products.

For more information, see the [Product Pricing v2022-05-01 Use Case Guide](doc:product-pricing-api-v2022-05-01-use-case-guide).
*/
class ProductPricing20220501 extends \lujie\amazon\sp\BaseAmazonSPClient
{

                
    /**
     * @description Returns the set of responses that correspond to the batched list of up to 40 requests defined in the request body. The response for each successful (HTTP status code 200) request in the set includes the computed listing price at or below which a seller can expect to become the featured offer (before applicable promotions). This is called the featured offer expected price (FOEP). Featured offer is not guaranteed, because competing offers may change, and different offers may be featured based on other factors, including fulfillment capabilities to a specific customer. The response to an unsuccessful request includes the available error text.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 0.033 | 1 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](doc:usage-plans-and-rate-limits-in-the-sp-api).
     * @tag productPricing
     * @param array $data 
     * @return array
     *      - *responses* - 
     */
    public function getFeaturedOfferExpectedPriceBatch(array $data): array
    {
        return $this->api("/batches/products/pricing/2022-05-01/offer/featuredOfferExpectedPrice", 'POST', $data);
    }
    
}
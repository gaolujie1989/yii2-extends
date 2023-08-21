<?php

namespace lujie\amazon\sp\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The Selling Partner API for Replenishment (Replenishment API) provides programmatic access to replenishment program metrics and offers. These programs provide recurring delivery (automatic or manual) of any replenishable item at a frequency chosen by the customer.
*/
class Replenishment20221107 extends \lujie\amazon\sp\BaseAmazonSPClient
{

                
    /**
     * @description Returns aggregated replenishment program metrics for a selling partner. 

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 1 | 1 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag sellingpartners
     * @param array $data 
     * @return array
     *      - *metrics* - array
     *          - A list of metrics data for the selling partner.
     */
    public function getSellingPartnerMetrics(array $data): array
    {
        return $this->api("/replenishment/2022-11-07/sellingPartners/metrics/search", 'POST', $data);
    }
                    
    /**
     * @description Returns aggregated replenishment program metrics for a selling partner's offers.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 1 | 1 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag offers
     * @param array $data 
     * @return array
     *      - *offers* - array
     *          - A list of offers and associated metrics.
     *      - *pagination* - 
     *          - Use these parameters to paginate through the response.
     */
    public function listOfferMetrics(array $data): array
    {
        return $this->api("/replenishment/2022-11-07/offers/metrics/search", 'POST', $data);
    }
                    
    /**
     * @description Returns the details of a selling partner's replenishment program offers. Note that this operation only supports sellers at this time.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 1 | 1 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag offers
     * @param array $data 
     * @return array
     *      - *offers* - array
     *          - A list of offers.
     *      - *pagination* - 
     *          - Use these parameters to paginate through the response.
     */
    public function listOffers(array $data): array
    {
        return $this->api("/replenishment/2022-11-07/offers/search", 'POST', $data);
    }
    
}

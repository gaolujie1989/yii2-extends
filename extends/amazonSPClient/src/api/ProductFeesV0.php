<?php

namespace lujie\amazon\sp\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The Selling Partner API for Product Fees lets you programmatically retrieve estimated fees for a product. You can then account for those fees in your pricing.
*/
class ProductFeesV0 extends \lujie\amazon\sp\BaseAmazonSPClient
{

                
    /**
     * @description Returns the estimated fees for the item indicated by the specified seller SKU in the marketplace specified in the request body.

**Note:** The parameters associated with this operation may contain special characters that require URL encoding to call the API. To avoid errors with SKUs when encoding URLs, refer to [URL Encoding](https://developer-docs.amazon.com/sp-api/docs/url-encoding).

You can call `getMyFeesEstimateForSKU` for an item on behalf of a selling partner before the selling partner sets the item's price. The selling partner can then take any estimated fees into account. Each fees estimate request must include an original identifier. This identifier is included in the fees estimate so that you can correlate a fees estimate with the original request.

**Note:** This identifier value is used to identify an estimate. Actual costs may vary. Search "fees" in [Seller Central](https://sellercentral.amazon.com/) and consult the store-specific fee schedule for the most up-to-date information.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 1 | 2 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](doc:usage-plans-and-rate-limits-in-the-sp-api).
     * @tag fees
     * @param string $sellerSKU Used to identify an item in the given marketplace. SellerSKU is qualified by the seller's SellerId, which is included with every operation that you submit.
     * @param array $data 
     * @return array
     *      - *payload* - 
     *          - The payload for the operation.
     *      - *errors* - 
     */
    public function getMyFeesEstimateForSKU(string $sellerSKU, array $data): array
    {
        return $this->api("/products/fees/v0/listings/{$sellerSKU}/feesEstimate", 'POST', $data);
    }
                        
    /**
     * @description Returns the estimated fees for the item indicated by the specified ASIN in the marketplace specified in the request body.

You can call `getMyFeesEstimateForASIN` for an item on behalf of a selling partner before the selling partner sets the item's price. The selling partner can then take estimated fees into account. Each fees request must include an original identifier. This identifier is included in the fees estimate so you can correlate a fees estimate with the original request.

**Note:** This identifier value is used to identify an estimate. Actual costs may vary. Search "fees" in [Seller Central](https://sellercentral.amazon.com/) and consult the store-specific fee schedule for the most up-to-date information.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 1 | 2 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](doc:usage-plans-and-rate-limits-in-the-sp-api).
     * @tag fees
     * @param string $asin The Amazon Standard Identification Number (ASIN) of the item.
     * @param array $data 
     * @return array
     *      - *payload* - 
     *          - The payload for the operation.
     *      - *errors* - 
     */
    public function getMyFeesEstimateForASIN(string $asin, array $data): array
    {
        return $this->api("/products/fees/v0/items/{$asin}/feesEstimate", 'POST', $data);
    }
                        
    /**
     * @description Returns the estimated fees for a list of products.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 0.5 | 1 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](doc:usage-plans-and-rate-limits-in-the-sp-api).
     * @tag fees
     * @param array $data 
     * @return array
     */
    public function getMyFeesEstimates(array $data): array
    {
        return $this->api("/products/fees/v0/feesEstimate", 'POST', $data);
    }
        
}

<?php

namespace lujie\amazon\sp\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The Selling Partner API for Listings Items (Listings Items API) provides programmatic access to selling partner listings on Amazon. Use this API in collaboration with the Selling Partner API for Product Type Definitions, which you use to retrieve the information about Amazon product types needed to use the Listings Items API.

For more information, see the [Listing Items API Use Case Guide](doc:listings-items-api-v2020-09-01-use-case-guide).
*/
class ListingsItems20200901 extends \lujie\amazon\sp\BaseAmazonSPClient
{

                
    /**
     * @description Delete a listings item for a selling partner.

**Note:** The parameters associated with this operation may contain special characters that must be encoded to successfully call the API. To avoid errors with SKUs when encoding URLs, refer to [URL Encoding](https://developer-docs.amazon.com/sp-api/docs/url-encoding).

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 5 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag listings
     * @param string $sellerId A selling partner identifier, such as a merchant account or vendor code.
     * @param string $sku A selling partner provided identifier for an Amazon listing.
     * @param array $query
     *      - *marketplaceIds* - array - required
     *          - A comma-delimited list of Amazon marketplace identifiers for the request.
     *      - *issueLocale* - string - optional
     *          - A locale for localization of issues. When not provided, the default language code of the first marketplace is used. Examples: "en_US", "fr_CA", "fr_FR". Localized messages default to "en_US" when a localization is not available in the specified locale.
     * @return array
     *      - *sku* - string
     *          - A selling partner provided identifier for an Amazon listing.
     *      - *status* - string
     *          - The status of the listings item submission.
     *      - *submissionId* - string
     *          - The unique identifier of the listings item submission.
     *      - *issues* - array
     *          - Listings item issues related to the listings item submission.
     */
    public function deleteListingsItem(string $sellerId, string $sku, array $query): array
    {
        return $this->api(array_merge(["/listings/2020-09-01/items/{$sellerId}/{$sku}"], $query), 'DELETE');
    }
                
    /**
     * @description Partially update (patch) a listings item for a selling partner. Only top-level listings item attributes can be patched. Patching nested attributes is not supported.

**Note:** The parameters associated with this operation may contain special characters that must be encoded to successfully call the API. To avoid errors with SKUs when encoding URLs, refer to [URL Encoding](https://developer-docs.amazon.com/sp-api/docs/url-encoding).

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 5 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag listings
     * @param string $sellerId A selling partner identifier, such as a merchant account or vendor code.
     * @param string $sku A selling partner provided identifier for an Amazon listing.
     * @param array $query
     *      - *marketplaceIds* - array - required
     *          - A comma-delimited list of Amazon marketplace identifiers for the request.
     *      - *issueLocale* - string - optional
     *          - A locale for localization of issues. When not provided, the default language code of the first marketplace is used. Examples: "en_US", "fr_CA", "fr_FR". Localized messages default to "en_US" when a localization is not available in the specified locale.
     * @param array $data 
     * @return array
     *      - *sku* - string
     *          - A selling partner provided identifier for an Amazon listing.
     *      - *status* - string
     *          - The status of the listings item submission.
     *      - *submissionId* - string
     *          - The unique identifier of the listings item submission.
     *      - *issues* - array
     *          - Listings item issues related to the listings item submission.
     */
    public function patchListingsItem(string $sellerId, string $sku, array $query, array $data): array
    {
        return $this->api(array_merge(["/listings/2020-09-01/items/{$sellerId}/{$sku}"], $query), 'PATCH', $data);
    }
                
    /**
     * @description Creates a new or fully-updates an existing listings item for a selling partner.

**Note:** The parameters associated with this operation may contain special characters that must be encoded to successfully call the API. To avoid errors with SKUs when encoding URLs, refer to [URL Encoding](https://developer-docs.amazon.com/sp-api/docs/url-encoding).

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 5 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag listings
     * @param string $sellerId A selling partner identifier, such as a merchant account or vendor code.
     * @param string $sku A selling partner provided identifier for an Amazon listing.
     * @param array $query
     *      - *marketplaceIds* - array - required
     *          - A comma-delimited list of Amazon marketplace identifiers for the request.
     *      - *issueLocale* - string - optional
     *          - A locale for localization of issues. When not provided, the default language code of the first marketplace is used. Examples: "en_US", "fr_CA", "fr_FR". Localized messages default to "en_US" when a localization is not available in the specified locale.
     * @param array $data 
     * @return array
     *      - *sku* - string
     *          - A selling partner provided identifier for an Amazon listing.
     *      - *status* - string
     *          - The status of the listings item submission.
     *      - *submissionId* - string
     *          - The unique identifier of the listings item submission.
     *      - *issues* - array
     *          - Listings item issues related to the listings item submission.
     */
    public function putListingsItem(string $sellerId, string $sku, array $query, array $data): array
    {
        return $this->api(array_merge(["/listings/2020-09-01/items/{$sellerId}/{$sku}"], $query), 'PUT', $data);
    }
    
}

<?php

namespace lujie\amazon\sp\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The Selling Partner API for Catalog Items provides programmatic access to information about items in the Amazon catalog.

For more information, refer to the [Catalog Items API Use Case Guide](doc:catalog-items-api-v2022-04-01-use-case-guide).
*/
class CatalogItems20220401 extends \lujie\amazon\sp\BaseAmazonSPClient
{

                
    /**
     * @description Search for and return a list of Amazon catalog items and associated information either by identifier or by keywords.

**Usage Plans:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 2 | 2 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may observe higher rate and burst values than those shown here. For more information, refer to the [Usage Plans and Rate Limits in the Selling Partner API](doc:usage-plans-and-rate-limits-in-the-sp-api).
     * @tag catalog
     * @param array $query
     *      - *identifiers* - array - optional
     *          - A comma-delimited list of product identifiers to search the Amazon catalog for. **Note:** Cannot be used with `keywords`.
     *      - *identifiersType* - string - optional
     *          - Type of product identifiers to search the Amazon catalog for. **Note:** Required when `identifiers` are provided.
     *      - *marketplaceIds* - array - required
     *          - A comma-delimited list of Amazon marketplace identifiers for the request.
     *      - *includedData* - array - optional
     *          - A comma-delimited list of data sets to include in the response. Default: `summaries`.
     *      - *locale* - string - optional
     *          - Locale for retrieving localized summaries. Defaults to the primary locale of the marketplace.
     *      - *sellerId* - string - optional
     *          - A selling partner identifier, such as a seller account or vendor code. **Note:** Required when `identifiersType` is `SKU`.
     *      - *keywords* - array - optional
     *          - A comma-delimited list of words to search the Amazon catalog for. **Note:** Cannot be used with `identifiers`.
     *      - *brandNames* - array - optional
     *          - A comma-delimited list of brand names to limit the search for `keywords`-based queries. **Note:** Cannot be used with `identifiers`.
     *      - *classificationIds* - array - optional
     *          - A comma-delimited list of classification identifiers to limit the search for `keywords`-based queries. **Note:** Cannot be used with `identifiers`.
     *      - *pageSize* - integer - optional
     *          - Number of results to be returned per page.
     *      - *pageToken* - string - optional
     *          - A token to fetch a certain page when there are multiple pages worth of results.
     *      - *keywordsLocale* - string - optional
     *          - The language of the keywords provided for `keywords`-based queries. Defaults to the primary locale of the marketplace. **Note:** Cannot be used with `identifiers`.
     * @return array
     *      - *numberOfResults* - integer
     *          - For `identifiers`-based searches, the total number of Amazon catalog items found. For `keywords`-based searches, the estimated total number of Amazon catalog items matched by the search query (only results up to the page count limit will be returned per request regardless of the number found).

Note: The maximum number of items (ASINs) that can be returned and paged through is 1000.
     *      - *pagination* - 
     *          - If available, the `nextToken` and/or `previousToken` values required to return paginated results.
     *      - *refinements* - 
     *          - Search refinements for `keywords`-based searches.
     *      - *items* - array
     *          - A list of items from the Amazon catalog.
     */
    public function searchCatalogItems(array $query): array
    {
        return $this->api(array_merge(["/catalog/2022-04-01/items"], $query));
    }
                    
    /**
     * @description Retrieves details for an item in the Amazon catalog.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 2 | 2 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may observe higher rate and burst values than those shown here. For more information, refer to the [Usage Plans and Rate Limits in the Selling Partner API](doc:usage-plans-and-rate-limits-in-the-sp-api).
     * @tag catalog
     * @param string $asin The Amazon Standard Identification Number (ASIN) of the item.
     * @param array $query
     *      - *marketplaceIds* - array - required
     *          - A comma-delimited list of Amazon marketplace identifiers. Data sets in the response contain data only for the specified marketplaces.
     *      - *includedData* - array - optional
     *          - A comma-delimited list of data sets to include in the response. Default: `summaries`.
     *      - *locale* - string - optional
     *          - Locale for retrieving localized summaries. Defaults to the primary locale of the marketplace.
     * @return array
     *      - *asin* - 
     *      - *attributes* - 
     *      - *dimensions* - 
     *      - *identifiers* - 
     *      - *images* - 
     *      - *productTypes* - 
     *      - *relationships* - 
     *      - *salesRanks* - 
     *      - *summaries* - 
     *      - *vendorDetails* - 
     */
    public function getCatalogItem(string $asin, array $query): array
    {
        return $this->api(array_merge(["/catalog/2022-04-01/items/{$asin}"], $query));
    }
    
}

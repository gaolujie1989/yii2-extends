<?php

namespace lujie\amazon\sp\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The Selling Partner API for Catalog Items helps you programmatically retrieve item details for items in the catalog.
*/
class CatalogItemsV0 extends \lujie\amazon\sp\BaseAmazonSPClient
{

                
    /**
     * @description Effective September 30, 2022, the `listCatalogItems` operation will no longer be available in the Selling Partner API for Catalog Items v0. As an alternative, `searchCatalogItems` is available in the latest version of the [Selling Partner API for Catalog Items v2022-04-01](doc:catalog-items-api-v2022-04-01-reference). Integrations that rely on the `listCatalogItems` operation should migrate to the `searchCatalogItems`operation to avoid service disruption. 
_Note:_ The [`listCatalogCategories`](#get-catalogv0categories) operation is not being deprecated and you can continue to make calls to it.
     * @tag catalog
     * @param array $query
     *      - *MarketplaceId* - string - required
     *          - A marketplace identifier. Specifies the marketplace for which items are returned.
     *      - *Query* - string - optional
     *          - Keyword(s) to use to search for items in the catalog. Example: 'harry potter books'.
     *      - *QueryContextId* - string - optional
     *          - An identifier for the context within which the given search will be performed. A marketplace might provide mechanisms for constraining a search to a subset of potential items. For example, the retail marketplace allows queries to be constrained to a specific category. The QueryContextId parameter specifies such a subset. If it is omitted, the search will be performed using the default context for the marketplace, which will typically contain the largest set of items.
     *      - *SellerSKU* - string - optional
     *          - Used to identify an item in the given marketplace. SellerSKU is qualified by the seller's SellerId, which is included with every operation that you submit.
     *      - *UPC* - string - optional
     *          - A 12-digit bar code used for retail packaging.
     *      - *EAN* - string - optional
     *          - A European article number that uniquely identifies the catalog item, manufacturer, and its attributes.
     *      - *ISBN* - string - optional
     *          - The unique commercial book identifier used to identify books internationally.
     *      - *JAN* - string - optional
     *          - A Japanese article number that uniquely identifies the product, manufacturer, and its attributes.
     * @return array
     *      - *payload* - 
     *          - The payload for the listCatalogItems operation.
     *      - *errors* - 
     *          - One or more unexpected errors occurred during the listCatalogItems operation.
     */
    public function listCatalogItems(array $query): array
    {
        return $this->api(array_merge(["/catalog/v0/items"], $query));
    }
                    
    /**
     * @description Effective September 30, 2022, the `getCatalogItem` operation will no longer be available in the Selling Partner API for Catalog Items v0. This operation is available in the latest version of the [Selling Partner API for Catalog Items v2022-04-01](doc:catalog-items-api-v2022-04-01-reference). Integrations that rely on this operation should migrate to the latest version to avoid service disruption. 
_Note:_ The [`listCatalogCategories`](#get-catalogv0categories) operation is not being deprecated and you can continue to make calls to it.
     * @tag catalog
     * @param string $asin The Amazon Standard Identification Number (ASIN) of the item.
     * @param array $query
     *      - *MarketplaceId* - string - required
     *          - A marketplace identifier. Specifies the marketplace for the item.
     * @return array
     *      - *payload* - 
     *          - The payload for the getCatalogItem operation.
     *      - *errors* - 
     *          - One or more unexpected errors occurred during the getCatalogItem operation.
     */
    public function getCatalogItem(string $asin, array $query): array
    {
        return $this->api(array_merge(["/catalog/v0/items/{$asin}"], $query));
    }
                    
    /**
     * @description Returns the parent categories to which an item belongs, based on the specified ASIN or SellerSKU.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 1 | 2 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](doc:usage-plans-and-rate-limits-in-the-sp-api).
     * @tag catalog
     * @param array $query
     *      - *MarketplaceId* - string - required
     *          - A marketplace identifier. Specifies the marketplace for the item.
     *      - *ASIN* - string - optional
     *          - The Amazon Standard Identification Number (ASIN) of the item.
     *      - *SellerSKU* - string - optional
     *          - Used to identify items in the given marketplace. SellerSKU is qualified by the seller's SellerId, which is included with every operation that you submit.
     * @return array
     *      - *payload* - 
     *          - The payload for the listCatalogCategories operation.
     *      - *errors* - 
     *          - One or more unexpected errors occurred during the listCatalogCategories operation.
     */
    public function listCatalogCategories(array $query): array
    {
        return $this->api(array_merge(["/catalog/v0/categories"], $query));
    }
    
}

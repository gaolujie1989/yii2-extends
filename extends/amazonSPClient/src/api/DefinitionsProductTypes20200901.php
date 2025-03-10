<?php

namespace lujie\amazon\sp\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The Selling Partner API for Product Type Definitions provides programmatic access to attribute and data requirements for product types in the Amazon catalog. Use this API to return the JSON Schema for a product type that you can then use with other Selling Partner APIs, such as the Selling Partner API for Listings Items, the Selling Partner API for Catalog Items, and the Selling Partner API for Feeds (for JSON-based listing feeds).

For more information, see the [Product Type Definitions API Use Case Guide](doc:product-type-api-use-case-guide).
*/
class DefinitionsProductTypes20200901 extends \lujie\amazon\sp\BaseAmazonSPClient
{

                
    /**
     * @description Search for and return a list of Amazon product types that have definitions available.

**Usage Plans:**

| Plan type | Rate (requests per second) | Burst |
| ---- | ---- | ---- |
|Default| 5 | 10 |
|Selling partner specific| Variable | Variable |

The x-amzn-RateLimit-Limit response header returns the usage plan rate limits that were applied to the requested operation. Rate limits for some selling partners will vary from the default rate and burst shown in the table above. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](doc:usage-plans-and-rate-limits-in-the-sp-api).
     * @tag definitions
     * @param array $query
     *      - *keywords* - array - optional
     *          - A comma-delimited list of keywords to search product types. **Note:** Cannot be used with `itemName`.
     *      - *marketplaceIds* - array - required
     *          - A comma-delimited list of Amazon marketplace identifiers for the request.
     *      - *itemName* - string - optional
     *          - The title of the ASIN to get the product type recommendation. **Note:** Cannot be used with `keywords`.
     *      - *locale* - string - optional
     *          - The locale for the display names in the response. Defaults to the primary locale of the marketplace.
     *      - *searchLocale* - string - optional
     *          - The locale used for the `keywords` and `itemName` parameters. Defaults to the primary locale of the marketplace.
     * @return array
     *      - *productTypes* - array
     *      - *productTypeVersion* - string
     *          - Amazon product type version identifier.
     */
    public function searchDefinitionsProductTypes(array $query): array
    {
        return $this->api(array_merge(["/definitions/2020-09-01/productTypes"], $query));
    }
                        
    /**
     * @description Retrieve an Amazon product type definition.

**Usage Plans:**

| Plan type | Rate (requests per second) | Burst |
| ---- | ---- | ---- |
|Default| 5 | 10 |
|Selling partner specific| Variable | Variable |

The x-amzn-RateLimit-Limit response header returns the usage plan rate limits that were applied to the requested operation. Rate limits for some selling partners will vary from the default rate and burst shown in the table above. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](doc:usage-plans-and-rate-limits-in-the-sp-api).
     * @tag definitions
     * @param string $productType The Amazon product type name.
     * @param array $query
     *      - *sellerId* - string - optional
     *          - A selling partner identifier. When provided, seller-specific requirements and values are populated within the product type definition schema, such as brand names associated with the selling partner.
     *      - *marketplaceIds* - array - required
     *          - A comma-delimited list of Amazon marketplace identifiers for the request.
Note: This parameter is limited to one marketplaceId at this time.
     *      - *productTypeVersion* - string - optional
     *          - The version of the Amazon product type to retrieve. Defaults to "LATEST",. Prerelease versions of product type definitions may be retrieved with "RELEASE_CANDIDATE". If no prerelease version is currently available, the "LATEST" live version will be provided.
     *      - *requirements* - string - optional
     *          - The name of the requirements set to retrieve requirements for.
     *      - *requirementsEnforced* - string - optional
     *          - Identifies if the required attributes for a requirements set are enforced by the product type definition schema. Non-enforced requirements enable structural validation of individual attributes without all the required attributes being present (such as for partial updates).
     *      - *locale* - string - optional
     *          - Locale for retrieving display labels and other presentation details. Defaults to the default language of the first marketplace in the request.
     * @return array
     *      - *metaSchema* - 
     *          - Link to meta-schema describing the vocabulary used by the product type schema.
     *      - *schema* - 
     *          - Link to schema describing the attributes and requirements for the product type.
     *      - *requirements* - string
     *          - Name of the requirements set represented in this product type definition.
     *      - *requirementsEnforced* - string
     *          - Identifies if the required attributes for a requirements set are enforced by the product type definition schema. Non-enforced requirements enable structural validation of individual attributes without all of the required attributes being present (such as for partial updates).
     *      - *propertyGroups* - object
     *          - Mapping of property group names to property groups. Property groups represent logical groupings of schema properties that can be used for display or informational purposes.
     *      - *locale* - string
     *          - Locale of the display elements contained in the product type definition.
     *      - *marketplaceIds* - array
     *          - Amazon marketplace identifiers for which the product type definition is applicable.
     *      - *productType* - string
     *          - The name of the Amazon product type that this product type definition applies to.
     *      - *displayName* - string
     *          - Human-readable and localized description of the Amazon product type.
     *      - *productTypeVersion* - 
     *          - The version details for the Amazon product type.
     */
    public function getDefinitionsProductType(string $productType, array $query): array
    {
        return $this->api(array_merge(["/definitions/2020-09-01/productTypes/{$productType}"], $query));
    }
        
}

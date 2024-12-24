<?php

namespace lujie\amazon\advertising\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The Amazon Product Selector API allows integrators to receive product metadata such as inventory status, price, eligibility status and product details for SKUS or ASINs in their Product Catalog in order to launch, manage or optimize Sponsored Product, Sponsored Brands or Sponsored Display advertising campaigns. The Product Selector API is available to Sellers, Vendors, and Authors. Note that for vendors it fetches inventory based on the vendor codes so the result could be different from elsewhere.
*/
class ProductSelectorV3 extends \lujie\amazon\advertising\BaseAmazonAdvertisingClient
{

                
    /**
     * @description 
     * @tag Product Selector
     * @param array $data 
     *      - *checkItemDetails* - boolean
     *          - Whether item details such as name, image, and price is required.
     *      - *skus* - array
     *          - Specific SKUs to search for in the advertiser's inventory. Currently only support SP program type for sellers. Cannot use together with asins or searchStr input types.
     *      - *checkEligibility* - boolean
     *          - Whether advertising eligibility info is required
     *      - *isGlobalStoreSelection* - boolean
     *          -  This will return only GlobalStore listings related to [GlobalStore Program](https://sellercentral.amazon.com/help/hub/reference/external/202139180) and not local listings
     *      - *pageSize* - integer
     *          - Number of items to be returned on this page index.
     *      - *locale* - string
     *          - Optional locale for detail and eligibility response strings. Default to the marketplace locale.
     *      - *asins* - array
     *          - Specific asins to search for in the advertiser's inventory. Cannot use together with skus or searchStr input types.
     *      - *cursorToken* - string
     *          - Pagination token used for the suggested sort type or for author merchant
     *      - *adType* - string
     *          - Program type. Required if checks advertising eligibility: 
 * SP - Sponsored Product 
 * SB - Sponsored Brand 
 * SD - Sponsored Display
     *      - *searchStr* - string
     *          - Specific string in the item title to search for in the advertiser's inventory. Case insensitive. Cannot use together with asins or skus input types.
     *      - *pageIndex* - integer
     *          - Index of the page to be returned; For author, this value will be ignored, should use cursorToken instead. For seller and vendor, results are capped at 10k ((pageIndex + 1) * pageSize).
     *      - *sortOrder* - string
     *          - Sort order (has to be DESC for the suggested sort type): 
 * ASC - Ascending, from A to Z 
 * DESC - Descending, from Z to A
     *      - *sortBy* - string
     *          - Sort option for the result. Currently only support SP program type for sellers: 
 * SUGGESTED - Suggested products are those most likely to engage customers, and have a higher chance of generating clicks if advertised. 
 * CREATED_DATE - Date the item listing was created 
     * @return array
     *      - *cursorToken* - string
     *          - Pagination token for later requests with specific sort type to use as the page index instead. Empty cursorToken means no further data is present at Server side.
     *      - *ProductMetadataList* - array
     */
    public function productMetadata(array $data): array
    {
        return $this->api("/product/metadata", 'POST', $data, ['content-type' => 'application/vnd.productmetadatarequest.v1+json']);
    }
    
}

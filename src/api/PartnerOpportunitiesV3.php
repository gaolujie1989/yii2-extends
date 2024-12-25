<?php

namespace lujie\amazon\advertising\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description <br><br>**Purpose of the API**<br><br>The API is for partners to retrieve **'opportunities'** relevant to optimizing their book of business. Opportunities are actionable insights for partners that drive value to advertisers.<br><br>For a step by step guide to using our API, we recommend our official [developer guide](https://advertising.amazon.com/API/docs/en-us/guides/recommendations/partner-opportunities/overview).<br><br>**How should partners use this set of APIs?**<br><br>Partners should use this set of APIs by first calling the **GET** `/partnerOpportunities` route to obtain a list of opportunities that are pertinent (and exclusive) to their book of business (the advertisers they manage).<br><br>It should be noted that the opportunities returned in this list call may not have data for that day (opportunities are updated every 24 hours). To check if an opportunity has data, introspect on the value of the `opportunities[].dataMetadata.rowCount` field. If the value is greater than zero, data is available and a data file can be downloaded.<br><br>Once an opportunity of interest has been identified, the given opportunity data can be downloaded using the **GET** `/partnerOpportunities/{partnerOpportunityId}/file` route, which will redirect to a pre-signed file download URL containing all opportunity data in a CSV format. This operation will return a 404 if no data is available for the given opportunity.<br><br>Partners can get aggregated information about opportunities available to them using the **GET** `/partnerOpportunities/summary` route. This information includes total opportunity count, count of opportunities with data available to be downloaded, and a count of unique advertisers across all opportunities. <br><br>**Required Headers**<br><br>Currently, there are two headers that are required for all API calls. If these headers are not correctly provided and properly formatted, the API call requests will fail.<br><br>1. `Accept:` Must be set to a supported API version (see below), using the format described on the [Advertising API portal](https://advertising.amazon.com/API/docs/en-us/concepts/compatibility-versioning-policy). The request and response payloads are identical between versions v1, v1.1, and v1.2.<br>    - Version 1 (Recommended): `'application/vnd.partneropportunity.v1+json'`<br>    - Version 1.1: `'application/vnd.partneropportunity.v1.1+json'`<br>    - Version 1.2: `'application/vnd.partneropportunity.v1.2+json'`<br>  <br>2. `Amazon-Advertising-API-Manager-Account:` 'Partner Network Account ID' which is accessible from Partner Network under the ['User settings'](https://advertising.amazon.com/partner-network/settings) link in the upper right corner.<br>    - Example: `'amzn1.ads1.ma1.abcd1234...'`<br><br>**Applying Opportunities**<br>Partners can take action against opportunities based on their business needs. For example, partners may choose to use existing Advertising API resources to launch new ASINs or modify campaign settings. Some entries in your opportunity data file will have `recommendationId`s and `applyApiEndPoint`s. These opportunities will have objective type `AMAZON_ACCOUNT_TEAM_RECOMMENDATIONS` and can be supplied back to the `/apply` endpoint to programmatically take action on each opportunity.<br><br>See [How to use the Partner Opportunities API](https://advertising.amazon.com/API/docs/en-us/guides/recommendations/partner-opportunities/how-to) for additional details. <br><br>**Advanced Usage**<br><br>**Pagination**<br><br>The `/partnerOpportunities` route supports pagination of results via query parameters. <br><br>**GET** `/partnerOpportunities` calls support using *optional* query parameters `maxResults` and `nextToken` for paginated requests and responses. <br>**GET** `/partnerOpportunities` responses include tokens which can be used to navigate to the first, previous, next, and last pages of results. To navigate to the desired page, pass one of these provided tokens as a query parameter for the next call to **GET** `/partnerOpportunities`.<br><br>Examples:<br>- **GET** `/partnerOpportunities?maxResults=10` will return 10 opportunities and a valid series of pagination tokens.<br>- **GET** `/partnerOpportunities?maxResults=10&nextToken=[next-page-token]` will get the next 10 opportunities. <br><br>**Filtering**<br><br>The `/partnerOpportunities` and `/partnerOpportunities/summary` routes support filtering results via query parameters.<br><br>**GET** `/partnerOpportunities` calls support using *optional* query parameters `audience`, `objectiveType`, and `product` for filtering responses.<br>**GET** `/partnerOpportunities` responses will include only opportunities which have values matching the requested filters. If no filters are specified, all opportunities are returned.<br><br>Examples:<br>- **GET** `/partnerOpportunities?objectiveType=UNLAUNCHED_ASINS&product=AMAZON_DSP,SPONSORED_PRODUCTS` will return all opportunities which have a `objectiveType` of `UNLAUNCHED_ASINS` and a `product` of `AMAZON_DSP` OR `SPONSORED_PRODUCTS`.<br><br>**GET** `/partnerOpportunities/summary` calls support using *optional* query parameters `audience`, `objectiveType`, and `product` for filtering responses.<br>**GET** `/partnerOpportunities/summary` responses will include metadata for the filter values for opportunities under the `availableAudiences`, `availableObjectiveTypes`, etc. fields. This metadata will include the available filter values along with the number of opportunities for those filter values.<br><br>Examples:<br>- **GET** `/partnerOpportunities/summary?product=SPONSORED_PRODUCTS` will provide a summary of all opportunities that have a `product` value of `SPONSORED_PRODUCTS`.<br><br>**Localization**<br><br>The `/partnerOpportunities` route supports a `locale` query parameter for retrieving opportunities in a localized manner.<br><br>**GET** `/partnerOpportunities` calls support the *optional* query parameter `locale` for localizing responses.<br>**GET** `/partnerOpportunities` responses will be localized to the requested `locale` if possible and filtered out if not.<br><br>Example:<br>- **GET** `/partnerOpportunities?locale=zh_CN` to request opportunity responses localized in Chinese.<br><br>**Additional Resources**<br><br>For more information on **CURL** command formatting, please see [the Amazon Ads Website](https://advertising.amazon.com/API/docs/en-us/getting-started/first-call).<br>
*/
class PartnerOpportunitiesV3 extends \lujie\amazon\advertising\BaseAmazonAdvertisingClient
{

                
    /**
     * @description Applies a given set of recommendations. Application may be asynchronous. Application status may be checked using the applicationStatus operation. Note that all required parameters are retrieved from opportunity data.

**Authorized resource type**:
Global Manager Account ID

**Parameter name**:
Amazon-Advertising-API-Manager-Account

**Parameter in**:
header

**Requires one of these permissions**:
["MasterAccount_Manager","ManagerAccount_Dev"]     * @tag Partner Opportunities
     * @param string $partnerOpportunityId 
     * @param array $data 
     *      - *encryptedAdvertiserId* - string
     *          - The encrypted advertiser ID.

Provided in opportunity data.
     *      - *marketplace* - 
     *      - *recommendationIds* - array
     *          - A list of recommendation IDs to apply for the given opportunity.
     *      - *entityId* - string
     *          - Entity ID

Provided in opportunity data.
     *      - *advertiserType* - string
     *          - Entity Type

Provided in opportunity data as 'advertiserType'.
     */
    public function partnerOpportunitiesApply(string $partnerOpportunityId, array $data, string $contentType = 'application/vnd.partneropportunity.v1.2+json'): void
    {
        $this->api("/partnerOpportunities/{$partnerOpportunityId}/apply", 'POST', $data, ['content-type' => $contentType, 'accept' => $contentType]);
    }
                    
    /**
     * @description Gets a 307 - TEMPORARY_REDIRECT to an opportunity data file.

**Authorized resource type**:
Global Manager Account ID

**Parameter name**:
Amazon-Advertising-API-Manager-Account

**Parameter in**:
header

**Requires one of these permissions**:
["MasterAccount_Manager","ManagerAccount_Dev"]     * @tag Partner Opportunities
     * @param string $partnerOpportunityId The opportunity ID for which the file URL is desired.
     * @param array $query
     *      - *fileFormat* - string - optional
     *          - Specify the desired file format for the opportunity data. Default is CSV.
     */
    public function partnerOpportunitiesGetOpportunityFile(string $partnerOpportunityId, array $query = []): void
    {
        $this->api(array_merge(["/partnerOpportunities/{$partnerOpportunityId}/file"], $query));
    }
                
    /**
     * @description Gets a list of opportunities specific to the partner making the request.

**Authorized resource type**:
Global Manager Account ID

**Parameter name**:
Amazon-Advertising-API-Manager-Account

**Parameter in**:
header

**Requires one of these permissions**:
["MasterAccount_Manager","ManagerAccount_Dev"]     * @tag Partner Opportunities
     * @param array $query
     *      - *maxResults* - int - optional
     *          - The maximum number of results to return in a single page.
     *      - *nextToken* - string - optional
     *          - An obfuscated cursor value that indicates which 'page' of results should be returned next.
     *      - *locale* -  - optional
     *          - The desired locale for opportunity data to be presented in. The `title`, `description`, and `callToAction` fields of the response items support localization.
     *      - *retrieveTranslationKeys* - boolean - optional
     *          - 
     *      - *audience* - array - optional
     *          - Filter for opportunities with these audience values.
* PARTNER_MANAGED_ADVERTISERS - Recommendation relates to advertisers the partner manages.
* PARTNER_MANAGED_AD_BUSINESS - Recommendation relates to other partners the partner interacts with.
* PARTNER - Recommendation relates to you, the partner.
     *      - *objectiveType* - array - optional
     *          - Filter for opportunities with these objectiveType values.
* AD_API_ENDPOINT_ADOPTION - Recommendation relates to adopting a new API endpoint.
* AMAZON_ACCOUNT_TEAM_RECOMMENDATIONS - Recommendation is provided by the Amazon Ads Account Team.
* CAMPAIGN_OPTIMIZATION - Recommendation relates to optimizing campaigns.
* CATEGORY_INSIGHTS - Recommendation relates to advertising insights across product categories..
* CLICK_CREDITS - Recommendation relates to available click credits.
* DEALS - Recommendation relates to deals.
* MARKETPLACE_EXPANSION - Recommendation relates to expanding to new marketplaces.
* NEW_TO_BRAND_INSIGHTS - Recommendation relates to new to brand advertising insights.
* PARTNER_GROWTH - Recommendation relates to growing your business as a partner.
* PATH_TO_PURCHASE_INSIGHTS - Recommendation relates to path to purchase insights.
* RETAIL_INSIGHTS - Recommendation related to retail insights about products you manage.
* SHARE_OF_VOICE_INSIGHTS - Recommendation relates to share of voice for a particular audience.
* UNLAUNCHED_ASINS - Recommendation relates to ASINs you manage that are not enrolled in advertising campaigns.

     *      - *product* - array - optional
     *          - Filter for opportunities with these product values.
* AMAZON_DSP - Recommendation relates to the Amazon DSP.
* AMAZON_LIVE - Recommendation relates to Amazon's Live Show and Tell program.
* POSTS - Recommendation relates to Amazon's social media Posts service.
* SPONSORED_BRANDS - Recommendation relates to Sponsored Brands.
* SPONSORED_BRANDS_VIDEO - Recommendation relates to Sponsored Brands Video.
* SPONSORED_DISPLAY - Recommendation relates to Sponsored Display.
* SPONSORED_DISPLAY_VIDEO - Recommendation relates to Sponsored Display Video.
* SPONSORED_PRODUCTS - Recommendation relates to Sponsored Products.
* STORES - Recommendation relates to building a storefront page on Amazon.
* VIDEO_ADS - Deprecated value, replaced by SPONSORED_BRANDS_VIDEO and SPONSORED_DISPLAY_VIDEO values.
     * @return Iterator
     *      - *lastToken* - string
     *          - Pagination token to the last page.
     *      - *totalResults* - number
     *          - Total results contained in the list of opportunities.
     *      - *firstToken* - string
     *          - Pagination token back to the first page/element.
     *      - *nextToken* - string
     *          - Pagination token to the next page.
     *      - *opportunities* - array
     *          - The list of partner opportunities.
     *      - *prevToken* - string
     *          - Pagination token back to the previous page.
     */
    public function eachtnerOpportunitiesListOpportunities(array $query = [], string $contentType = 'application/vnd.partneropportunity.v1.2+json'): Iterator
    {
        return $this->eachInternal('partnerOpportunitiesListOpportunities', func_get_args());
    }
        
    /**
     * @description Gets a list of opportunities specific to the partner making the request.

**Authorized resource type**:
Global Manager Account ID

**Parameter name**:
Amazon-Advertising-API-Manager-Account

**Parameter in**:
header

**Requires one of these permissions**:
["MasterAccount_Manager","ManagerAccount_Dev"]     * @tag Partner Opportunities
     * @param array $query
     *      - *maxResults* - int - optional
     *          - The maximum number of results to return in a single page.
     *      - *nextToken* - string - optional
     *          - An obfuscated cursor value that indicates which 'page' of results should be returned next.
     *      - *locale* -  - optional
     *          - The desired locale for opportunity data to be presented in. The `title`, `description`, and `callToAction` fields of the response items support localization.
     *      - *retrieveTranslationKeys* - boolean - optional
     *          - 
     *      - *audience* - array - optional
     *          - Filter for opportunities with these audience values.
* PARTNER_MANAGED_ADVERTISERS - Recommendation relates to advertisers the partner manages.
* PARTNER_MANAGED_AD_BUSINESS - Recommendation relates to other partners the partner interacts with.
* PARTNER - Recommendation relates to you, the partner.
     *      - *objectiveType* - array - optional
     *          - Filter for opportunities with these objectiveType values.
* AD_API_ENDPOINT_ADOPTION - Recommendation relates to adopting a new API endpoint.
* AMAZON_ACCOUNT_TEAM_RECOMMENDATIONS - Recommendation is provided by the Amazon Ads Account Team.
* CAMPAIGN_OPTIMIZATION - Recommendation relates to optimizing campaigns.
* CATEGORY_INSIGHTS - Recommendation relates to advertising insights across product categories..
* CLICK_CREDITS - Recommendation relates to available click credits.
* DEALS - Recommendation relates to deals.
* MARKETPLACE_EXPANSION - Recommendation relates to expanding to new marketplaces.
* NEW_TO_BRAND_INSIGHTS - Recommendation relates to new to brand advertising insights.
* PARTNER_GROWTH - Recommendation relates to growing your business as a partner.
* PATH_TO_PURCHASE_INSIGHTS - Recommendation relates to path to purchase insights.
* RETAIL_INSIGHTS - Recommendation related to retail insights about products you manage.
* SHARE_OF_VOICE_INSIGHTS - Recommendation relates to share of voice for a particular audience.
* UNLAUNCHED_ASINS - Recommendation relates to ASINs you manage that are not enrolled in advertising campaigns.

     *      - *product* - array - optional
     *          - Filter for opportunities with these product values.
* AMAZON_DSP - Recommendation relates to the Amazon DSP.
* AMAZON_LIVE - Recommendation relates to Amazon's Live Show and Tell program.
* POSTS - Recommendation relates to Amazon's social media Posts service.
* SPONSORED_BRANDS - Recommendation relates to Sponsored Brands.
* SPONSORED_BRANDS_VIDEO - Recommendation relates to Sponsored Brands Video.
* SPONSORED_DISPLAY - Recommendation relates to Sponsored Display.
* SPONSORED_DISPLAY_VIDEO - Recommendation relates to Sponsored Display Video.
* SPONSORED_PRODUCTS - Recommendation relates to Sponsored Products.
* STORES - Recommendation relates to building a storefront page on Amazon.
* VIDEO_ADS - Deprecated value, replaced by SPONSORED_BRANDS_VIDEO and SPONSORED_DISPLAY_VIDEO values.
     * @return Iterator
     *      - *lastToken* - string
     *          - Pagination token to the last page.
     *      - *totalResults* - number
     *          - Total results contained in the list of opportunities.
     *      - *firstToken* - string
     *          - Pagination token back to the first page/element.
     *      - *nextToken* - string
     *          - Pagination token to the next page.
     *      - *opportunities* - array
     *          - The list of partner opportunities.
     *      - *prevToken* - string
     *          - Pagination token back to the previous page.
     */
    public function batchtnerOpportunitiesListOpportunities(array $query = [], string $contentType = 'application/vnd.partneropportunity.v1.2+json'): Iterator
    {
        return $this->batchInternal('partnerOpportunitiesListOpportunities', func_get_args());
    }
    
    /**
     * @description Gets a list of opportunities specific to the partner making the request.

**Authorized resource type**:
Global Manager Account ID

**Parameter name**:
Amazon-Advertising-API-Manager-Account

**Parameter in**:
header

**Requires one of these permissions**:
["MasterAccount_Manager","ManagerAccount_Dev"]     * @tag Partner Opportunities
     * @param array $query
     *      - *maxResults* - int - optional
     *          - The maximum number of results to return in a single page.
     *      - *nextToken* - string - optional
     *          - An obfuscated cursor value that indicates which 'page' of results should be returned next.
     *      - *locale* -  - optional
     *          - The desired locale for opportunity data to be presented in. The `title`, `description`, and `callToAction` fields of the response items support localization.
     *      - *retrieveTranslationKeys* - boolean - optional
     *          - 
     *      - *audience* - array - optional
     *          - Filter for opportunities with these audience values.
* PARTNER_MANAGED_ADVERTISERS - Recommendation relates to advertisers the partner manages.
* PARTNER_MANAGED_AD_BUSINESS - Recommendation relates to other partners the partner interacts with.
* PARTNER - Recommendation relates to you, the partner.
     *      - *objectiveType* - array - optional
     *          - Filter for opportunities with these objectiveType values.
* AD_API_ENDPOINT_ADOPTION - Recommendation relates to adopting a new API endpoint.
* AMAZON_ACCOUNT_TEAM_RECOMMENDATIONS - Recommendation is provided by the Amazon Ads Account Team.
* CAMPAIGN_OPTIMIZATION - Recommendation relates to optimizing campaigns.
* CATEGORY_INSIGHTS - Recommendation relates to advertising insights across product categories..
* CLICK_CREDITS - Recommendation relates to available click credits.
* DEALS - Recommendation relates to deals.
* MARKETPLACE_EXPANSION - Recommendation relates to expanding to new marketplaces.
* NEW_TO_BRAND_INSIGHTS - Recommendation relates to new to brand advertising insights.
* PARTNER_GROWTH - Recommendation relates to growing your business as a partner.
* PATH_TO_PURCHASE_INSIGHTS - Recommendation relates to path to purchase insights.
* RETAIL_INSIGHTS - Recommendation related to retail insights about products you manage.
* SHARE_OF_VOICE_INSIGHTS - Recommendation relates to share of voice for a particular audience.
* UNLAUNCHED_ASINS - Recommendation relates to ASINs you manage that are not enrolled in advertising campaigns.

     *      - *product* - array - optional
     *          - Filter for opportunities with these product values.
* AMAZON_DSP - Recommendation relates to the Amazon DSP.
* AMAZON_LIVE - Recommendation relates to Amazon's Live Show and Tell program.
* POSTS - Recommendation relates to Amazon's social media Posts service.
* SPONSORED_BRANDS - Recommendation relates to Sponsored Brands.
* SPONSORED_BRANDS_VIDEO - Recommendation relates to Sponsored Brands Video.
* SPONSORED_DISPLAY - Recommendation relates to Sponsored Display.
* SPONSORED_DISPLAY_VIDEO - Recommendation relates to Sponsored Display Video.
* SPONSORED_PRODUCTS - Recommendation relates to Sponsored Products.
* STORES - Recommendation relates to building a storefront page on Amazon.
* VIDEO_ADS - Deprecated value, replaced by SPONSORED_BRANDS_VIDEO and SPONSORED_DISPLAY_VIDEO values.
     * @return array
     *      - *lastToken* - string
     *          - Pagination token to the last page.
     *      - *totalResults* - number
     *          - Total results contained in the list of opportunities.
     *      - *firstToken* - string
     *          - Pagination token back to the first page/element.
     *      - *nextToken* - string
     *          - Pagination token to the next page.
     *      - *opportunities* - array
     *          - The list of partner opportunities.
     *      - *prevToken* - string
     *          - Pagination token back to the previous page.
     */
    public function partnerOpportunitiesListOpportunities(array $query = [], string $contentType = 'application/vnd.partneropportunity.v1.2+json'): array
    {
        return $this->api(array_merge(["/partnerOpportunities"], $query), ['content-type' => $contentType, 'accept' => $contentType]);
    }
                    
    /**
     * @description Gets aggregated information about all opportunities specific to the partner making the request. Supported since V1.1.

**Authorized resource type**:
Global Manager Account ID

**Parameter name**:
Amazon-Advertising-API-Manager-Account

**Parameter in**:
header

**Requires one of these permissions**:
["MasterAccount_Manager","ManagerAccount_Dev"]     * @tag Partner Opportunities
     * @param array $query
     *      - *audience* - array - optional
     *          - Filter for opportunities with these audience values.
* PARTNER_MANAGED_ADVERTISERS - Recommendation relates to advertisers the partner manages.
* PARTNER_MANAGED_AD_BUSINESS - Recommendation relates to other partners the partner interacts with.
* PARTNER - Recommendation relates to you, the partner.
     *      - *objectiveType* - array - optional
     *          - Filter for opportunities with these objectiveType values.
* AD_API_ENDPOINT_ADOPTION - Recommendation relates to adopting a new API endpoint.
* AMAZON_ACCOUNT_TEAM_RECOMMENDATIONS - Recommendation is provided by the Amazon Ads Account Team.
* CAMPAIGN_OPTIMIZATION - Recommendation relates to optimizing campaigns.
* CATEGORY_INSIGHTS - Recommendation relates to advertising insights across product categories..
* CLICK_CREDITS - Recommendation relates to available click credits.
* DEALS - Recommendation relates to deals.
* MARKETPLACE_EXPANSION - Recommendation relates to expanding to new marketplaces.
* NEW_TO_BRAND_INSIGHTS - Recommendation relates to new to brand advertising insights.
* PARTNER_GROWTH - Recommendation relates to growing your business as a partner.
* PATH_TO_PURCHASE_INSIGHTS - Recommendation relates to path to purchase insights.
* RETAIL_INSIGHTS - Recommendation related to retail insights about products you manage.
* SHARE_OF_VOICE_INSIGHTS - Recommendation relates to share of voice for a particular audience.
* UNLAUNCHED_ASINS - Recommendation relates to ASINs you manage that are not enrolled in advertising campaigns.

     *      - *product* - array - optional
     *          - Filter for opportunities with these product values.
* AMAZON_DSP - Recommendation relates to the Amazon DSP.
* AMAZON_LIVE - Recommendation relates to Amazon's Live Show and Tell program.
* POSTS - Recommendation relates to Amazon's social media Posts service.
* SPONSORED_BRANDS - Recommendation relates to Sponsored Brands.
* SPONSORED_BRANDS_VIDEO - Recommendation relates to Sponsored Brands Video.
* SPONSORED_DISPLAY - Recommendation relates to Sponsored Display.
* SPONSORED_DISPLAY_VIDEO - Recommendation relates to Sponsored Display Video.
* SPONSORED_PRODUCTS - Recommendation relates to Sponsored Products.
* STORES - Recommendation relates to building a storefront page on Amazon.
* VIDEO_ADS - Deprecated value, replaced by SPONSORED_BRANDS_VIDEO and SPONSORED_DISPLAY_VIDEO values.
     * @return array
     *      - *uniqueAdvertiserApproximateCount* - number
     *          - Approximate number of unique advertisers across all opportunities for the partner.
     *      - *availableProducts* - array
     *          - All available opportunity product values with the number of opportunities for each.
     *      - *opportunitiesCount* - number
     *          - Total number of opportunities for the partner.
     *      - *opportunitiesWithDataCount* - number
     *          - Number of actionable opportunities with data for the partner.
     *      - *availableAudiences* - array
     *          - All available opportunity audience values with the number of opportunities for each.
     *      - *availableObjectiveTypes* - array
     *          - All available opportunity objective values with the number of opportunities for each.
     */
    public function partnerOpportunitiesSummarizeOpportunities(array $query = [], string $contentType = 'application/vnd.partneropportunity.v1.2+json'): array
    {
        return $this->api(array_merge(["/partnerOpportunities/summary"], $query), ['content-type' => $contentType, 'accept' => $contentType]);
    }
                    
    /**
     * @description Retrieves the current status of applied recommendations.

**Authorized resource type**:
Global Manager Account ID

**Parameter name**:
Amazon-Advertising-API-Manager-Account

**Parameter in**:
header

**Requires one of these permissions**:
["MasterAccount_Manager","ManagerAccount_Dev"]     * @tag Partner Opportunities
     * @param string $partnerOpportunityId 
     * @param array $data 
     *      - *encryptedAdvertiserId* - string
     *          - The encrypted advertiser ID.

Provided in opportunity data.
     *      - *marketplace* - 
     *      - *recommendationIds* - array
     *          - A list of recommendation IDs for which status will be retrieved.

Provided in opportunity data.
     *      - *entityId* - string
     *          - Entity ID

Provided in opportunity data.
     *      - *advertiserType* - string
     *          - Entity Type

Provided in opportunity data as 'advertiserType'.
     * @return array
     *      - *statuses* - array
     */
    public function partnerOpportunitiesApplicationStatus(string $partnerOpportunityId, array $data, string $contentType = 'application/vnd.partneropportunity.v1.2+json'): array
    {
        return $this->api("/partnerOpportunities/{$partnerOpportunityId}/applicationStatus", 'POST', $data, ['content-type' => $contentType, 'accept' => $contentType]);
    }
    
}

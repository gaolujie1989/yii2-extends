<?php

namespace lujie\ebay\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The Metadata API has operations that retrieve configuration details pertaining to the different eBay marketplaces. In addition to marketplace information, the API also has operations that get information that helps sellers list items on eBay.
*/
class SellMetadataV1 extends \lujie\ebay\BaseEbayRestClient
{

    public $apiBaseUrl = 'https://api.ebay.com/sell/metadata/v1';

                
    /**
     * @description This method returns the eBay policies that define how to list automotive-parts-compatibility items in the categories of a specific marketplace.  <br><br>By default, this method returns the entire category tree for the specified marketplace. You can limit the size of the result set by using the <b>filter</b> query parameter to specify only the category IDs you want to review.<br><br><span class="tablenote"><span style="color:#478415"><strong>Tip:</strong></span> This method can potentially return a very large response payload. eBay recommends that the response payload be compressed by passing in the <b>Accept-Encoding</b> request header and setting the value to <code>application/gzip</code>.</span>
     * @tag marketplace
     * @param string $marketplaceId This path parameter specifies the eBay marketplace for which policy information is retrieved.  <br><br><b>Note:</b> Only the following eBay marketplaces support automotive parts compatibility: <ul> <li>EBAY_US</li> <li>EBAY_AU</li> <li>EBAY_CA</li> <li>EBAY_DE</li> <li>EBAY_ES</li> <li>EBAY_FR</li> <li>EBAY_GB</li> <li>EBAY_IT</li><ul>
     * @param array $query
     *      - *filter* - string - optional
     *          - This query parameter limits the response by returning policy information for only the selected sections of the category tree. Supply <b>categoryId</b> values for the sections of the tree you want returned.  <br><br>When you specify a <b>categoryId</b> value, the returned category tree includes the policies for that parent node, plus the policies for any leaf nodes below that parent node.  <br><br>The parameter takes a list of <b>categoryId</b> values and you can specify up to 50 separate category IDs. Separate multiple values with a pipe character ('|'). If you specify more than 50 <code>categoryId</code> values, eBay returns the policies for the first 50 IDs and a warning that not all categories were returned.  <br><br><b>Example:</b> <code>filter=categoryIds:{100|101|102}</code>  <br><br>Note that you must URL-encode the parameter list, which results in the following filter for the above example: <br><br> &nbsp;&nbsp;<code>filter=categoryIds%3A%7B100%7C101%7C102%7D</code>
     * @param array $headers
     *      - *Accept-Encoding* - string - optional
     *          - This header indicates the compression-encoding algorithms the client accepts for the response. This value should be set to <code>application/gzip</code>. <br><br> For more information, refer to <a href="/api-docs/static/rest-request-components.html#HTTP" target="_blank ">HTTP request headers</a>.
     * @return array
     *      - *automotivePartsCompatibilityPolicies* - array
     *          - A list of category IDs and the automotive-parts-compatibility policies for each of the listed categories.
     *      - *warnings* - array
     *          - A list of the warnings that were generated as a result of the request. This field is not returned if no warnings were generated by the request.
     */
    public function getAutomotivePartsCompatibilityPolicies(string $marketplaceId, array $query = [], array $headers = []): array
    {
        return $this->api(array_merge(["/marketplace/{$marketplaceId}/get_automotive_parts_compatibility_policies"], $query), 'GET', [], $headers);
    }
                    
    /**
     * @description This method returns the Extended Producer Responsibility policies for one, multiple, or all eBay categories in an eBay marketplace.<br><br>The identifier of the eBay marketplace is passed in as a path parameter, and unless one or more eBay category IDs are passed in through the filter query parameter, this method will return metadata on every applicable category for the specified marketplace.<br><br><span class="tablenote"><span style="color:#004680"><strong>Note:</strong></span> Currently, the Extended Producer Responsibility policies are only applicable to a limited number of categories, and only in the EBAY_FR marketplace.</span><br><span class="tablenote"><span style="color:#004680"><strong>Note: </strong></span>Extended Producer Responsibility IDs are no longer set at the listing level so category-level metadata is no longer returned. Instead, sellers will provide/manage these IDs at the account level by going to <a href="https://accountsettings.ebay.fr/epr-fr " target="_blank">Account Settings</a>.</span><br><span class="tablenote"><span style="color:#478415"><strong>Tip:</strong></span> This method can potentially return a very large response payload. eBay recommends that the response payload be compressed by passing in the <b>Accept-Encoding</b> request header and setting the value to <code>application/gzip</code>.</span>
     * @tag marketplace
     * @param string $marketplaceId A path parameter that specifies the eBay marketplace for which policy information shall be retrieved.<br><br><span class="tablenote"><span style="color:#478415"><strong>Tip:</strong></span> See <a href="/api-docs/static/rest-request-components.html#marketpl" target="_blank">Request components</a> for a list of valid eBay marketplace IDs.</span>
     * @param array $query
     *      - *filter* - string - optional
     *          - A query parameter that can be used to limit the response by returning policy information for only the selected sections of the category tree. Supply <b>categoryId</b> values for the sections of the tree that should be returned.<br><br>When a <b>categoryId</b> value is specified, the returned category tree includes the policies for that parent node, as well as the policies for any child nodes below that parent node.<br><br>Pass in the <b>categoryId</b> values using a URL-encoded, pipe-separated ('|') list. For example:<br><br><code>filter=categoryIds%3A%7B100%7C101%7C102%7D</code><br><br><b>Maximum:</b> 50
     * @param array $headers
     *      - *Accept-Encoding* - string - optional
     *          - This header indicates the compression-encoding algorithms the client accepts for the response. This value should be set to <code>application/gzip</code>. <br><br> For more information, refer to <a href="/api-docs/static/rest-request-components.html#HTTP" target="_blank ">HTTP request headers</a>.
     * @return array
     *      - *extendedProducerResponsibilities* - array
     *          - An array of response fields detailing the Extended Producer Responsibility policies supported for the specified marketplace.
     *      - *warnings* - array
     *          - A collection of warnings generated for the request.
     */
    public function getExtendedProducerResponsibilityPolicies(string $marketplaceId, array $query = [], array $headers = []): array
    {
        return $this->api(array_merge(["/marketplace/{$marketplaceId}/get_extended_producer_responsibility_policies"], $query), 'GET', [], $headers);
    }
                    
    /**
     * @description This method returns hazardous materials label information for the specified eBay marketplace. The information includes IDs, descriptions, and URLs (as applicable) for the available signal words, statements, and pictograms. The returned statements are localized for the default langauge of the marketplace. If a marketplace does not support hazardous materials label information, an error is returned.<p>This information is used by the seller to add hazardous materials label related information to their listings (see <a href='/api-docs/sell/static/metadata/feature-regulatorhazmatcontainer.html'>Specifying hazardous material related information</a>).</p>
     * @tag marketplace
     * @param string $marketplaceId A path parameter that specifies the eBay marketplace for which hazardous materials label information shall be retrieved.<p><span class="tablenote"><strong>Tip:</strong> See <a href="/api-docs/static/rest-request-components.html#marketpl" >Request components</a> for a list of valid eBay marketplace IDs.</span></p>
     * @return array
     *      - *signalWords* - array
     *          - This array contains available hazardous materials signal words for the specified marketplace.
     *      - *statements* - array
     *          - This array contains available hazardous materials hazard statements for the specified marketplace.
     *      - *pictograms* - array
     *          - This array contains of available hazardous materials hazard pictograms for the specified marketplace.
     */
    public function getHazardousMaterialsLabels(string $marketplaceId): array
    {
        return $this->api("/marketplace/{$marketplaceId}/get_hazardous_materials_labels");
    }
                    
    /**
     * @description This method returns item condition metadata on one, multiple, or all eBay categories on an eBay marketplace. This metadata consists of the different item conditions (with IDs) that an eBay category supports, and a boolean to indicate if an eBay category requires an item condition. <br><br>If applicable, this metadata also shows the different condition descriptors (with IDs) that an eBay category supports.<br><br><span class="tablenote"><b>Note:</b> Condition descriptors are currently only available in the United Kingdom (GB) and will become available on all other marketplaces by July 2023.</span><br><span class="tablenote"><b>Note:</b> Currently, condition grading is only applicable to the following trading card categories: <ul><li>Non-Sport Trading Card Singles</li><li>CCG Individual Cards</li><li>Sports Trading Cards Singles</li></ul></span><br>The identifier of the eBay marketplace is passed in as a path parameter, and unless one or more eBay category IDs are passed in through the <b>filter</b> query parameter, this method will return metadata on every single category for the specified marketplace. If you only want to view item condition metadata for one eBay category or a select group of eBay categories, you can pass in up to 50 eBay category ID through the <b>filter</b> query parameter.<br><br><span class="tablenote"><span style="color:#FF0000"><strong>Important:</strong></span> <b>Certified - Refurbished</b>-eligible sellers, and sellers who are eligible to list with the new values (EXCELLENT_REFURBISHED, VERY_GOOD_REFURBISHED, and GOOD_REFURBISHED) must use an OAuth token created with the <a href="/api-docs/static/oauth-authorization-code-grant.html" target="_blank">authorization code grant flow</a> and <b>https://api.ebay.com/oauth/api_scope/sell.inventory</b> scope in order to retrieve the refurbished conditions for the relevant categories.<br/><br/>See the <a href="/api-docs/sell/static/metadata/condition-id-values.html#Category " target="_blank">eBay Refurbished Program - Category and marketplace support</a> topic for the categories and marketplaces that support these refurbished conditions<br/><br/>These restricted item conditions will not be returned if an OAuth token created with the <a href="/api-docs/static/oauth-client-credentials-grant.html" target="_blank">client credentials grant flow</a> and <b>https://api.ebay.com/oauth/api_scope</b> scope is used, or if any seller is not eligible to list with that item condition. <br/><br/> See the <a href="/api-docs/static/oauth-scopes.html" target="_blank">Specifying OAuth scopes</a> topic for more information about specifying scopes.</span><br><br><span class="tablenote"><span style="color:#478415"><strong>Tip:</strong></span> This method can potentially return a very large response payload. eBay recommends that the response payload be compressed by passing in the <b>Accept-Encoding</b> request header and setting the value to <code>application/gzip</code>.</span>
     * @tag marketplace
     * @param string $marketplaceId This path parameter specifies the eBay marketplace for which policy information is retrieved. See the following page for a list of valid eBay marketplace IDs: <a href="/api-docs/static/rest-request-components.html#marketpl" target="_blank">Request components</a>.
     * @param array $query
     *      - *filter* - string - optional
     *          - This query parameter limits the response by returning policy information for only the selected sections of the category tree. Supply <b>categoryId</b> values for the sections of the tree you want returned.  <br><br>When you specify a <b>categoryId</b> value, the returned category tree includes the policies for that parent node, plus the policies for any leaf nodes below that parent node.  <br><br>The parameter takes a list of <b>categoryId</b> values and you can specify up to 50 separate category IDs. Separate multiple values with a pipe character ('|'). If you specify more than 50 <code>categoryId</code> values, eBay returns the policies for the first 50 IDs and a warning that not all categories were returned.  <br><br><b>Example:</b> <code>filter=categoryIds:{100|101|102}</code>  <br><br>Note that you must URL-encode the parameter list, which results in the following filter for the above example: <br><br> &nbsp;&nbsp;<code>filter=categoryIds%3A%7B100%7C101%7C102%7D</code>
     * @param array $headers
     *      - *Accept-Encoding* - string - optional
     *          - This header indicates the compression-encoding algorithms the client accepts for the response. This value should be set to <code>application/gzip</code>. <br><br> For more information, refer to <a href="/api-docs/static/rest-request-components.html#HTTP" target="_blank ">HTTP request headers</a>.
     * @return array
     *      - *itemConditionPolicies* - array
     *          - A list of category IDs and the policies for how to indicate an item's condition in each of the listed categories.
     *      - *warnings* - array
     *          - A list of the warnings that were generated as a result of the request. This field is not returned if no warnings were generated by the request.
     */
    public function getItemConditionPolicies(string $marketplaceId, array $query = [], array $headers = []): array
    {
        return $this->api(array_merge(["/marketplace/{$marketplaceId}/get_item_condition_policies"], $query), 'GET', [], $headers);
    }
                    
    /**
     * @description This method returns the eBay policies that define the allowed listing structures for the categories of a specific marketplace. The listing-structure policies currently pertain to whether or not you can list items with variations.  <br><br>By default, this method returns the entire category tree for the specified marketplace. You can limit the size of the result set by using the <b>filter</b> query parameter to specify only the category IDs you want to review.<br><br><span class="tablenote"><span style="color:#478415"><strong>Tip:</strong></span> This method can potentially return a very large response payload. eBay recommends that the response payload be compressed by passing in the <b>Accept-Encoding</b> request header and setting the value to <code>application/gzip</code>.</span>
     * @tag marketplace
     * @param string $marketplaceId This path parameter specifies the eBay marketplace for which policy information is retrieved. See the following page for a list of valid eBay marketplace IDs: <a href="/api-docs/static/rest-request-components.html#marketpl" target="_blank">Request components</a>.
     * @param array $query
     *      - *filter* - string - optional
     *          - This query parameter limits the response by returning policy information for only the selected sections of the category tree. Supply <b>categoryId</b> values for the sections of the tree you want returned.  <br><br>When you specify a <b>categoryId</b> value, the returned category tree includes the policies for that parent node, plus the policies for any leaf nodes below that parent node.  <br><br>The parameter takes a list of <b>categoryId</b> values and you can specify up to 50 separate category IDs. Separate multiple values with a pipe character ('|'). If you specify more than 50 <code>categoryId</code> values, eBay returns the policies for the first 50 IDs and a warning that not all categories were returned.  <br><br><b>Example:</b> <code>filter=categoryIds:{100|101|102}</code>  <br><br>Note that you must URL-encode the parameter list, which results in the following filter for the above example: <br><br> &nbsp;&nbsp;<code>filter=categoryIds%3A%7B100%7C101%7C102%7D</code>
     * @param array $headers
     *      - *Accept-Encoding* - string - optional
     *          - This header indicates the compression-encoding algorithms the client accepts for the response. This value should be set to <code>application/gzip</code>. <br><br> For more information, refer to <a href="/api-docs/static/rest-request-components.html#HTTP" target="_blank ">HTTP request headers</a>.
     * @return array
     *      - *listingStructurePolicies* - array
     *          - Returns a list of category IDs plus a flag indicating whether or not each listed category supports item variations.
     *      - *warnings* - array
     *          - A list of the warnings that were generated as a result of the request. This field is not returned if no warnings were generated by the request.
     */
    public function getListingStructurePolicies(string $marketplaceId, array $query = [], array $headers = []): array
    {
        return $this->api(array_merge(["/marketplace/{$marketplaceId}/get_listing_structure_policies"], $query), 'GET', [], $headers);
    }
                    
    /**
     * @description This method returns the eBay policies that define the supported negotiated price features (like "best offer") for the categories of a specific marketplace.  <br><br>By default, this method returns the entire category tree for the specified marketplace. You can limit the size of the result set by using the <b>filter</b> query parameter to specify only the category IDs you want to review.<br><br><span class="tablenote"><span style="color:#478415"><strong>Tip:</strong></span> This method can potentially return a very large response payload. eBay recommends that the response payload be compressed by passing in the <b>Accept-Encoding</b> request header and setting the value to <code>application/gzip</code>.</span>
     * @tag marketplace
     * @param string $marketplaceId This path parameter specifies the eBay marketplace for which policy information is retrieved. See the following page for a list of valid eBay marketplace IDs: <a href="/api-docs/static/rest-request-components.html#marketpl" target="_blank">Request components</a>.
     * @param array $query
     *      - *filter* - string - optional
     *          - This query parameter limits the response by returning policy information for only the selected sections of the category tree. Supply <b>categoryId</b> values for the sections of the tree you want returned.  <br><br>When you specify a <b>categoryId</b> value, the returned category tree includes the policies for that parent node, plus the policies for any leaf nodes below that parent node.  <br><br>The parameter takes a list of <b>categoryId</b> values and you can specify up to 50 separate category IDs. Separate multiple values with a pipe character ('|'). If you specify more than 50 <code>categoryId</code> values, eBay returns the policies for the first 50 IDs and a warning that not all categories were returned.  <br><br><b>Example:</b> <code>filter=categoryIds:{100|101|102}</code>  <br><br>Note that you must URL-encode the parameter list, which results in the following filter for the above example: <br><br> &nbsp;&nbsp;<code>filter=categoryIds%3A%7B100%7C101%7C102%7D</code>
     * @param array $headers
     *      - *Accept-Encoding* - string - optional
     *          - This header indicates the compression-encoding algorithms the client accepts for the response. This value should be set to <code>application/gzip</code>. <br><br> For more information, refer to <a href="/api-docs/static/rest-request-components.html#HTTP" target="_blank ">HTTP request headers</a>.
     * @return array
     *      - *negotiatedPricePolicies* - array
     *          - A list of category IDs and the policies related to negotiated-price items for each of the listed categories.
     *      - *warnings* - array
     *          - A list of the warnings that were generated as a result of the request. This field is not returned if no warnings were generated by the request.
     */
    public function getNegotiatedPricePolicies(string $marketplaceId, array $query = [], array $headers = []): array
    {
        return $this->api(array_merge(["/marketplace/{$marketplaceId}/get_negotiated_price_policies"], $query), 'GET', [], $headers);
    }
                    
    /**
     * @description This method returns the eBay policies that define whether or not you must include a return policy for the items you list in the categories of a specific marketplace, plus the guidelines for creating domestic and international return policies in the different eBay categories.  <br><br>By default, this method returns the entire category tree for the specified marketplace. You can limit the size of the result set by using the <b>filter</b> query parameter to specify only the category IDs you want to review.<br><br><span class="tablenote"><span style="color:#478415"><strong>Tip:</strong></span> This method can potentially return a very large response payload. eBay recommends that the response payload be compressed by passing in the <b>Accept-Encoding</b> request header and setting the value to <code>application/gzip</code>.</span>
     * @tag marketplace
     * @param string $marketplaceId This path parameter specifies the eBay marketplace for which policy information is retrieved. See the following page for a list of valid eBay marketplace IDs: <a href="/api-docs/static/rest-request-components.html#marketpl" target="_blank">Request components</a>.
     * @param array $query
     *      - *filter* - string - optional
     *          - This query parameter limits the response by returning policy information for only the selected sections of the category tree. Supply <b>categoryId</b> values for the sections of the tree you want returned.  <br><br>When you specify a <b>categoryId</b> value, the returned category tree includes the policies for that parent node, plus the policies for any leaf nodes below that parent node.  <br><br>The parameter takes a list of <b>categoryId</b> values and you can specify up to 50 separate category IDs. Separate multiple values with a pipe character ('|'). If you specify more than 50 <code>categoryId</code> values, eBay returns the policies for the first 50 IDs and a warning that not all categories were returned.  <br><br><b>Example:</b> <code>filter=categoryIds:{100|101|102}</code>  <br><br>Note that you must URL-encode the parameter list, which results in the following filter for the above example: <br><br> &nbsp;&nbsp;<code>filter=categoryIds%3A%7B100%7C101%7C102%7D</code>
     * @param array $headers
     *      - *Accept-Encoding* - string - optional
     *          - This header indicates the compression-encoding algorithms the client accepts for the response. This value should be set to <code>application/gzip</code>. <br><br> For more information, refer to <a href="/api-docs/static/rest-request-components.html#HTTP" target="_blank ">HTTP request headers</a>.
     * @return array
     *      - *returnPolicies* - array
     *          - A list of elements, where each contains a category ID and a flag that indicates whether or not listings in that category require a return policy.
     *      - *warnings* - array
     *          - A list of the warnings that were generated as a result of the request. This field is not returned if no warnings were generated by the request.
     */
    public function getReturnPolicies(string $marketplaceId, array $query = [], array $headers = []): array
    {
        return $this->api(array_merge(["/marketplace/{$marketplaceId}/get_return_policies"], $query), 'GET', [], $headers);
    }
                    
    /**
     * @description This method retrieves all the sales tax jurisdictions for the country that you specify in the <b>countryCode</b> path parameter. Countries with valid sales tax jurisdictions are Canada and the US.  <br><br>The response from this call tells you the jurisdictions for which a seller can configure tax tables. Although setting up tax tables is optional, you can use the <b>createOrReplaceSalesTax</b> in the <b>Account API</b> call to configure the tax tables for the jurisdictions you sell to.
     * @tag country
     * @param string $countryCode This path parameter specifies the two-letter <a href="https://www.iso.org/iso-3166-country-codes.html " title="https://www.iso.org " target="_blank">ISO 3166</a> country code for the country whose jurisdictions you want to retrieve. eBay provides sales tax jurisdiction information for Canada and the United States.Valid values for this path parameter are <code>CA</code> and <code>US</code>.
     * @return array
     *      - *salesTaxJurisdictions* - array
     *          - A list of sales tax jurisdictions.
     */
    public function getSalesTaxJurisdictions(string $countryCode): array
    {
        return $this->api("/country/{$countryCode}/sales_tax_jurisdiction");
    }
    
}
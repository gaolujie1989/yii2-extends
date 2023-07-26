<?php

namespace lujie\ebay\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description Service for providing information to sellers about their listings being non-compliant, or at risk for becoming non-compliant, against eBay listing policies.
*/
class SellComplianceV1 extends \lujie\ebay\BaseEbayRestClient
{

    public $apiBaseUrl = 'https://api.ebay.com/sell/compliance/v1';

                
    /**
     * @description This call returns listing violation counts for a seller. A user can pass in one or more compliance types through the <strong>compliance_type</strong> query parameter. See <a href="/api-docs/sell/compliance/types/com:ComplianceTypeEnum">ComplianceTypeEnum</a> for more information on the supported listing compliance types. Listing violations are returned for multiple marketplaces if the seller sells on multiple eBay marketplaces.<br /><br /> <span class="tablenote"><strong>Note:</strong> Only a canned response, with counts for all listing compliance types, is returned in the Sandbox environment. Due to this limitation, the <strong>compliance_type</strong> query parameter (if used) will not have an effect on the response. </span>
     * @tag listing_violation_summary
     * @param array $query
     *      - *compliance_type* - string - optional
     *          - A user passes in one or more compliance type values through this query parameter. See <a href="/api-docs/sell/compliance/types/com:ComplianceTypeEnum">ComplianceTypeEnum</a> for more information on the supported compliance types that can be passed in here. If more than one compliance type value is used, delimit these values with a comma. If no compliance type values are passed in, the listing count for all compliance types will be returned. <br /><br /> <span class="tablenote"><strong>Note:</strong> Only a canned response, with counts for all listing compliance types, is returned in the Sandbox environment. Due to this limitation, the <strong>compliance_type</strong> query parameter (if used) will not have an effect on the response. </span>
     * @param array $headers
     *      - *X-EBAY-C-MARKETPLACE-ID* - string - required
     *          - Use this header to specify the eBay marketplace identifier. Supported values for this header can be found in the <a href="/api-docs/sell/compliance/types/bas:MarketplaceIdEnum">MarketplaceIdEnum</a> type definition. Note that Version 1.4.0 of the Compliance API is only supported on the US, UK, Australia, Canada {English), and Germany sites.
     */
    public function getListingViolationsSummary(array $query, array $headers): void
    {
        $this->api(array_merge(["/listing_violation_summary"], $query), 'GET', [], $headers);
    }
                    
    /**
     * @description This call returns specific listing violations for the supported listing compliance types. Only one compliance type can be passed in per call, and the response will include all the listing violations for this compliance type, and listing violations are grouped together by eBay listing ID. See <a href="/api-docs/sell/compliance/types/com:ComplianceTypeEnum">ComplianceTypeEnum</a> for more information on the supported listing compliance types. This method also has pagination control. <br /><br /> <span class="tablenote"><strong>Note:</strong> A maximum of 2000 listing violations will be returned in a result set. If the seller has more than 2000 listing violations, some/all of those listing violations must be corrected before additional listing violations will be retrieved. The user should pay attention to the <strong>total</strong> value in the response. If this value is '2000', it is possible that the seller has more than 2000 listing violations, but this field maxes out at 2000. </span> <br /><br /> <span class="tablenote"><strong>Note:</strong> In a future release of this API, the seller will be able to pass in a specific eBay listing ID as a query parameter to see if this specific listing has any violations. </span><br /><br /> <span class="tablenote"><strong>Note:</strong> Only mocked non-compliant listing data will be returned for this call in the Sandbox environment, and not specific to the seller. However, the user can still use this mock data to experiment with the compliance type filters and pagination control.</span>
     * @tag listing_violation
     * @param array $query
     *      - *compliance_type* - string - optional
     *          - A seller uses this query parameter to retrieve listing violations of a specific compliance type.  Only one compliance type value should be passed in here. See <a href="/api-docs/sell/compliance/types/com:ComplianceTypeEnum">ComplianceTypeEnum</a> for more information on the compliance types that can be passed in here. If the <strong>listing_id</strong> query parameter is used, the <strong>compliance_type</strong> query parameter {if passed in) will be ignored. This is because all of a listing's policy violations {each compliance type) will be returned if a <strong>listing_id</strong> is provided. Either the <strong>listing_id</strong> or a <strong>compliance_type</strong> query parameter must be used, and if the seller only wants to view listing violations of a specific compliance type, both of these parameters can be used. <br /><br /> <span class="tablenote"><strong>Note:</strong> The <strong>listing_id</strong> query parameter is not yet available for use, so the seller does not have the ability to retrieve listing violations for one or more specific listings. Until the <strong>listing_id</strong> query parameter becomes available, the <strong>compliance_type</strong> query parameter is required with each <strong>getListingViolations</strong> call.</span>
     *      - *offset* - string - optional
     *          - The integer value input into this field controls the first listing violation in the result set that will be displayed at the top of the response. The <strong>offset</strong> and <strong>limit</strong> query parameters are used to control the pagination of the output. For example, if <strong>offset</strong> is set to <code>10</code> and <strong>limit</strong> is set to <code>10</code>, the call retrieves listing violations 11 thru 20 from the resulting set of violations. <br /><br /> <span class="tablenote"><strong>Note:</strong> This feature employs a zero-based index, where the first item in the list has an offset of <code>0</code>. If the <strong>listing_id</strong> parameter is included in the request, this parameter will be ignored.</span><br/><br/> <strong>Default: </strong> <code>0</code> {zero)
     *      - *listing_id* - string - optional
     *          - <span class="tablenote"><strong>Note:</strong> This query parameter is not yet supported for the Compliance API. Please note that until this query parameter becomes available, the <strong>compliance_type</strong> query parameter is required with each <strong>getListingViolations</strong> call.</span><br/><br/>This query parameter is used if the user wants to view all listing violations for one or more eBay listings. The string value passed into this field is the unique identifier of the listing, sometimes referred to as the Item ID. Either the <strong>listing_id</strong> or a <strong>compliance_type</strong> query parameter must be used, and if the seller only wants to view listing violations of a specific compliance type, both of these parameters can be used.<br/><br/> Up to 50 listing IDs can be specified with this query parameter, and each unique listing ID is separated with a comma.
     *      - *limit* - string - optional
     *          - This query parameter is used if the user wants to set a limit on the number of listing violations that are returned on one page of the result set. This parameter is used in conjunction with the <strong>offset</strong> parameter to control the pagination of the output.<br /><br />For example, if <strong>offset</strong> is set to <code>10</code> and <strong>limit</strong> is set to <code>10</code>, the call retrieves listing violations 11 thru 20 from the collection of listing violations that match the value set in the <strong>compliance_type</strong> parameter.<br /><br /><span class="tablenote"><strong>Note:</strong> This feature employs a zero-based index, where the first item in the list has an offset of <code>0</code>. If the <strong>listing_id</strong> parameter is included in the request, this parameter will be ignored.</span><br/><br/><strong>Default:</strong> <code>100</code><br/> <strong>Maximum:</strong> <code>200</code>
     *      - *filter* - string - optional
     *          - This filter allows a user to retrieve only listings that are currently out of compliance, or only listings that are at risk of becoming out of compliance.<br><br> Although other filters may be added in the future, <code>complianceState</code> is the only supported filter type at this time. The two compliance 'states' are <code>OUT_OF_COMPLIANCE</code> and <code>AT_RISK</code>. Below is an example of how to set up this compliance state filter. Notice that the filter type and filter value are separated with a colon (:) character, and the filter value is wrapped with curly brackets.<br><br> <code>filter=complianceState:{OUT_OF_COMPLIANCE}</code>
     * @param array $headers
     *      - *X-EBAY-C-MARKETPLACE-ID* - string - required
     *          - This header is required and is used to specify the eBay marketplace identifier. Supported values for this header can be found in the <a href="/api-docs/sell/compliance/types/bas:MarketplaceIdEnum">MarketplaceIdEnum</a> type definition. Note that Version 1.4.0 of the Compliance API is only supported on the US, UK, Australia, Canada {English), and Germany sites.
     */
    public function getListingViolations(array $query, array $headers): void
    {
        $this->api(array_merge(["/listing_violation"], $query), 'GET', [], $headers);
    }
                    
    /**
     * @description This call suppresses a listing violation for a specific listing. Only listing violations in the <code>AT_RISK</code> state (returned in the <strong>violations.complianceState</strong> field of the <strong>getListingViolations</strong> call) can be suppressed.<br/><br/><span class="tablenote"><strong>Note:</strong> At this time, the <strong>suppressViolation</strong> call only supports the suppressing of <code>ASPECTS_ADOPTION</code> listing violations in the <code>AT_RISK</code> state. In the future, it is possible that this method can be used to suppress other listing violation types.</span><br><br>A successful call returns a http status code of <code>204 Success</code>. There is no response payload. If the call is not successful, an error code will be returned stating the issue.
     * @tag listing_violation
     * @param array $data This type is the base request type of the <strong>SuppressViolation</strong> method.
     *      - *complianceType* - string
     *          - The compliance type of the listing violation to suppress is specified in this field. The compliance type for each listing violation is found in the <strong>complianceType</strong> field under the <strong>listingViolations</strong> array in a <strong>getListingViolations</strong> response.<br /><br /><span class="tablenote"> <strong>Note:</strong> At this time, the <strong>suppressViolation</strong> method is only used to suppress aspect adoption listing violations in the 'at-risk' state, so <code>ASPECTS_ADOPTION</code> is currently the only supported value for this field.  </span> For implementation help, refer to <a href='https://developer.ebay.com/api-docs/sell/compliance/types/com:ComplianceTypeEnum'>eBay API documentation</a>
     *      - *listingId* - string
     *          - The unique identifier of the listing with the violation(s) is specified in this field. The unique identifier of the listing with the listing violation(s) is found in the <strong>listingId</strong> field under the <strong>listingViolations</strong> array in a <strong>getListingViolations</strong> response.<br /><br /><span class="tablenote"> <strong>Note:</strong> At this time, the <strong>suppressViolation</strong> method is only used to suppress aspect adoption listing violations in the 'at-risk' state, so the listing specified in this field should be a listing with an <code>ASPECTS_ADOPTION</code> violation in the 'at-risk' state.</span>
     * @param array $headers
     *      - *Content-Type* - string - required
     *          - This header indicates the format of the request body provided by the client. It's value should be set to <b>application/json</b>. <br><br> For more information, refer to <a href="/api-docs/static/rest-request-components.html#HTTP" target="_blank ">HTTP request headers</a>.
     *      - *X-EBAY-C-MARKETPLACE-ID* - string - required
     *          - This header is required and is used to specify the eBay marketplace identifier. Supported values for this header can be found in the <a href="/api-docs/sell/compliance/types/bas:MarketplaceIdEnum">MarketplaceIdEnum</a> type definition. Note that Version 1.4.0 of the Compliance API is only supported on the US, UK, Australia, Canada {English), and Germany sites.
     */
    public function suppressViolation(array $data, array $headers): void
    {
        $this->api("/suppress_listing_violation", 'POST', $data, $headers);
    }
    
}

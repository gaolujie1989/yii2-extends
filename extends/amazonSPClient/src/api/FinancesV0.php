<?php

namespace lujie\amazon\sp\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The Selling Partner API for Finances helps you obtain financial information relevant to a seller's business. You can obtain financial events for a given order, financial event group, or date range without having to wait until a statement period closes. You can also obtain financial event groups for a given date range.
*/
class FinancesV0 extends \lujie\amazon\sp\BaseAmazonSPClient
{

                
    /**
     * @description Returns financial event groups for a given date range. It may take up to 48 hours for orders to appear in your financial events.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 0.5 | 30 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag 
     * @param array $query
     *      - *MaxResultsPerPage* - integer - optional
     *          - The maximum number of results to return per page. If the response exceeds the maximum number of transactions or 10 MB, the API responds with 'InvalidInput'.
     *      - *FinancialEventGroupStartedBefore* - string - optional
     *          - A date used for selecting financial event groups that opened before (but not at) a specified date and time, in ISO 8601 format. The date-time  must be later than FinancialEventGroupStartedAfter and no later than two minutes before the request was submitted. If FinancialEventGroupStartedAfter and FinancialEventGroupStartedBefore are more than 180 days apart, no financial event groups are returned.
     *      - *FinancialEventGroupStartedAfter* - string - optional
     *          - A date used for selecting financial event groups that opened after (or at) a specified date and time, in ISO 8601 format. The date-time must be no later than two minutes before the request was submitted.
     *      - *NextToken* - string - optional
     *          - A string token returned in the response of your previous request.
     * @return array
     *      - *payload* - 
     *          - The payload for the listFinancialEventGroups operation.
     *      - *errors* - 
     *          - One or more unexpected errors occurred during the listFinancialEventGroups operation.
     */
    public function listFinancialEventGroups(array $query = []): array
    {
        return $this->api(array_merge(["/finances/v0/financialEventGroups"], $query));
    }
                    
    /**
     * @description Returns all financial events for the specified financial event group. It may take up to 48 hours for orders to appear in your financial events.

**Note:** This operation will only retrieve group's data for the past two years. If a request is submitted for data spanning more than two years, an empty response is returned.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 0.5 | 30 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag 
     * @param string $eventGroupId The identifier of the financial event group to which the events belong.
     * @param array $query
     *      - *MaxResultsPerPage* - integer - optional
     *          - The maximum number of results to return per page. If the response exceeds the maximum number of transactions or 10 MB, the API responds with 'InvalidInput'.
     *      - *PostedAfter* - string - optional
     *          - A date used for selecting financial events posted after (or at) a specified time. The date-time **must** be more than two minutes before the time of the request, in ISO 8601 date time format.
     *      - *PostedBefore* - string - optional
     *          - A date used for selecting financial events posted before (but not at) a specified time. The date-time must be later than `PostedAfter` and no later than two minutes before the request was submitted, in ISO 8601 date time format. If `PostedAfter` and `PostedBefore` are more than 180 days apart, no financial events are returned. You must specify the `PostedAfter` parameter if you specify the `PostedBefore` parameter. Default: Now minus two minutes.
     *      - *NextToken* - string - optional
     *          - A string token returned in the response of your previous request.
     * @return array
     *      - *payload* - 
     *          - The payload for the listFinancialEvents operation.
     *      - *errors* - 
     *          - One or more unexpected errors occurred during the listFinancialEvents operation.
     */
    public function listFinancialEventsByGroupId(string $eventGroupId, array $query = []): array
    {
        return $this->api(array_merge(["/finances/v0/financialEventGroups/{$eventGroupId}/financialEvents"], $query));
    }
                    
    /**
     * @description Returns all financial events for the specified order. It may take up to 48 hours for orders to appear in your financial events.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 0.5 | 30 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag 
     * @param string $orderId An Amazon-defined order identifier, in 3-7-7 format.
     * @param array $query
     *      - *MaxResultsPerPage* - integer - optional
     *          - The maximum number of results to return per page. If the response exceeds the maximum number of transactions or 10 MB, the API responds with 'InvalidInput'.
     *      - *NextToken* - string - optional
     *          - A string token returned in the response of your previous request.
     * @return array
     *      - *payload* - 
     *          - The payload for the listFinancialEvents operation.
     *      - *errors* - 
     *          - One or more unexpected errors occurred during the listFinancialEvents operation.
     */
    public function listFinancialEventsByOrderId(string $orderId, array $query = []): array
    {
        return $this->api(array_merge(["/finances/v0/orders/{$orderId}/financialEvents"], $query));
    }
                    
    /**
     * @description Returns financial events for the specified data range. It may take up to 48 hours for orders to appear in your financial events.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 0.5 | 30 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag 
     * @param array $query
     *      - *MaxResultsPerPage* - integer - optional
     *          - The maximum number of results to return per page. If the response exceeds the maximum number of transactions or 10 MB, the API responds with 'InvalidInput'.
     *      - *PostedAfter* - string - optional
     *          - A date used for selecting financial events posted after (or at) a specified time. The date-time must be no later than two minutes before the request was submitted, in ISO 8601 date time format.
     *      - *PostedBefore* - string - optional
     *          - A date used for selecting financial events posted before (but not at) a specified time. The date-time must be later than PostedAfter and no later than two minutes before the request was submitted, in ISO 8601 date time format. If PostedAfter and PostedBefore are more than 180 days apart, no financial events are returned. You must specify the PostedAfter parameter if you specify the PostedBefore parameter. Default: Now minus two minutes.
     *      - *NextToken* - string - optional
     *          - A string token returned in the response of your previous request.
     * @return array
     *      - *payload* - 
     *          - The payload for the listFinancialEvents operation.
     *      - *errors* - 
     *          - One or more unexpected errors occurred during the listFinancialEvents operation.
     */
    public function listFinancialEvents(array $query = []): array
    {
        return $this->api(array_merge(["/finances/v0/financialEvents"], $query));
    }
    
}
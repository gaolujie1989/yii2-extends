<?php

namespace lujie\amazon\sp\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description The Selling Partner API for Reports lets you retrieve and manage a variety of reports that can help selling partners manage their businesses.
*/
class Reports20210630 extends \lujie\amazon\sp\BaseAmazonSPClient
{

            
    /**
     * @description Returns report details for the reports that match the filters that you specify.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 0.0222 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag reports
     * @param array $query
     *      - *reportTypes* - array - optional
     *          - A list of report types used to filter reports. Refer to [Report Type Values](https://developer-docs.amazon.com/sp-api/docs/report-type-values) for more information. When reportTypes is provided, the other filter parameters (processingStatuses, marketplaceIds, createdSince, createdUntil) and pageSize may also be provided. Either reportTypes or nextToken is required.
     *      - *processingStatuses* - array - optional
     *          - A list of processing statuses used to filter reports.
     *      - *marketplaceIds* - array - optional
     *          - A list of marketplace identifiers used to filter reports. The reports returned will match at least one of the marketplaces that you specify.
     *      - *pageSize* - integer - optional
     *          - The maximum number of reports to return in a single call.
     *      - *createdSince* - string - optional
     *          - The earliest report creation date and time for reports to include in the response, in ISO 8601 date time format. The default is 90 days ago. Reports are retained for a maximum of 90 days.
     *      - *createdUntil* - string - optional
     *          - The latest report creation date and time for reports to include in the response, in ISO 8601 date time format. The default is now.
     *      - *nextToken* - string - optional
     *          - A string token returned in the response to your previous request. nextToken is returned when the number of results exceeds the specified pageSize value. To get the next page of results, call the getReports operation and include this token as the only parameter. Specifying nextToken with any other parameters will cause the request to fail.
     * @return Iterator
     *      - *reports* - 
     *          - The reports.
     *      - *nextToken* - string
     *          - Returned when the number of results exceeds pageSize. To get the next page of results, call getReports with this token as the only parameter.
     */
    public function eachReports(array $query = []): Iterator
    {
        return $this->eachInternal('getReports', func_get_args());
    }
        
    /**
     * @description Returns report details for the reports that match the filters that you specify.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 0.0222 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag reports
     * @param array $query
     *      - *reportTypes* - array - optional
     *          - A list of report types used to filter reports. Refer to [Report Type Values](https://developer-docs.amazon.com/sp-api/docs/report-type-values) for more information. When reportTypes is provided, the other filter parameters (processingStatuses, marketplaceIds, createdSince, createdUntil) and pageSize may also be provided. Either reportTypes or nextToken is required.
     *      - *processingStatuses* - array - optional
     *          - A list of processing statuses used to filter reports.
     *      - *marketplaceIds* - array - optional
     *          - A list of marketplace identifiers used to filter reports. The reports returned will match at least one of the marketplaces that you specify.
     *      - *pageSize* - integer - optional
     *          - The maximum number of reports to return in a single call.
     *      - *createdSince* - string - optional
     *          - The earliest report creation date and time for reports to include in the response, in ISO 8601 date time format. The default is 90 days ago. Reports are retained for a maximum of 90 days.
     *      - *createdUntil* - string - optional
     *          - The latest report creation date and time for reports to include in the response, in ISO 8601 date time format. The default is now.
     *      - *nextToken* - string - optional
     *          - A string token returned in the response to your previous request. nextToken is returned when the number of results exceeds the specified pageSize value. To get the next page of results, call the getReports operation and include this token as the only parameter. Specifying nextToken with any other parameters will cause the request to fail.
     * @return Iterator
     *      - *reports* - 
     *          - The reports.
     *      - *nextToken* - string
     *          - Returned when the number of results exceeds pageSize. To get the next page of results, call getReports with this token as the only parameter.
     */
    public function batchReports(array $query = []): Iterator
    {
        return $this->batchInternal('getReports', func_get_args());
    }
    
    /**
     * @description Returns report details for the reports that match the filters that you specify.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 0.0222 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag reports
     * @param array $query
     *      - *reportTypes* - array - optional
     *          - A list of report types used to filter reports. Refer to [Report Type Values](https://developer-docs.amazon.com/sp-api/docs/report-type-values) for more information. When reportTypes is provided, the other filter parameters (processingStatuses, marketplaceIds, createdSince, createdUntil) and pageSize may also be provided. Either reportTypes or nextToken is required.
     *      - *processingStatuses* - array - optional
     *          - A list of processing statuses used to filter reports.
     *      - *marketplaceIds* - array - optional
     *          - A list of marketplace identifiers used to filter reports. The reports returned will match at least one of the marketplaces that you specify.
     *      - *pageSize* - integer - optional
     *          - The maximum number of reports to return in a single call.
     *      - *createdSince* - string - optional
     *          - The earliest report creation date and time for reports to include in the response, in ISO 8601 date time format. The default is 90 days ago. Reports are retained for a maximum of 90 days.
     *      - *createdUntil* - string - optional
     *          - The latest report creation date and time for reports to include in the response, in ISO 8601 date time format. The default is now.
     *      - *nextToken* - string - optional
     *          - A string token returned in the response to your previous request. nextToken is returned when the number of results exceeds the specified pageSize value. To get the next page of results, call the getReports operation and include this token as the only parameter. Specifying nextToken with any other parameters will cause the request to fail.
     * @return array
     *      - *reports* - 
     *          - The reports.
     *      - *nextToken* - string
     *          - Returned when the number of results exceeds pageSize. To get the next page of results, call getReports with this token as the only parameter.
     */
    public function getReports(array $query = []): array
    {
        return $this->api(array_merge(["/reports/2021-06-30/reports"], $query));
    }
                
    /**
     * @description Creates a report.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 0.0167 | 15 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag reports
     * @param array $data 
     * @return array
     *      - *reportId* - string
     *          - The identifier for the report. This identifier is unique only in combination with a seller ID.
     */
    public function createReport(array $data): array
    {
        return $this->api("/reports/2021-06-30/reports", 'POST', $data);
    }
                        
    /**
     * @description Cancels the report that you specify. Only reports with processingStatus=IN_QUEUE can be cancelled. Cancelled reports are returned in subsequent calls to the getReport and getReports operations.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 0.0222 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag reports
     * @param string $reportId The identifier for the report. This identifier is unique only in combination with a seller ID.
     */
    public function cancelReport(string $reportId): void
    {
        $this->api("/reports/2021-06-30/reports/{$reportId}", 'DELETE');
    }
                
    /**
     * @description Returns report details (including the reportDocumentId, if available) for the report that you specify.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 2 | 15 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag reports
     * @param string $reportId The identifier for the report. This identifier is unique only in combination with a seller ID.
     * @return array
     *      - *marketplaceIds* - array
     *          - A list of marketplace identifiers for the report.
     *      - *reportId* - string
     *          - The identifier for the report. This identifier is unique only in combination with a seller ID.
     *      - *reportType* - string
     *          - The report type. Refer to [Report Type Values](https://developer-docs.amazon.com/sp-api/docs/report-type-values) for more information.
     *      - *dataStartTime* - string
     *          - The start of a date and time range used for selecting the data to report.
     *      - *dataEndTime* - string
     *          - The end of a date and time range used for selecting the data to report.
     *      - *reportScheduleId* - string
     *          - The identifier of the report schedule that created this report (if any). This identifier is unique only in combination with a seller ID.
     *      - *createdTime* - string
     *          - The date and time when the report was created.
     *      - *processingStatus* - string
     *          - The processing status of the report.
     *      - *processingStartTime* - string
     *          - The date and time when the report processing started, in ISO 8601 date time format.
     *      - *processingEndTime* - string
     *          - The date and time when the report processing completed, in ISO 8601 date time format.
     *      - *reportDocumentId* - string
     *          - The identifier for the report document. Pass this into the getReportDocument operation to get the information you will need to retrieve the report document's contents.
     */
    public function getReport(string $reportId): array
    {
        return $this->api("/reports/2021-06-30/reports/{$reportId}");
    }
                        
    /**
     * @description Returns report schedule details that match the filters that you specify.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 0.0222 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag reports
     * @param array $query
     *      - *reportTypes* - array - required
     *          - A list of report types used to filter report schedules. Refer to [Report Type Values](https://developer-docs.amazon.com/sp-api/docs/report-type-values) for more information.
     * @return array
     *      - *reportSchedules* - array
     */
    public function getReportSchedules(array $query): array
    {
        return $this->api(array_merge(["/reports/2021-06-30/schedules"], $query));
    }
                
    /**
     * @description Creates a report schedule. If a report schedule with the same report type and marketplace IDs already exists, it will be cancelled and replaced with this one.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 0.0222 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag reports
     * @param array $data 
     * @return array
     *      - *reportScheduleId* - string
     *          - The identifier for the report schedule. This identifier is unique only in combination with a seller ID.
     */
    public function createReportSchedule(array $data): array
    {
        return $this->api("/reports/2021-06-30/schedules", 'POST', $data);
    }
                        
    /**
     * @description Cancels the report schedule that you specify.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 0.0222 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag reports
     * @param string $reportScheduleId The identifier for the report schedule. This identifier is unique only in combination with a seller ID.
     */
    public function cancelReportSchedule(string $reportScheduleId): void
    {
        $this->api("/reports/2021-06-30/schedules/{$reportScheduleId}", 'DELETE');
    }
                
    /**
     * @description Returns report schedule details for the report schedule that you specify.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 0.0222 | 10 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag reports
     * @param string $reportScheduleId The identifier for the report schedule. This identifier is unique only in combination with a seller ID.
     * @return array
     *      - *reportScheduleId* - string
     *          - The identifier for the report schedule. This identifier is unique only in combination with a seller ID.
     *      - *reportType* - string
     *          - The report type. Refer to [Report Type Values](https://developer-docs.amazon.com/sp-api/docs/report-type-values) for more information.
     *      - *marketplaceIds* - array
     *          - A list of marketplace identifiers. The report document's contents will contain data for all of the specified marketplaces, unless the report type indicates otherwise.
     *      - *reportOptions* - 
     *      - *period* - string
     *          - An ISO 8601 period value that indicates how often a report should be created.
     *      - *nextReportCreationTime* - string
     *          - The date and time when the schedule will create its next report, in ISO 8601 date time format.
     */
    public function getReportSchedule(string $reportScheduleId): array
    {
        return $this->api("/reports/2021-06-30/schedules/{$reportScheduleId}");
    }
                        
    /**
     * @description Returns the information required for retrieving a report document's contents.

**Usage Plan:**

| Rate (requests per second) | Burst |
| ---- | ---- |
| 0.0167 | 15 |

The `x-amzn-RateLimit-Limit` response header returns the usage plan rate limits that were applied to the requested operation, when available. The table above indicates the default rate and burst values for this operation. Selling partners whose business demands require higher throughput may see higher rate and burst values than those shown here. For more information, see [Usage Plans and Rate Limits in the Selling Partner API](https://developer-docs.amazon.com/sp-api/docs/usage-plans-and-rate-limits-in-the-sp-api).
     * @tag reports
     * @param string $reportDocumentId The identifier for the report document.
     * @return array
     *      - *reportDocumentId* - string
     *          - The identifier for the report document. This identifier is unique only in combination with a seller ID.
     *      - *url* - string
     *          - A presigned URL for the report document. If `compressionAlgorithm` is not returned, you can download the report directly from this URL. This URL expires after 5 minutes.
     *      - *compressionAlgorithm* - string
     *          - If the report document contents have been compressed, the compression algorithm used is returned in this property and you must decompress the report when you download. Otherwise, you can download the report directly. Refer to [Step 2. Download the report](doc:reports-api-v2021-06-30-retrieve-a-report#step-2-download-the-report) in the use case guide, where sample code is provided.
     */
    public function getReportDocument(string $reportDocumentId): array
    {
        return $this->api("/reports/2021-06-30/documents/{$reportDocumentId}");
    }
    
}

<?php

namespace lujie\amazon\advertising\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description Fetch reports with performance metrics for DSP campaigns.
*/
class DSPReportsV3 extends \lujie\amazon\advertising\BaseAmazonAdvertisingClient
{

                
    /**
     * @description Use this operation to request creation of a report that includes metrics about your Amazon DSP campaigns. Specify the `type` of report and the `metrics` you'd like to include. Note that the value specified for the `dimensions` field affects the metrics included in the report. See the `dimensions` field description for more information.

**Authorized resource type**:
DSP Rodeo Entity ID, DSP Advertiser Account ID

**Parameter name**:
accountId

**Parameter in**:
path

**Requires one of these permissions**:
["view_performance_dashboard"]     * @tag Reports
     * @param string $accountId Account Identifier you use to access the DSP. This is your DSP Entity ID if you have access to all DSP advertisers within that entity, or your DSP Advertiser ID if you only have access to a specific advertiser ID.
     * @param array $data Create report request body. Version of request can be specified in the accept.
     *      - *advertiserIds* - array
     *          - List of advertisers specified by identifier to include in the report. This should not be present if accountId is advertiser. To learn more about when to use advertiserIds, see [Reporting by account type](https://advertising.amazon.com/API/docs/en-us/reporting/dsp/reporting-by-account-type).
     *      - *endDate* - string
     *          - Date in yyyy-MM-dd format. The report contains only metrics generated on the specified date range between startDate and endDate. The maximum date range between startDate and endDate is 31 days. The endDate can be up to 90 days older from today.
     *      - *format* - string
     *          - The report file format.
     *      - *orderIds* - array
     *          - List of orders specified by identifier to include in the report.
     *      - *metrics* - array
     *          - Specify a list of metrics field names to include in the report. For example: ["impressions", "clickThroughs", "CTR", "eCPC", "totalCost", "eCPM"]. If no metric field names are specified, only the default fields and selected `DIMENSION` fields are included by default. Specifying default fields returns an error. To view the metrics available by report type, see [DSP report types](https://advertising.amazon.com/API/docs/en-us/reporting/dsp/report-types)
     *      - *type* - string
     *          - The report type.
     *      - *startDate* - string
     *          - Date in yyyy-MM-dd format. The report contains only metrics generated on the specified date range between startDate and endDate. The maximum date range between startDate and endDate is 31 days. The startDate can be up to 90 days older from today.
     *      - *dimensions* - array
     *          - List of dimensions to include in the report. Specify one or many comma-delimited strings of dimensions. For example: ["ORDER", "LINE_ITEM", "CREATIVE"]. Adding a dimension in this array determines the aggregation level of the report data and also adds the fields for that dimension in the report. If the list is null or empty, the aggregation of the report data is at `ORDER` level. The allowed values can be used together in this array as an allowed value in which case the report aggregation will be at the lowest aggregation level and the report will contain the fields for all the dimensions included in the report. To see a list of metrics available by dimension, see [Dimensions](https://advertising.amazon.com/API/docs/en-us/reporting/dsp/dimensions).
     *      - *timeUnit* - string
     *          - Adding timeUnit determines the aggregation level (`SUMMARY` or `DAILY`) of the report data. If the timeUnit is null or empty, the aggregation of the report data is at the `SUMMARY` level and aggregated at the time period specified. `DAILY` timeUnit is not supported for `AUDIENCE` report type. The report will contain the fields based on timeUnit:<details><summary>`SUMMARY`</summary>intervalStart</br>intervalEnd</details></br><details><summary>`DAILY`</summary>Date</details>
     * @return array
     *      - *reportId* - string
     *          - The identifier of the report.
     *      - *format* - string
     *          - The data format of the report.
     *      - *statusDetails* - string
     *          -  A human-readable description of the current status.
     *      - *location* - string
     *          - The URI address of the report.
     *      - *expiration* - string
     *          - The expiration time of the URI in the location property in date-time format(yyyy-MM-ddTHH:mm:ss). The expiration time is the time when the download link expires.
     *      - *type* - string
     *          - The type of report.
     *      - *status* - string
     *          - The build status of the report.
     */
    public function createReportV3(string $accountId, array $data, string $contentType = 'application/vnd.dspcreatereports.v3+json'): array
    {
        return $this->api("/accounts/{$accountId}/dsp/reports", 'POST', $data, ['content-type' => $contentType, 'accept' => $contentType]);
    }
                    
    /**
     * @description Pass the identifier of a previously requested report in the `reportId` field to get the current status of the report. While the report is pending, `status` is set to `IN_PROGRESS`. When a response with `status` set to `SUCCESS` is returned, the report is available for download at the address specified in the `location` field.

**Authorized resource type**:
DSP Rodeo Entity ID, DSP Advertiser Account ID

**Parameter name**:
accountId

**Parameter in**:
path

**Requires one of these permissions**:
["view_performance_dashboard"]     * @tag Reports
     * @param string $accountId Account Identifier for DSP. Please input DSP entity ID if you want to retrieve reports<br />for a group of advertisers, or input DSP advertiser ID if you want to retrieve reports for a single advertiser.
     * @param string $reportId The identifier of the requested report.
     * @return array
     *      - *reportId* - string
     *          - The identifier of the report.
     *      - *format* - string
     *          - The data format of the report.
     *      - *statusDetails* - string
     *          -  A human-readable description of the current status.
     *      - *location* - string
     *          - The URI address of the report.
     *      - *expiration* - string
     *          - The expiration time of the URI in the location property in date-time format(yyyy-MM-ddTHH:mm:ss). The expiration time is the time when the download link expires.
     *      - *type* - string
     *          - The type of report.
     *      - *status* - string
     *          - The build status of the report.
     */
    public function getCampaignReportV3(string $accountId, string $reportId): array
    {
        return $this->api("/accounts/{$accountId}/dsp/reports/{$reportId}");
    }
    
}

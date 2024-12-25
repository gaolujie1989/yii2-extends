<?php

namespace lujie\amazon\advertising\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description 
*/
class AmazonMarketingStreamV3 extends \lujie\amazon\advertising\BaseAmazonAdvertisingClient
{

                
    /**
     * @description Fetch a specific subscription by Id
Note: trailing slash in request uri is not allowed

**Requires one of these permissions**:
["advertiser_campaign_edit"]     * @tag Stream Subscription
     * @param string $subscriptionId Unique subscription identifier
     * @return array
     *      - *subscription* - 
     */
    public function getStreamSubscription(string $subscriptionId, string $contentType = 'application/vnd.MarketingStreamSubscriptions.StreamSubscriptionResource.v1.0+json'): array
    {
        return $this->api("/streams/subscriptions/{$subscriptionId}", ['content-type' => $contentType, 'accept' => $contentType]);
    }
                
    /**
     * @description Update an existing subscription
Note: trailing slash in request uri is not allowed

**Requires one of these permissions**:
["advertiser_campaign_edit"]     * @tag Stream Subscription
     * @param string $subscriptionId Unique subscription identifier
     * @param array $data 
     *      - *notes* - string
     *          - Additional details associated with the subscription
     *      - *status* - 
     */
    public function updateStreamSubscription(string $subscriptionId, array $data, string $contentType = 'application/vnd.MarketingStreamSubscriptions.StreamSubscriptionResource.v1.0+json'): void
    {
        $this->api("/streams/subscriptions/{$subscriptionId}", 'PUT', $data, ['content-type' => $contentType, 'accept' => $contentType]);
    }
                    
    /**
     * @description Create a new subscription
Note: trailing slash in request uri is not allowed

**Requires one of these permissions**:
["advertiser_campaign_edit"]     * @tag Stream Subscription
     * @param array $data 
     *      - *notes* - string
     *          - Additional details associated with the subscription
     *      - *clientRequestToken* - string
     *          - Unique value supplied by the caller used to track identical API requests.
Should request be re-tried, the caller should supply the same value. We recommend using GUID.
     *      - *dataSetId* - string
     *          - Identifier of data set, callers can be subscribed to. Please refer to https://advertising.amazon.com/API/docs/en-us/amazon-marketing-stream/data-guide for the list of all data sets.
     *      - *destinationArn* - string
     *          - AWS ARN of the destination endpoint associated with the subscription.
Supported destination types:
- SQS
     *      - *destination* - 
     * @return array
     *      - *clientRequestToken* - string
     *          - Unique value supplied by the caller used to track identical API requests.
Should request be re-tried, the caller should supply the same value. We recommend using GUID.
     *      - *subscriptionId* - string
     *          - Unique subscription identifier
     */
    public function createStreamSubscription(array $data, string $contentType = 'application/vnd.MarketingStreamSubscriptions.StreamSubscriptionResource.v1.0+json'): array
    {
        return $this->api("/streams/subscriptions", 'POST', $data, ['content-type' => $contentType, 'accept' => $contentType]);
    }
                
    /**
     * @description List subscriptions
Note: trailing slash in request uri is not allowed

**Requires one of these permissions**:
["advertiser_campaign_edit"]     * @tag Stream Subscription
     * @param array $query
     *      - *maxResults* - int - optional
     *          - desired number of entries in the response, defaults to maximum value
     *      - *startingToken* - string - optional
     *          - Token which can be used to get the next page of results, if more entries exist
     * @return array
     *      - *subscriptions* - array
     *      - *nextToken* - string
     *          - Token which can be used to get the next page of results, if more entries exist
     */
    public function listStreamSubscriptions(array $query = [], string $contentType = 'application/vnd.MarketingStreamSubscriptions.StreamSubscriptionResource.v1.0+json'): array
    {
        return $this->api(array_merge(["/streams/subscriptions"], $query), ['content-type' => $contentType, 'accept' => $contentType]);
    }
                    
    /**
     * @description Create a new subscription
Note: trailing slash in request uri is not allowed

**Authorized resource type**:
DSP Rodeo Entity ID, DSP Advertiser Account ID

**Parameter name**:
Amazon-Ads-Account-ID

**Parameter in**:
header

**Requires one of these permissions**:
["view_performance_dashboard"]     * @tag DSP Stream Subscription
     * @param array $data 
     *      - *notes* - string
     *          - Additional details associated with the subscription
     *      - *clientRequestToken* - string
     *          - Unique value supplied by the caller used to track identical API requests.
Should request be re-tried, the caller should supply the same value. We recommend using GUID.
     *      - *dataSetId* - string
     *          - Identifier of data set, callers can be subscribed to. Please refer to https://advertising.amazon.com/API/docs/en-us/amazon-marketing-stream/data-guide for the list of all data sets.
     *      - *destinationArn* - string
     *          - AWS ARN of the destination endpoint associated with the subscription.
Supported destination types:
- SQS
     *      - *destination* - 
     * @return array
     *      - *clientRequestToken* - string
     *          - Unique value supplied by the caller used to track identical API requests.
Should request be re-tried, the caller should supply the same value. We recommend using GUID.
     *      - *subscriptionId* - string
     *          - Unique subscription identifier
     */
    public function createDspStreamSubscription(array $data, string $contentType = 'application/vnd.MarketingStreamSubscriptions.DspStreamSubscriptionResource.v1.0+json'): array
    {
        return $this->api("/dsp/streams/subscriptions", 'POST', $data, ['content-type' => $contentType, 'accept' => $contentType]);
    }
                
    /**
     * @description List subscriptions
Note: trailing slash in request uri is not allowed

**Authorized resource type**:
DSP Rodeo Entity ID, DSP Advertiser Account ID

**Parameter name**:
Amazon-Ads-Account-ID

**Parameter in**:
header

**Requires one of these permissions**:
["view_performance_dashboard"]     * @tag DSP Stream Subscription
     * @param array $query
     *      - *maxResults* - int - optional
     *          - desired number of entries in the response, defaults to maximum value
     *      - *startingToken* - string - optional
     *          - Token which can be used to get the next page of results, if more entries exist
     * @return array
     *      - *subscriptions* - array
     *      - *nextToken* - string
     *          - Token which can be used to get the next page of results, if more entries exist
     */
    public function listDspStreamSubscriptions(array $query = [], string $contentType = 'application/vnd.MarketingStreamSubscriptions.DspStreamSubscriptionResource.v1.0+json'): array
    {
        return $this->api(array_merge(["/dsp/streams/subscriptions"], $query), ['content-type' => $contentType, 'accept' => $contentType]);
    }
                    
    /**
     * @description Fetch a specific subscription by Id
Note: trailing slash in request uri is not allowed

**Authorized resource type**:
DSP Rodeo Entity ID, DSP Advertiser Account ID

**Parameter name**:
Amazon-Ads-Account-ID

**Parameter in**:
header

**Requires one of these permissions**:
["view_performance_dashboard"]     * @tag DSP Stream Subscription
     * @param string $subscriptionId Unique subscription identifier
     * @return array
     *      - *subscription* - 
     */
    public function getDspStreamSubscription(string $subscriptionId, string $contentType = 'application/vnd.MarketingStreamSubscriptions.DspStreamSubscriptionResource.v1.0+json'): array
    {
        return $this->api("/dsp/streams/subscriptions/{$subscriptionId}", ['content-type' => $contentType, 'accept' => $contentType]);
    }
                
    /**
     * @description Update an existing subscription
Note: trailing slash in request uri is not allowed

**Authorized resource type**:
DSP Rodeo Entity ID, DSP Advertiser Account ID

**Parameter name**:
Amazon-Ads-Account-ID

**Parameter in**:
header

**Requires one of these permissions**:
["view_performance_dashboard"]     * @tag DSP Stream Subscription
     * @param string $subscriptionId Unique subscription identifier
     * @param array $data 
     *      - *notes* - string
     *          - Additional details associated with the subscription
     *      - *status* - 
     */
    public function updateDspStreamSubscription(string $subscriptionId, array $data, string $contentType = 'application/vnd.MarketingStreamSubscriptions.DspStreamSubscriptionResource.v1.0+json'): void
    {
        $this->api("/dsp/streams/subscriptions/{$subscriptionId}", 'PUT', $data, ['content-type' => $contentType, 'accept' => $contentType]);
    }
    
}

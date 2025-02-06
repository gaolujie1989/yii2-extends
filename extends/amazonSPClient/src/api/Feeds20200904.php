<?php

namespace lujie\amazon\sp\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description Effective **June 27, 2024**, the Selling Partner API for Feeds v2020-09-04 will no longer be available and all calls to it will fail. Integrations that rely on the Feeds API must migrate to [Feeds v2021-06-30](https://developer-docs.amazon.com/sp-api/docs/feeds-api-v2021-06-30-reference) to avoid service disruption.
*/
class Feeds20200904 extends \lujie\amazon\sp\BaseAmazonSPClient
{

            
    /**
     * @description Effective June 27, 2023, the `getFeeds` operation will no longer be available in the Selling Partner API for Feeds v2020-09-04 and all calls to it will fail. Integrations that rely on this operation should migrate to [Feeds v2021-06-30](https://developer-docs.amazon.com/sp-api/docs/feeds-api-v2021-06-30-reference) to avoid service disruption.
     * @tag feeds
     * @param array $query
     *      - *feedTypes* - array - optional
     *          - A list of feed types used to filter feeds. When feedTypes is provided, the other filter parameters (processingStatuses, marketplaceIds, createdSince, createdUntil) and pageSize may also be provided. Either feedTypes or nextToken is required.
     *      - *marketplaceIds* - array - optional
     *          - A list of marketplace identifiers used to filter feeds. The feeds returned will match at least one of the marketplaces that you specify.
     *      - *pageSize* - integer - optional
     *          - The maximum number of feeds to return in a single call.
     *      - *processingStatuses* - array - optional
     *          - A list of processing statuses used to filter feeds.
     *      - *createdSince* - string - optional
     *          - The earliest feed creation date and time for feeds included in the response, in ISO 8601 format. The default is 90 days ago. Feeds are retained for a maximum of 90 days.
     *      - *createdUntil* - string - optional
     *          - The latest feed creation date and time for feeds included in the response, in ISO 8601 format. The default is now.
     *      - *nextToken* - string - optional
     *          - A string token returned in the response to your previous request. nextToken is returned when the number of results exceeds the specified pageSize value. To get the next page of results, call the getFeeds operation and include this token as the only parameter. Specifying nextToken with any other parameters will cause the request to fail.
     * @return Iterator
     *      - *payload* - 
     *      - *nextToken* - string
     *          - Returned when the number of results exceeds pageSize. To get the next page of results, call the getFeeds operation with this token as the only parameter.
     *      - *errors* - 
     */
    public function eachFeeds(array $query = []): Iterator
    {
        return $this->eachInternal('getFeeds', func_get_args());
    }
        
    /**
     * @description Effective June 27, 2023, the `getFeeds` operation will no longer be available in the Selling Partner API for Feeds v2020-09-04 and all calls to it will fail. Integrations that rely on this operation should migrate to [Feeds v2021-06-30](https://developer-docs.amazon.com/sp-api/docs/feeds-api-v2021-06-30-reference) to avoid service disruption.
     * @tag feeds
     * @param array $query
     *      - *feedTypes* - array - optional
     *          - A list of feed types used to filter feeds. When feedTypes is provided, the other filter parameters (processingStatuses, marketplaceIds, createdSince, createdUntil) and pageSize may also be provided. Either feedTypes or nextToken is required.
     *      - *marketplaceIds* - array - optional
     *          - A list of marketplace identifiers used to filter feeds. The feeds returned will match at least one of the marketplaces that you specify.
     *      - *pageSize* - integer - optional
     *          - The maximum number of feeds to return in a single call.
     *      - *processingStatuses* - array - optional
     *          - A list of processing statuses used to filter feeds.
     *      - *createdSince* - string - optional
     *          - The earliest feed creation date and time for feeds included in the response, in ISO 8601 format. The default is 90 days ago. Feeds are retained for a maximum of 90 days.
     *      - *createdUntil* - string - optional
     *          - The latest feed creation date and time for feeds included in the response, in ISO 8601 format. The default is now.
     *      - *nextToken* - string - optional
     *          - A string token returned in the response to your previous request. nextToken is returned when the number of results exceeds the specified pageSize value. To get the next page of results, call the getFeeds operation and include this token as the only parameter. Specifying nextToken with any other parameters will cause the request to fail.
     * @return Iterator
     *      - *payload* - 
     *      - *nextToken* - string
     *          - Returned when the number of results exceeds pageSize. To get the next page of results, call the getFeeds operation with this token as the only parameter.
     *      - *errors* - 
     */
    public function batchFeeds(array $query = []): Iterator
    {
        return $this->batchInternal('getFeeds', func_get_args());
    }
    
    /**
     * @description Effective June 27, 2023, the `getFeeds` operation will no longer be available in the Selling Partner API for Feeds v2020-09-04 and all calls to it will fail. Integrations that rely on this operation should migrate to [Feeds v2021-06-30](https://developer-docs.amazon.com/sp-api/docs/feeds-api-v2021-06-30-reference) to avoid service disruption.
     * @tag feeds
     * @param array $query
     *      - *feedTypes* - array - optional
     *          - A list of feed types used to filter feeds. When feedTypes is provided, the other filter parameters (processingStatuses, marketplaceIds, createdSince, createdUntil) and pageSize may also be provided. Either feedTypes or nextToken is required.
     *      - *marketplaceIds* - array - optional
     *          - A list of marketplace identifiers used to filter feeds. The feeds returned will match at least one of the marketplaces that you specify.
     *      - *pageSize* - integer - optional
     *          - The maximum number of feeds to return in a single call.
     *      - *processingStatuses* - array - optional
     *          - A list of processing statuses used to filter feeds.
     *      - *createdSince* - string - optional
     *          - The earliest feed creation date and time for feeds included in the response, in ISO 8601 format. The default is 90 days ago. Feeds are retained for a maximum of 90 days.
     *      - *createdUntil* - string - optional
     *          - The latest feed creation date and time for feeds included in the response, in ISO 8601 format. The default is now.
     *      - *nextToken* - string - optional
     *          - A string token returned in the response to your previous request. nextToken is returned when the number of results exceeds the specified pageSize value. To get the next page of results, call the getFeeds operation and include this token as the only parameter. Specifying nextToken with any other parameters will cause the request to fail.
     * @return array
     *      - *payload* - 
     *      - *nextToken* - string
     *          - Returned when the number of results exceeds pageSize. To get the next page of results, call the getFeeds operation with this token as the only parameter.
     *      - *errors* - 
     */
    public function getFeeds(array $query = []): array
    {
        return $this->api(array_merge(["/feeds/2020-09-04/feeds"], $query));
    }
                
    /**
     * @description Effective June 27, 2023, the `createFeed` operation will no longer be available in the Selling Partner API for Feeds v2020-09-04 and all calls to it will fail. Integrations that rely on this operation should migrate to [Feeds v2021-06-30](https://developer-docs.amazon.com/sp-api/docs/feeds-api-v2021-06-30-reference) to avoid service disruption.
     * @tag feeds
     * @param array $data 
     * @return array
     *      - *payload* - 
     *      - *errors* - 
     */
    public function createFeed(array $data): array
    {
        return $this->api("/feeds/2020-09-04/feeds", 'POST', $data);
    }
                    
    /**
     * @description Effective June 27, 2023, the `getFeed` operation will no longer be available in the Selling Partner API for Feeds v2020-09-04 and all calls to it will fail. Integrations that rely on this operation should migrate to [Feeds v2021-06-30](https://developer-docs.amazon.com/sp-api/docs/feeds-api-v2021-06-30-reference) to avoid service disruption.
     * @tag feeds
     * @param string $feedId The identifier for the feed. This identifier is unique only in combination with a seller ID.
     * @return array
     *      - *payload* - 
     *      - *errors* - 
     */
    public function getFeed(string $feedId): array
    {
        return $this->api("/feeds/2020-09-04/feeds/{$feedId}");
    }
                
    /**
     * @description Effective June 27, 2023, the `cancelFeed` operation will no longer be available in the Selling Partner API for Feeds v2020-09-04 and all calls to it will fail. Integrations that rely on this operation should migrate to [Feeds v2021-06-30](https://developer-docs.amazon.com/sp-api/docs/feeds-api-v2021-06-30-reference) to avoid service disruption.
     * @tag feeds
     * @param string $feedId The identifier for the feed. This identifier is unique only in combination with a seller ID.
     * @return array
     *      - *errors* - 
     */
    public function cancelFeed(string $feedId): array
    {
        return $this->api("/feeds/2020-09-04/feeds/{$feedId}", 'DELETE');
    }
                    
    /**
     * @description Effective June 27, 2023, the `createFeedDocument` operation will no longer be available in the Selling Partner API for Feeds v2020-09-04 and all calls to it will fail. Integrations that rely on this operation should migrate to [Feeds v2021-06-30](https://developer-docs.amazon.com/sp-api/docs/feeds-api-v2021-06-30-reference) to avoid service disruption.
     * @tag feeds
     * @param array $data 
     * @return array
     *      - *payload* - 
     *      - *errors* - 
     */
    public function createFeedDocument(array $data): array
    {
        return $this->api("/feeds/2020-09-04/documents", 'POST', $data);
    }
                    
    /**
     * @description Effective June 27, 2023, the `getFeedDocument` operation will no longer be available in the Selling Partner API for Feeds v2020-09-04 and all calls to it will fail. Integrations that rely on this operation should migrate to [Feeds v2021-06-30](https://developer-docs.amazon.com/sp-api/docs/feeds-api-v2021-06-30-reference) to avoid service disruption.
     * @tag feeds
     * @param string $feedDocumentId The identifier of the feed document.
     * @return array
     *      - *payload* - 
     *      - *errors* - 
     */
    public function getFeedDocument(string $feedDocumentId): array
    {
        return $this->api("/feeds/2020-09-04/documents/{$feedDocumentId}");
    }
    
}

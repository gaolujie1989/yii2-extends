<?php

namespace lujie\amazon\advertising\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description 
*/
class InsightsV3 extends \lujie\amazon\advertising\BaseAmazonAdvertisingClient
{

                
    /**
     * @description 

**Requires one of these permissions**:
["advertiser_campaign_edit","advertiser_campaign_view"]     * @tag Audience insights
     * @param string $audienceId The identifier of an audience.
     * @param array $query
     *      - *adType* - string - required
     *          - The advertising program.
     *      - *advertiserId* - string - optional
     *          - The identifier of the advertiser you'd like to retrieve overlapping audiences for. This parameter is required for the DSP adType, but is optional for the SD adType.
     *      - *minimumOverlapAffinity* - int - optional
     *          - If specified, the affinities of all returned overlapping audiences will be at least the provided affinity.
     *      - *maximumOverlapAffinity* - int - optional
     *          - If specified, the affinities of all returned overlapping audiences will be at most the provided affinity.
     *      - *audienceCategory* - array - optional
     *          - If specified, the categories of all returned overlapping audiences will be one of the provided categories.
     *      - *maxResults* - int - optional
     *          - Sets the maximum number of overlapping audiences in the response. This parameter is supported only for request to return `application/vnd.insightsaudiencesoverlap.v2+json`.
     *      - *nextToken* - string - optional
     *          - Token to be used to request additional overlapping audiences. If not provided, the top 30 overlapping audiences are returned. Note: subsequent calls must be made using the same parameters as used in previous requests.
     */
    public function insightsGetAudiencesOverlappingAudiences(string $audienceId, array $query): void
    {
        $this->api(array_merge(["/insights/audiences/{$audienceId}/overlappingAudiences"], $query));
    }
    
}

<?php

namespace lujie\amazon\advertising\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description Audience Discovery API
*/
class AudiencesV3 extends \lujie\amazon\advertising\BaseAmazonAdvertisingClient
{

                
    /**
     * @description Updates an existing targeting audience based on an audience definition and audience ID.

**Requires one of these permissions**:
["advertiser_campaign_edit"]     * @tag Ads
     * @param array $data 
     *      - *dspAudienceEditRequestItems* - array
     *          - A list of audience edit objects containing fields to be overwritten. For each object, specify fields and their values to be modified.
     * @return array
     *      - *success* - array
     *      - *failed* - array
     */
    public function dspAudienceEdit(array $data): array
    {
        return $this->api("/dsp/audiences/edit", 'PUT', $data, ['content-type' => 'application/vnd.dspaudiences.v1+json']);
    }
                    
    /**
     * @description Deletes an existing targeting audience based on audience ID. Only available for the audiences of the type: *PRODUCT_PURCHASES*, *PRODUCT_VIEWS*, *PRODUCT_SIMS*, *PRODUCT_SEARCH* and *COMBINED_AUDIENCE*

**Requires one of these permissions**:
["advertiser_campaign_edit"]     * @tag Ads
     * @param array $data 
     *      - *dspAudienceDeleteRequestItems* - array
     *          - A list of audiences to be deleted
     * @return array
     *      - *success* - array
     *      - *failed* - array
     */
    public function dspAudienceDelete(array $data): array
    {
        return $this->api("/dsp/audiences/delete", 'POST', $data, ['content-type' => 'application/vnd.dspaudiences.v1+json']);
    }
                    
    /**
     * @description Returns a list of audience segments for an advertiser. The result set can be filtered by providing an array of Filter objects. Each item in the resulting set will match all specified filters.     * @tag Discovery
     * @param array $data 
     *      - *adType* - string
     *      - *countries* - array
     *          - The ISO Alpha-2 country codes to search audiences from. This field must be specified if the advertiser does not have an associated country. Currently, it is only supported to specify a single country per request.
     *      - *filters* - array
     * @param array $query
     *      - *advertiserId* - string - optional
     *          - The advertiser to retrieve segments for. This parameter is required for the DSP adType, but optional for the SD adType.
     *      - *canTarget* - boolean - optional
     *          - When set to true, only targetable audience segments will be returned.
     *      - *nextToken* - string - optional
     *          - Token from a previous request. Use in conjunction with the `maxResults` parameter to control pagination of the returned array.
     *      - *maxResults* - integer - optional
     *          - Sets the maximum number of audiences in the returned array. Use in conjunction with the `nextToken` parameter to control pagination. For example, supplying maxResults=20 with a previously returned token will fetch up to the next 20 items. In some cases, fewer items may be returned.
     * @return array
     *      - *nextToken* - string
     *      - *audiences* - array
     *          - Array of segments matching given filters sorted by create time, earliest first.
     *      - *matchCount* - integer
     */
    public function listAudiences(array $data, array $query = []): array
    {
        return $this->api(array_merge(["/audiences/list"], $query), 'POST', $data, ['content-type' => 'application/json']);
    }
                    
    /**
     * @description Returns a list of audience categories for a given category path     * @tag Discovery
     * @param array $data 
     *      - *adType* - string
     *      - *categoryPath* - array
     *      - *countries* - array
     *          - The ISO Alpha-2 country codes to search audiences from. This field must be specified if the advertiser does not have an associated country. Currently, it is only supported to specify a single country per request.
     * @param array $query
     *      - *advertiserId* - string - optional
     *          - The advertiser associated with the advertising account. This parameter is required for the DSP adType, but optional for the SD adType.
     *      - *nextToken* - string - optional
     *          - Token from a previous request. Use in conjunction with the `maxResults` parameter to control pagination of the returned array.
     *      - *maxResults* - integer - optional
     *          - Sets the maximum number of categories in the returned array. Use in conjunction with the `nextToken` parameter to control pagination. For example, supplying maxResults=20 with a previously returned token will fetch up to the next 20 items. In some cases, fewer items may be returned.
     * @return array
     *      - *categoryPath* - array
     *      - *nextToken* - string
     *      - *categories* - array
     */
    public function fetchTaxonomy(array $data, array $query = []): array
    {
        return $this->api(array_merge(["/audiences/taxonomy/list"], $query), 'POST', $data, ['content-type' => 'application/json']);
    }
    
}

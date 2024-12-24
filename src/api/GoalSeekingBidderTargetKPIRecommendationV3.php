<?php

namespace lujie\amazon\advertising\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description Creates a Target KPI recommendation for advertisers when they are in the process of creating a new campaign (ADSP).
*/
class GoalSeekingBidderTargetKPIRecommendationV3 extends \lujie\amazon\advertising\BaseAmazonAdvertisingClient
{

                
    /**
     * @description Creates a Target KPI recommendation for advertisers when they are in the process of creating a new campaign (ADSP).     * @tag 
     * @param array $data 
     *      - *flightEndDate* - string
     *          - The campaign flight end date in YYYY-MM-DD format.
     *      - *flightStartDate* - string
     *          - The campaign flight start date in YYYY-MM-DD format.
     *      - *advertiserIndustry* - string
     *          - The industry or sector of the advertiser.
     *      - *goalKpi* - string
     *          - The key performance indicator (KPI) for the campaign.
     *      - *budgetAmount* - number
     *          - Budget amount set by the user. Will be null in case of Pre-Budget scenario.
     *      - *advertiserCountry* - string
     *          - The name of the country associated with the advertiser.
     *      - *entityId* - string
     *          - The identifier of the entity.
     *      - *currencyCode* - string
     *          - The currency code (e.g., USD, EUR) used for the budget.
     *      - *advertiserId* - string
     *          - The identifier of the advertiser.
     * @return array
     *      - *modelBasedRecommendation* - boolean
     *          - Boolean value to signify if recommendation was generated by the model or by a heuristic.
     *      - *goalKpi* - string
     *          - The key performance indicator (KPI) for the campaign.
     *      - *recommendedKpi* - 
     *      - *currencyCode* - string
     *          - The currency code of the recommended KPI value.
     */
    public function getGsbTargetKpiRecommendation(array $data): array
    {
        return $this->api("/dsp/campaigns/targetKpi/recommendations", 'POST', $data, ['content-type' => 'application/vnd.gsbtargetkpirecommendation.v1+json']);
    }
    
}

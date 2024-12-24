<?php

namespace lujie\amazon\advertising\api;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description DSP Persona Builder API.
*/
class PersonaBuilderAPIV3 extends \lujie\amazon\advertising\BaseAmazonAdvertisingClient
{

                
    /**
     * @description Get insights on top retail categories purchased by customers in the input expression.

**Requires one of these permissions**:
[]     * @tag Persona Builder API
     * @param array $data 
     *      - *audienceTargetingExpression* - 
     * @param array $query
     *      - *advertiserId* - string - required
     *          - The identifier of the advertiser, retrieved from /dsp/advertisers, that you'd like to retrieve insights for.
     *      - *maxResults* - number - optional
     *          - Sets the maximum number of objects in the returned array. Use in conjunction with the nextToken parameter to control pagination. For example, supplying maxResults=20 with a previously returned token will fetch up to the next 20 items. In some cases, fewer items may be returned.<br/>Default: 30; <br/>Minimum: 1; <br/>Maximum:250.
     *      - *nextToken* - string - optional
     *          - Operations that return paginated results include a pagination token in this field. To retrieve the next page of results, call the same operation and specify this token in the request. If the nextToken field is empty, there are no further results.
     * @return array
     *      - *retailCategories* - array
     *          - Top retail categories purchased by customers in the input expression., ordered by the affinity score.
                    
 
 Affinity represents a measure of how likely customers in the input expression are to make a purchase
                    from the category. An affinity of 5 indicates that customers in the input expression are 5 times as likely to
                    buy from this category than the average of customers on Amazon.
     *      - *lastUpdatedAt* - string
     *          - UTC timestamp in ISO 8601 format indicating when insight was last generated for the input audience set.
     *      - *nextToken* - string
     *          - Optional: If present, there are more insights than initially returned. Use this token to call the operation again
                    and have the additional insights returned. The token is valid for 8 hours from the initial request.
     */
    public function topCategoriesPurchased(array $data, array $query): array
    {
        return $this->api(array_merge(["/insights/topCategoriesPurchased"], $query), 'POST', $data, ['content-type' => 'application/vnd.topcategoriespurchasedinputexpression.v1+json']);
    }
                    
    /**
     * @description Get banded size of number of unique customers that are in the input expression.

**Requires one of these permissions**:
[]     * @tag Persona Builder API
     * @param array $data 
     *      - *audienceTargetingExpression* - 
     * @param array $query
     *      - *advertiserId* - string - required
     *          - The identifier of the advertiser, retrieved from /dsp/advertisers, that you'd like to retrieve insights for.
     * @return array
     *      - *estimatedSize* - 
     *      - *lastUpdatedAt* - string
     *          - UTC timestamp in ISO 8601 format indicating when insight was last generated for the audience targeting expression.
     */
    public function bandedSize(array $data, array $query): array
    {
        return $this->api(array_merge(["/insights/bandedSize"], $query), 'POST', $data, ['content-type' => 'application/vnd.bandedsizeinputexpression.v1+json']);
    }
                    
    /**
     * @description Get demographic insights for the input expression.

**Requires one of these permissions**:
[]     * @tag Persona Builder API
     * @param array $data 
     *      - *audienceTargetingExpression* - 
     * @param array $query
     *      - *advertiserId* - string - required
     *          - The identifier of the advertiser, retrieved from /dsp/advertisers, that you'd like to retrieve insights for.
     * @return array
     *      - *lastUpdatedAt* - string
     *          - UTC timestamp in ISO 8601 format indicating when insight was last generated for the input audience set.
     *      - *demographics* - 
     */
    public function demographics(array $data, array $query): array
    {
        return $this->api(array_merge(["/insights/demographics"], $query), 'POST', $data, ['content-type' => 'application/vnd.demographicinputexpressions.v1+json']);
    }
                    
    /**
     * @description Get Prime Video insights for the input expression.

**Requires one of these permissions**:
[]     * @tag Persona Builder API
     * @param array $data 
     *      - *categoryFilter* - array
     *          - Optional: A list of prime video categories to filter insights on. By default it will return all
                   prime video category types in response.
     *      - *audienceTargetingExpression* - 
     * @param array $query
     *      - *advertiserId* - string - required
     *          - The identifier of the advertiser, retrieved from /dsp/advertisers, that you'd like to retrieve insights for.
     * @return array
     *      - *lastUpdatedAt* - string
     *          - UTC timestamp in ISO 8601 format indicating when insight was last generated for the input expression.
     *      - *primeVideoInsights* - 
     */
    public function primeVideo(array $data, array $query): array
    {
        return $this->api(array_merge(["/insights/primeVideo"], $query), 'POST', $data, ['content-type' => 'application/vnd.primevideoinputexpressions.v1+json']);
    }
                    
    /**
     * @description Get top audiences overlapping with the input expression.     * @tag Persona Builder API
     * @param array $data 
     *      - *categoryFilter* - array
     *          - Optional: A list of audience categories to filter insights on. By default it will return all audience category types in response, ordered by affinity score.
     *      - *audienceTargetingExpression* - 
     * @param array $query
     *      - *advertiserId* - string - required
     *          - The identifier of the advertiser, retrieved from /dsp/advertisers, that you'd like to retrieve insights for.
     *      - *maxResults* - number - optional
     *          - Sets the maximum number of objects in the returned array. Use in conjunction with the nextToken parameter to control pagination. For example, supplying maxResults=20 with a previously returned token will fetch up to the next 20 items. In some cases, fewer items may be returned.<br/>Default: 30; <br/>Minimum: 1; <br/>Maximum:250.
     *      - *nextToken* - string - optional
     *          - Operations that return paginated results include a pagination token in this field. To retrieve the next page of results, call the same operation and specify this token in the request. If the nextToken field is empty, there are no further results.
     * @return array
     *      - *lastUpdatedAt* - string
     *          - UTC timestamp in ISO 8601 format indicating when insight was last generated for the input audience set.
     *      - *nextToken* - string
     *          - Optional: If present, there are more insights than initially returned. Use this token to call the operation again and have the additional insights returned. The token is valid for 8 hours from the initial request.
     *      - *audiences* - array
     *          - Top audiences associated with customers in the input expression, ordered by the affinity score.
                    
 Affinity is a measure of how likely customers in the input expression are to belong to a specific
                    audience. An affinity of 5 for some audience indicates that customers in the input expression set
                    are 5 times as likely to belong to this audience than the average of customers on Amazon.
     */
    public function topOverlappingAudiences(array $data, array $query): array
    {
        return $this->api(array_merge(["/insights/topOverlappingAudiences"], $query), 'POST', $data, ['content-type' => 'application/vnd.topoverlappingaudiencesinputexpression.v1+json']);
    }
    
}

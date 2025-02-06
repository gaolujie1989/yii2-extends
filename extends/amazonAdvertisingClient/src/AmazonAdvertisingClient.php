<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\amazon\advertising;

use lujie\extend\authclient\RestOAuth2;
use lujie\extend\helpers\HttpClientHelper;
use yii\authclient\InvalidResponseException;
use yii\authclient\OAuthToken;
use yii\base\InvalidCallException;
use yii\httpclient\Request;

/**
 * Class AmazonAdvertisingClient
 *
 * @method array listV3ProductCampaigns($data = [])
 * @method \Generator eachV3ProductCampaigns($condition = [], $batchSize = 100)
 * @method \Generator batchV3ProductCampaigns($condition = [], $batchSize = 100)
 * @method array bulkCreateV3ProductCampaigns($data)
 * @method array bulkUpdateV3ProductCampaigns($data)
 * @method array bulkDeleteV3ProductCampaigns($data)
 *
 * @method array listV3ProductAdGroups($data = [])
 * @method \Generator eachV3ProductAdGroups($condition = [], $batchSize = 100)
 * @method \Generator batchV3ProductAdGroups($condition = [], $batchSize = 100)
 * @method array bulkCreateV3ProductAdGroups($data)
 * @method array bulkUpdateV3ProductAdGroups($data)
 * @method array bulkDeleteV3ProductAdGroups($data)
 *
 * @method array listV3ProductAds($data = [])
 * @method \Generator eachV3ProductAds($condition = [], $batchSize = 100)
 * @method \Generator batchV3ProductAds($condition = [], $batchSize = 100)
 * @method array bulkCreateV3ProductAds($data)
 * @method array bulkUpdateV3ProductAds($data)
 * @method array bulkDeleteV3ProductAds($data)
 *
 * @method array listV3ProductKeywords($data = [])
 * @method \Generator eachV3ProductKeywords($condition = [], $batchSize = 100)
 * @method \Generator batchV3ProductKeywords($condition = [], $batchSize = 100)
 * @method array bulkCreateV3ProductKeywords($data)
 * @method array bulkUpdateV3ProductKeywords($data)
 * @method array bulkDeleteV3ProductKeywords($data)
 *
 * @method array listV3ProductNegativeKeywords($data = [])
 * @method \Generator eachV3ProductNegativeKeywords($condition = [], $batchSize = 100)
 * @method \Generator batchV3ProductNegativeKeywords($condition = [], $batchSize = 100)
 * @method array bulkCreateV3ProductNegativeKeywords($data)
 * @method array bulkUpdateV3ProductNegativeKeywords($data)
 * @method array bulkDeleteV3ProductNegativeKeywords($data)
 *
 * @method array listV3ProductCampaignNegativeKeywords($data = [])
 * @method \Generator eachV3ProductCampaignNegativeKeywords($condition = [], $batchSize = 100)
 * @method \Generator batchV3ProductCampaignNegativeKeywords($condition = [], $batchSize = 100)
 * @method array bulkCreateV3ProductCampaignNegativeKeywords($data)
 * @method array bulkUpdateV3ProductCampaignNegativeKeywords($data)
 * @method array bulkDeleteV3ProductCampaignNegativeKeywords($data)
 *
 * @method array listV3ProductTargets($data = [])
 * @method \Generator eachV3ProductTargets($condition = [], $batchSize = 100)
 * @method \Generator batchV3ProductTargets($condition = [], $batchSize = 100)
 * @method array bulkCreateV3ProductTargets($data)
 * @method array bulkUpdateV3ProductTargets($data)
 * @method array bulkDeleteV3ProductTargets($data)
 *
 * @method array listV3ProductNegativeTargets($data = [])
 * @method \Generator eachV3ProductNegativeTargets($condition = [], $batchSize = 100)
 * @method \Generator batchV3ProductNegativeTargets($condition = [], $batchSize = 100)
 * @method array bulkCreateV3ProductNegativeTargets($data)
 * @method array bulkUpdateV3ProductNegativeTargets($data)
 * @method array bulkDeleteV3ProductNegativeTargets($data)
 *
 * @method array listV3ProductCampaignNegativeTargets($data = [])
 * @method \Generator eachV3ProductCampaignNegativeTargets($condition = [], $batchSize = 100)
 * @method \Generator batchV3ProductCampaignNegativeTargets($condition = [], $batchSize = 100)
 * @method array bulkCreateV3ProductCampaignNegativeTargets($data)
 * @method array bulkUpdateV3ProductCampaignNegativeTargets($data)
 * @method array bulkDeleteV3ProductCampaignNegativeTargets($data)
 *
 * @method array listV3DisplayCampaigns($data = [])
 * @method \Generator eachV3DisplayCampaigns($condition = [], $batchSize = 100)
 * @method \Generator batchV3DisplayCampaigns($condition = [], $batchSize = 100)
 * @method array getV3DisplayCampaign($data)
 * @method array bulkCreateV3DisplayCampaigns($data)
 * @method array bulkUpdateV3DisplayCampaigns($data)
 * @method array deleteV3DisplayCampaign($data)
 *
 * @method array listExtendV3DisplayCampaigns($data = [])
 * @method \Generator eachExtendV3DisplayCampaigns($condition = [], $batchSize = 100)
 * @method \Generator batchExtendV3DisplayCampaigns($condition = [], $batchSize = 100)
 * @method array getExtendV3DisplayCampaign($data)
 *
 * @method array listV3DisplayAdGroups($data = [])
 * @method \Generator eachV3DisplayAdGroups($condition = [], $batchSize = 100)
 * @method \Generator batchV3DisplayAdGroups($condition = [], $batchSize = 100)
 * @method array getV3DisplayAdGroup($data)
 * @method array bulkCreateV3DisplayAdGroups($data)
 * @method array bulkUpdateV3DisplayAdGroups($data)
 * @method array deleteV3DisplayAdGroup($data)
 *
 * @method array listExtendV3DisplayAdGroups($data = [])
 * @method \Generator eachExtendV3DisplayAdGroups($condition = [], $batchSize = 100)
 * @method \Generator batchExtendV3DisplayAdGroups($condition = [], $batchSize = 100)
 * @method array getExtendV3DisplayAdGroup($data)
 *
 * @method array listV3DisplayAds($data = [])
 * @method \Generator eachV3DisplayAds($condition = [], $batchSize = 100)
 * @method \Generator batchV3DisplayAds($condition = [], $batchSize = 100)
 * @method array getV3DisplayAd($data)
 * @method array bulkCreateV3DisplayAds($data)
 * @method array bulkUpdateV3DisplayAds($data)
 * @method array deleteV3DisplayAd($data)
 *
 * @method array listExtendV3DisplayAds($data = [])
 * @method \Generator eachExtendV3DisplayAds($condition = [], $batchSize = 100)
 * @method \Generator batchExtendV3DisplayAds($condition = [], $batchSize = 100)
 * @method array getExtendV3DisplayAd($data)
 *
 * @method array listV3DisplayTargets($data = [])
 * @method \Generator eachV3DisplayTargets($condition = [], $batchSize = 100)
 * @method \Generator batchV3DisplayTargets($condition = [], $batchSize = 100)
 * @method array getV3DisplayTarget($data)
 * @method array bulkCreateV3DisplayTargets($data)
 * @method array bulkUpdateV3DisplayTargets($data)
 * @method array deleteV3DisplayTarget($data)
 *
 * @method array listExtendV3DisplayTargets($data = [])
 * @method \Generator eachExtendV3DisplayTargets($condition = [], $batchSize = 100)
 * @method \Generator batchExtendV3DisplayTargets($condition = [], $batchSize = 100)
 * @method array getExtendV3DisplayTarget($data)
 *
 * @method array listV3DisplayNegativeTargets($data = [])
 * @method \Generator eachV3DisplayNegativeTargets($condition = [], $batchSize = 100)
 * @method \Generator batchV3DisplayNegativeTargets($condition = [], $batchSize = 100)
 * @method array getV3DisplayNegativeTarget($data)
 * @method array bulkCreateV3DisplayNegativeTargets($data)
 * @method array bulkUpdateV3DisplayNegativeTargets($data)
 * @method array deleteV3DisplayNegativeTarget($data)
 *
 * @method array listExtendV3DisplayNegativeTargets($data = [])
 * @method \Generator eachExtendV3DisplayNegativeTargets($condition = [], $batchSize = 100)
 * @method \Generator batchExtendV3DisplayNegativeTargets($condition = [], $batchSize = 100)
 * @method array getExtendV3DisplayNegativeTarget($data)
 *
 * @method array listV4BrandCampaigns($data = [])
 * @method \Generator eachV4BrandCampaigns($condition = [], $batchSize = 100)
 * @method \Generator batchV4BrandCampaigns($condition = [], $batchSize = 100)
 * @method array bulkCreateV4BrandCampaigns($data)
 * @method array bulkUpdateV4BrandCampaigns($data)
 * @method array bulkDeleteV4BrandCampaigns($data)
 *
 * @method array listV4BrandAdGroups($data = [])
 * @method \Generator eachV4BrandAdGroups($condition = [], $batchSize = 100)
 * @method \Generator batchV4BrandAdGroups($condition = [], $batchSize = 100)
 * @method array bulkCreateV4BrandAdGroups($data)
 * @method array bulkUpdateV4BrandAdGroups($data)
 * @method array bulkDeleteV4BrandAdGroups($data)
 *
 * @method array listV4BrandAds($data = [])
 * @method \Generator eachV4BrandAds($condition = [], $batchSize = 100)
 * @method \Generator batchV4BrandAds($condition = [], $batchSize = 100)
 * @method array bulkCreateV4BrandAds($data)
 * @method array bulkUpdateV4BrandAds($data)
 * @method array bulkDeleteV4BrandAds($data)
 * @method array bulkCreateProductCollectionV4BrandAd($data)
 * @method array bulkCreateVideoV4BrandAd($data)
 * @method array bulkCreateBrandVideoV4BrandAd($data)
 * @method array bulkCreateStoreSpotlightV4BrandAd($data)
 *
 * @method array listV3BrandKeywords($data = [])
 * @method \Generator eachV3BrandKeywords($condition = [], $batchSize = 100)
 * @method \Generator batchV3BrandKeywords($condition = [], $batchSize = 100)
 * @method array getV3BrandKeyword($data)
 * @method array bulkCreateV3BrandKeywords($data)
 * @method array bulkUpdateV3BrandKeywords($data)
 * @method array deleteV3BrandKeyword($data)
 *
 * @method array listV3BrandNegativeKeywords($data = [])
 * @method \Generator eachV3BrandNegativeKeywords($condition = [], $batchSize = 100)
 * @method \Generator batchV3BrandNegativeKeywords($condition = [], $batchSize = 100)
 * @method array getV3BrandNegativeKeyword($data)
 * @method array bulkCreateV3BrandNegativeKeywords($data)
 * @method array bulkUpdateV3BrandNegativeKeywords($data)
 * @method array deleteV3BrandNegativeKeyword($data)
 *
 * @method array listV3BrandTargets($data = [])
 * @method \Generator eachV3BrandTargets($condition = [], $batchSize = 100)
 * @method \Generator batchV3BrandTargets($condition = [], $batchSize = 100)
 * @method array getV3BrandTarget($data)
 * @method array bulkCreateV3BrandTargets($data)
 * @method array bulkUpdateV3BrandTargets($data)
 * @method array deleteV3BrandTarget($data)
 *
 * @method array listV3BrandNegativeTargets($data = [])
 * @method \Generator eachV3BrandNegativeTargets($condition = [], $batchSize = 100)
 * @method \Generator batchV3BrandNegativeTargets($condition = [], $batchSize = 100)
 * @method array getV3BrandNegativeTarget($data)
 * @method array bulkCreateV3BrandNegativeTargets($data)
 * @method array bulkUpdateV3BrandNegativeTargets($data)
 * @method array deleteV3BrandNegativeTarget($data)
 *
 * @method array listV2Profiles($data = [])
 * @method \Generator eachV2Profiles($condition = [], $batchSize = 100)
 * @method \Generator batchV2Profiles($condition = [], $batchSize = 100)
 * @method array createV2BrandReport($data)
 * @method array createV2DisplayReport($data)
 * @method array getV2Report($data)
 * @method array downloadV2Report($data)
 * @method array createV3Report($data)
 * @method array getV3Report($data)
 * @method array createV2ProductSnapshot($data)
 * @method array createV2BrandSnapshot($data)
 * @method array getV2ProductSnapshot($data)
 * @method array getV2BrandSnapshot($data)
 * @method array downloadV2ProductSnapshot($data)
 * @method array downloadV2BrandSnapshot($data)
 * @method array createV3DisplaySnapshot($data)
 * @method array getV3DisplaySnapshot($data)
 * @method array downloadV3DisplaySnapshot($data)
 * @method array getV3TargetBidRecommendations($data)
 * @method array getV3TargetKeywordRecommendations($data)
 *
 * @package lujie\amazon\advertising
 * @author Lujie Zhou <gao_lujie@live.cn>
 * @document https://advertising.amazon.com/API/docs/en-us/index
 * @deprecated
 */
class AmazonAdvertisingClient extends RestOAuth2
{
    /**
     * @var string
     */
    public $apiBaseUrl = AmazonAdvertisingConst::API_URL_EU;

    /**
     * @var string
     */
    public $authUrl = AmazonAdvertisingConst::AUTH_URL_EU;

    /**
     * @var string
     */
    public $tokenUrl = AmazonAdvertisingConst::TOKEN_URL_EU;

    /**
     * @var string
     */
    public $scope = AmazonAdvertisingConst::SCOPE_DSP;

    /**
     * @var int
     */
    public $profileId;

    /**
     * @var array[]
     */
    public $httpClientOptions = [
        'requestConfig' => [
            'format' => 'json',
        ],
        'responseConfig' => [
            'format' => 'json'
        ],
    ];

    #region Rest

    /**
     * @var array
     */
    public $resources = [
        //V3Product
        'V3ProductCampaign' => '/sp/campaigns',
        'V3ProductAdGroup' => '/sp/adGroups',
        'V3ProductAd' => '/sp/productAds',
        'V3ProductKeyword' => '/sp/keywords',
        'V3ProductNegativeKeyword' => '/sp/negativeKeywords',
        'V3ProductCampaignNegativeKeyword' => '/sp/campaignNegativeKeywords',
        'V3ProductTarget' => '/sp/targets',
        'V3ProductNegativeTarget' => '/sp/negativeTargets',
        'V3ProductCampaignNegativeTarget' => '/sp/campaignNegativeTargets',
        //V3Display
        'V3DisplayCampaign' => '/sd/campaigns',
        'V3DisplayAdGroup' => '/sd/adGroups',
        'V3DisplayAd' => '/sd/productAds',
        'V3DisplayTarget' => '/sd/targets',
        'V3DisplayNegativeTarget' => '/sd/negativeTargets',
        //V3V4Brand
        'V4BrandCampaign' => '/sb/v4/campaigns',
        'V4BrandAdGroup' => '/sb/v4/adGroups',
        'V4BrandAd' => '/sb/v4/productAds',
        'V3BrandKeyword' => '/sb/keywords',
        'V3BrandNegativeKeyword' => '/sb/negativeKeywords',
        'V3BrandTarget' => '/sb/targets',
        'V3BrandNegativeTarget' => '/sb/negativeTargets',
    ];

    public $actions = [];

    /**
     * @var array
     */
    public $extraActions = [
        'V4BrandAd' => [
            'bulkCreateProductCollection' => ['POST', 'productCollection'],
            'bulkCreateVideo' => ['POST', 'video'],
            'bulkCreateBrandVideo' => ['POST', 'brandVideo'],
            'bulkCreateStoreSpotlight' => ['POST', '/sb/v4/ads/storeSpotlight'],
        ]
    ];

    public $versionActions = [
        'V3Product' => [
            'list' => ['POST', 'list'],
            'bulkCreate' => ['POST', ''],
            'bulkUpdate' => ['PUT', ''],
            'bulkDelete' => ['POST', 'delete'],
        ],
        'V3Display' => [
            'list' => ['GET', ''],
            'get' => ['GET', '{id}'],
            'bulkCreate' => ['POST', ''],
            'bulkUpdate' => ['PUT', ''],
            'delete' => ['DELETE', '{id}'],
            'listExtend' => ['GET', ''],
            'getExtend' => ['GET', '{id}'],
        ],
        'V4Brand' => [
            'list' => ['POST', 'list'],
            'bulkCreate' => ['POST', ''],
            'bulkUpdate' => ['PUT', ''],
            'bulkDelete' => ['POST', 'delete'],
        ],
        'V3Brand' => [
            'list' => ['POST', 'list'],
            'get' => ['GET', '{id}'],
            'bulkCreate' => ['POST', ''],
            'bulkUpdate' => ['PUT', ''],
            'delete' => ['DELETE', '{id}'],
        ]
    ];

    public $pluralize = [
        'list',
        'listExtend',
        'bulkCreate',
        'bulkUpdate',
        'bulkDelete',
    ];

    /**
     * @var array
     */
    public $extraMethods = [
        //Common Resources
        'listV2Profiles' => ['GET', '/v2/profiles'],
        //V2Reports
        'createV2BrandReport' => ['POST', '/v2/hsa/{recordType}/report', true],
        'createV2DisplayReport' => ['POST', '/sd/{recordType}/report', true],
        'getV2Report' => ['GET', '/v2/reports/{id}', true],
        'downloadV2Report' => ['GET', '/v2/reports/{id}/download', true],
        //V3Reports
        'createV3Report' => ['POST', '/reporting/reports'],
        'getV3Report' => ['GET', '/reporting/reports/{id}', true],
        //V2Snapshots
        'createV2ProductSnapshot' => ['POST', '/v2/sp/{recordType}/snapshot', true],
        'createV2BrandSnapshot' => ['POST', '/v2/hsa/{recordType}/snapshot', true],
        'getV2ProductSnapshot' => ['GET', '/v2/sp/snapshots/{id}', true],
        'getV2BrandSnapshot' => ['GET', '/v2/hsa/snapshots/{id}', true],
        'downloadV2ProductSnapshot' => ['GET', '/v2/sp/snapshots/{id}/download', true],
        'downloadV2BrandSnapshot' => ['GET', '/v2/hsa/snapshots/{id}/download', true],
        //V3Snapshots
        'createV3DisplaySnapshot' => ['POST', '/sd/{recordType}/snapshot', true],
        'getV3DisplaySnapshot' => ['GET', '/sd/snapshots/{id}', true],
        'downloadV3DisplaySnapshot' => ['GET', '/sd/snapshots/{id}/download', true],
        //V3Products Recommendations
        'getV3TargetBidRecommendations' => ['POST', '/sp/targets/bid/recommendations'],
        'getV3TargetKeywordRecommendations' => ['POST', '/sp/targets/keywords/recommendations'],
    ];

    #endregion

    public $customHeaders = [
        '/sp/targets/bid/recommendations' => [
            'Accept' => 'application/vnd.spthemebasedbidrecommendation.v3+json',
            'Content-Type' => 'application/vnd.spthemebasedbidrecommendation.v3+json',
        ],
        //V3Report
        '/reporting/reports' => [
            'Content-Type' => 'application/vnd.createasyncreportrequest.v3+json',
        ],
        //V3Product
        '/sp/campaigns' => [
            'Accept' => 'application/vnd.spCampaign.v3+json',
            'Content-Type' => 'application/vnd.spCampaign.v3+json'
        ],
        '/sp/adGroups' => [
            'Accept' => 'application/vnd.spAdGroup.v3+json',
            'Content-Type' => 'application/vnd.spAdGroup.v3+json'
        ],
        '/sp/productAds' => [
            'Accept' => 'application/vnd.spProductAd.v3+json',
            'Content-Type' => 'application/vnd.spProductAd.v3+json'
        ],
        '/sp/keywords' => [
            'Accept' => 'application/vnd.spKeyword.v3+json',
            'Content-Type' => 'application/vnd.spKeyword.v3+json'
        ],
        '/sp/negativeKeywords' => [
            'Accept' => 'application/vnd.spNegativeKeyword.v3+json',
            'Content-Type' => 'application/vnd.spNegativeKeyword.v3+json'
        ],
        '/sp/campaignNegativeKeywords' => [
            'Accept' => 'application/vnd.spNegativeKeyword.v3+json',
            'Content-Type' => 'application/vnd.spNegativeKeyword.v3+json'
        ],
        '/sp/targets' => [
            'Accept' => 'application/vnd.spTargetingClause.v3+json',
            'Content-Type' => 'application/vnd.spTargetingClause.v3+json'
        ],
        '/sp/negativeTargets' => [
            'Accept' => 'application/vnd.spNegativeTargetingClause.v3+json',
            'Content-Type' => 'application/vnd.spNegativeTargetingClause.v3+json'
        ],
        '/sp/campaignNegativeTargets' => [
            'Accept' => 'application/vnd.spNegativeTargetingClause.v3+json',
            'Content-Type' => 'application/vnd.spNegativeTargetingClause.v3+json'
        ],
        //V4Brand
        '/sd/v4/campaigns' => [
            'Accept' => 'application/vnd.sbcampaignresource.v4+json',
            'Content-Type' => 'application/vnd.sbcampaignresource.v4+json'
        ],
        '/sb/v4/adGroups' => [
            'Accept' => 'application/vnd.sbadgroupresource.v4+json',
            'Content-Type' => 'application/vnd.sbadgroupresource.v4+json'
        ],
        '/sb/v4/productAds' => [
            'Accept' => 'application/vnd.sbadresource.v4+json',
            'Content-Type' => 'application/vnd.sbadresource.v4+json'
        ],
        //@TODO keyword、target 不同操作不同的header。。。
        '/sb/keywords' => [
        ],
        '/sb/negativeKeywords' => [
        ],
        '/sb/targets' => [
        ],
        '/sb/negativeTargets' => [
        ],
    ];

    #region rest

    public function init(): void
    {
        $this->initExtraActions();
        parent::init();
    }

    /**
     * @inheritdoc
     */
    protected function initExtraActions(): void
    {
        foreach ($this->resources as $resource => $url) {
            foreach ($this->versionActions as $version => $actions) {
                if (str_contains($resource, $version)) {
                    $this->extraActions[$resource] = array_merge($actions, $this->extraActions[$resource] ?? []);
                }
            }
        }
    }

    #endregion

    #region auth

    /**
     * @param $profileId
     * @return $this
     * @inheritdoc
     */
    public function setProfileId($profileId): self
    {
        $this->profileId = $profileId;
        return $this;
    }

    /**
     * @param Request $request
     * @param OAuthToken $accessToken
     * @inheritdoc
     */
    public function applyAccessTokenToRequest($request, $accessToken): void
    {
        $headers = [
            'Amazon-Advertising-API-ClientId' => $this->clientId,
            'Authorization' => 'Bearer ' . $accessToken->getToken(),
        ];
        if ($this->profileId) {
            $headers['Amazon-Advertising-API-Scope'] = $this->profileId;
        } else if (!str_contains($request->getUrl(), 'profiles')) {
            throw new InvalidCallException('Missing profileId');
        }
        foreach ($this->customHeaders as $pathPrefix => $pathHeaders) {
            if (str_starts_with($request->getUrl(), $pathPrefix)) {
                $headers = array_merge($headers, $pathHeaders);
                break;
            }
        }
        if (empty($headers['Accept'])) {
            $headers['Accept'] = 'application/json';
        }
        $headerCollection = $request->getHeaders();
        foreach ($headers as $key => $header) {
            $headerCollection->set($key, $header);
        }
    }

    #endregion

    /**
     * @param Request $request
     * @return array|mixed|null
     * @throws InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    protected function sendRequest($request)
    {
        $response = HttpClientHelper::sendRequest($request, ['403']);
        if ($response->getStatusCode() === '307') {
            $location = $response->getHeaders()->get('Location');
            $newRequest = $this->createRequest()->setUrl($location);
            //If 403 expired, retry old request
            $newResponse = HttpClientHelper::sendRequest($newRequest, ['403']);
            if ($newResponse->getStatusCode() === '403') {
                return $this->sendRequest($request);
            }
            $content = $newResponse->getContent();
            return [$content];
        }
        return $response->getData();
    }
}

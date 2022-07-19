<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\amazon\advertising;

use Iterator;
use lujie\extend\authclient\RestApiTrait;
use lujie\extend\authclient\RestClientTrait;
use lujie\extend\helpers\HttpClientHelper;
use yii\authclient\BaseClient;
use yii\authclient\InvalidResponseException;
use yii\authclient\OAuth2;
use yii\authclient\OAuthToken;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\httpclient\Request;

/**
 * Class AmazonAdvertisingClient
 *
 * @method array listProfiles($data = [])
 * @method \Generator eachProfile($condition = [], $batchSize = 100)
 * @method \Generator batchProfile($condition = [], $batchSize = 100)
 * @method array getProfile($data)
 * @method array createProfile($data)
 * @method array updateProfile($data)
 * @method array deleteProfile($data)
 *
 * @method array listCampaigns($data = [])
 * @method \Generator eachCampaign($condition = [], $batchSize = 100)
 * @method \Generator batchCampaign($condition = [], $batchSize = 100)
 * @method array getCampaign($data)
 * @method array createCampaign($data)
 * @method array updateCampaign($data)
 * @method array deleteCampaign($data)
 *
 * @method array listExtendCampaign($data = [])
 * @method \Generator eachExtendCampaign($condition = [], $batchSize = 100)
 * @method \Generator batchExtendCampaign($condition = [], $batchSize = 100)
 * @method array getExtendCampaign($data)
 *
 * @method array listAdGroups($data = [])
 * @method \Generator eachAdGroup($condition = [], $batchSize = 100)
 * @method \Generator batchAdGroup($condition = [], $batchSize = 100)
 * @method array getAdGroup($data)
 * @method array createAdGroup($data)
 * @method array updateAdGroup($data)
 * @method array deleteAdGroup($data)
 *
 * @method array listExtendAdGroup($data = [])
 * @method \Generator eachExtendAdGroup($condition = [], $batchSize = 100)
 * @method \Generator batchExtendAdGroup($condition = [], $batchSize = 100)
 * @method array getExtendAdGroup($data)
 *
 * @method array listAds($data = [])
 * @method \Generator eachAd($condition = [], $batchSize = 100)
 * @method \Generator batchAd($condition = [], $batchSize = 100)
 * @method array getAd($data)
 * @method array createAd($data)
 * @method array updateAd($data)
 * @method array deleteAd($data)
 *
 * @method array listExtendAd($data = [])
 * @method \Generator eachExtendAd($condition = [], $batchSize = 100)
 * @method \Generator batchExtendAd($condition = [], $batchSize = 100)
 * @method array getExtendAd($data)
 *
 * @method array listKeywords($data = [])
 * @method \Generator eachKeyword($condition = [], $batchSize = 100)
 * @method \Generator batchKeyword($condition = [], $batchSize = 100)
 * @method array getKeyword($data)
 * @method array createKeyword($data)
 * @method array updateKeyword($data)
 * @method array deleteKeyword($data)
 *
 * @method array listExtendKeyword($data = [])
 * @method \Generator eachExtendKeyword($condition = [], $batchSize = 100)
 * @method \Generator batchExtendKeyword($condition = [], $batchSize = 100)
 * @method array getExtendKeyword($data)
 *
 * @method array listNegativeKeywords($data = [])
 * @method \Generator eachNegativeKeyword($condition = [], $batchSize = 100)
 * @method \Generator batchNegativeKeyword($condition = [], $batchSize = 100)
 * @method array getNegativeKeyword($data)
 * @method array createNegativeKeyword($data)
 * @method array updateNegativeKeyword($data)
 * @method array deleteNegativeKeyword($data)
 *
 * @method array listExtendNegativeKeyword($data = [])
 * @method \Generator eachExtendNegativeKeyword($condition = [], $batchSize = 100)
 * @method \Generator batchExtendNegativeKeyword($condition = [], $batchSize = 100)
 * @method array getExtendNegativeKeyword($data)
 *
 * @method array listCampaignNegativeKeywords($data = [])
 * @method \Generator eachCampaignNegativeKeyword($condition = [], $batchSize = 100)
 * @method \Generator batchCampaignNegativeKeyword($condition = [], $batchSize = 100)
 * @method array getCampaignNegativeKeyword($data)
 * @method array createCampaignNegativeKeyword($data)
 * @method array updateCampaignNegativeKeyword($data)
 * @method array deleteCampaignNegativeKeyword($data)
 *
 * @method array listExtendCampaignNegativeKeyword($data = [])
 * @method \Generator eachExtendCampaignNegativeKeyword($condition = [], $batchSize = 100)
 * @method \Generator batchExtendCampaignNegativeKeyword($condition = [], $batchSize = 100)
 * @method array getExtendCampaignNegativeKeyword($data)
 *
 * @method array listTargets($data = [])
 * @method \Generator eachTarget($condition = [], $batchSize = 100)
 * @method \Generator batchTarget($condition = [], $batchSize = 100)
 * @method array getTarget($data)
 * @method array createTarget($data)
 * @method array updateTarget($data)
 * @method array deleteTarget($data)
 *
 * @method array createProductReport($data = [])
 * @method array createBrandReport($data = [])
 * @method array createDisplayReport($data = [])
 * @method array getReport($data)
 * @method array downloadReport($data)
 *
 * @method array createProductSnapshot($data)
 * @method array createBrandSnapshot($data)
 * @method array createDisplaySnapshot($data)
 * @method array getProductSnapshot($data)
 * @method array getBrandSnapshot($data)
 * @method array getDisplaySnapshot($data)
 * @method array downloadProductSnapshot($data)
 * @method array downloadBrandSnapshot($data)
 * @method array downloadDisplaySnapshot($data)
 *
 * @method array listExtendTarget($data = [])
 * @method \Generator eachExtendTarget($condition = [], $batchSize = 100)
 * @method \Generator batchExtendTarget($condition = [], $batchSize = 100)
 * @method array getExtendTarget($data)
 * @method array getGroupSuggestedKeywords($data)
 * @method array getExtendGroupSuggestedKeywords($data)
 * @method array getAsinSuggestedKeywords($data)
 * @method array createAsinReport($data)
 *
 * @package lujie\amazon\advertising
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AmazonAdvertisingClient extends OAuth2
{
    use RestApiTrait;

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
     * @var array
     */
    public $resources = [
        'Profiles' => '/v2/profiles',
        'Campaign' => '/v2/sp/campaigns',
        'AdGroup' => '/v2/sp/adGroups',
        'Ad' => '/v2/sp/productAds',
        'Keyword' => '/v2/sp/keywords',
        'NegativeKeyword' => '/v2/sp/negativeKeywords',
        'CampaignNegativeKeyword' => '/v2/sp/campaignNegativeKeywords',
        'Target' => '/v2/sp/targets',
    ];

    /**
     * @var string
     */
    public $version = 'v2';

    /**
     * @var array
     */
    public $extraActions = [
        'Target' => [
            'createProductRecommendations' => ['POST', 'productRecommendations'],
            'getCategories' => ['POST', 'categories'],
            'getCategoriesRefinements' => ['POST', 'categories/refinements'],
            'getBrands' => ['POST', 'brands'],
        ],
    ];

    /**
     * @var array
     */
    public $extraMethods = [
        'createProductReport' => ['POST', '/v2/sp/{recordType}/report', true],
        'createBrandReport' => ['POST', '/v2/hsa/{recordType}/report', true],
        'createDisplayReport' => ['POST', '/sd/{recordType}/report', true],
        'getReport' => ['GET', '/v2/reports/{id}', true],
        'downloadReport' => ['GET', '/v2/reports/{id}/download', true],

        'createProductSnapshot' => ['POST', '/v2/sp/{recordType}/snapshot', true],
        'createBrandSnapshot' => ['POST', '/v2/hsa/{recordType}/snapshot', true],
        'createDisplaySnapshot' => ['POST', '/sd/{recordType}/snapshot', true],
        'getProductSnapshot' => ['GET', '/v2/sp/snapshots/{id}', true],
        'getBrandSnapshot' => ['GET', '/v2/hsa/snapshots/{id}', true],
        'getDisplaySnapshot' => ['GET', '/sd/snapshots/{id}', true],
        'downloadProductSnapshot' => ['GET', '/v2/sp/snapshots/{id}/download', true],
        'downloadBrandSnapshot' => ['GET', '/v2/hsa/snapshots/{id}/download', true],
        'downloadDisplaySnapshot' => ['GET', '/sd/snapshots/{id}/download', true],

        'getGroupSuggestedKeywords' => ['GET', '/v2/sp/adGroups/{adGroupId}/suggested/keywords'],
        'getExtendGroupSuggestedKeywords' => ['GET', '/v2/sp/adGroups/{adGroupId}/suggested/keywords/extended'],
        'getAsinSuggestedKeywords' => ['GET', '/v2/sp/asins/{asinValue}/suggested/keywords'],

        'createAsinReport' => ['POST', '/v2/asins/report'],

        'listExtend' => ['GET', 'extended'],
        'getExtend' => ['GET', 'extended/{id}'],
    ];

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->initRest();
    }

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
     * @return array
     * @inheritdoc
     */
    protected function initUserAttributes(): array
    {
        return [];
    }

    /**
     * @param array $tokenConfig
     * @return OAuthToken
     * @inheritdoc
     */
    protected function createToken(array $tokenConfig = []): OAuthToken
    {
        $tokenConfig['tokenSecretParamKey'] = 'refresh_token';
        return parent::createToken($tokenConfig);
    }

    /**
     * @param Request $request
     * @param OAuthToken $accessToken
     * @inheritdoc
     */
    public function applyAccessTokenToRequest($request, $accessToken): void
    {
        $request->addHeaders([
            'Amazon-Advertising-API-ClientId' => $this->clientId,
            'Authorization' => 'Bearer ' . $accessToken->getToken(),
        ]);
        if ($this->profileId) {
            $request->addHeaders([
                'Amazon-Advertising-API-Scope' => $this->profileId,
            ]);
        }
    }

    /**
     * @param Request $request
     * @return array|mixed|null
     * @throws InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    protected function sendRequest($request)
    {
        $response = HttpClientHelper::sendRequest($request, ['307']);
        if ($response->getStatusCode() === '307') {
            $location = $response->getHeaders()->get('Location');
            $newRequest = $this->createRequest()->setUrl($location);
            $content = HttpClientHelper::sendRequest($newRequest)->getContent();
            return [$content];
        }
        return $response->getData();
    }
}

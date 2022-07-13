<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\amazon\advertising;

use Iterator;
use lujie\extend\authclient\RestClientTrait;
use yii\authclient\BaseClient;
use yii\authclient\InvalidResponseException;
use yii\authclient\OAuth2;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\httpclient\Request;

/**
 * Class AmazonAdvertisingClient
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
 * @method array listExtendTarget($data = [])
 * @method \Generator eachExtendTarget($condition = [], $batchSize = 100)
 * @method \Generator batchExtendTarget($condition = [], $batchSize = 100)
 * @method array getExtendTarget($data)
 * @method array getGroupSuggestedKeywords($data)
 * @method array getExtendGroupSuggestedKeywords($data)
 * @method array getAsinSuggestedKeywords($data)
 * @method array createSnapshot($data)
 * @method array getSnapshot($data)
 * @method array createReport($data)
 * @method array createAsinReport($data)
 * @method array getReport($data)
 * @method array downloadReport($data)
 *
 * @package lujie\amazon\advertising
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AmazonAdvertisingClient extends OAuth2
{
    use RestClientTrait;

    /**
     * @var string
     */
    public $tokenUrl = AmazonAdvertisingConst::TOKEN_URL_EU;

    /**
     * @var string
     */
    public $scope = AmazonAdvertisingConst::SCOPE_CPC;

    /**
     * @var array
     */
    public $resources = [
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
        'spTarget' => [
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
        'getGroupSuggestedKeywords' => ['GET', '/v2/sp/adGroups/{adGroupId}/suggested/keywords'],
        'getExtendGroupSuggestedKeywords' => ['GET', '/v2/sp/adGroups/{adGroupId}/suggested/keywords/extended'],
        'getAsinSuggestedKeywords' => ['GET', '/v2/sp/asins/{asinValue}/suggested/keywords'],

        'createSnapshot' => ['GET', '/v2/sp/{recordType}/snapshot'],
        'getSnapshot' => ['GET', '/v2/sp/snapshots/{snapshotId}'],

        'createReport' => ['POST', '/v2/sp/{recordType}/report'],
        'createAsinReport' => ['POST', '/v2/asins/report'],
        'getReport' => ['GET', '/v2/reports/{id}'],
        'downloadReport' => ['GET', '/v2/reports/{id}/download'],
    ];

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        $this->actions = array_merge($this->actions, [
            'listExtend' => ['GET', 'extended'],
            'getExtend' => ['GET', 'extended/{id}'],
        ]);
        parent::init();
        $this->initRest();
    }

    /**
     * @return array
     * @inheritdoc
     */
    protected function initUserAttributes(): array
    {
        return $this->api('/v2/profiles');
    }

    /**
     * @param string $resource
     * @param array $condition
     * @param int $batchSize
     * @return Iterator
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function batch(string $resource, array $condition = [], int $batchSize = 100): Iterator
    {
        throw new NotSupportedException('AmazonAdvertisingClient method `batch` not supported');
    }
}

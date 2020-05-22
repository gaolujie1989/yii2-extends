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
 * @method array listSpCampaigns($data = [])
 * @method \Generator eachSpCampaign($condition = [], $batchSize = 100)
 * @method \Generator batchSpCampaign($condition = [], $batchSize = 100)
 * @method array getSpCampaign($data)
 * @method array createSpCampaign($data)
 * @method array updateSpCampaign($data)
 * @method array deleteSpCampaign($data)
 *
 * @method array listExtendSpCampaign($data = [])
 * @method \Generator eachExtendSpCampaign($condition = [], $batchSize = 100)
 * @method \Generator batchExtendSpCampaign($condition = [], $batchSize = 100)
 * @method array getExtendSpCampaign($data)
 *
 * @method array listSpAdGroups($data = [])
 * @method \Generator eachSpAdGroup($condition = [], $batchSize = 100)
 * @method \Generator batchSpAdGroup($condition = [], $batchSize = 100)
 * @method array getSpAdGroup($data)
 * @method array createSpAdGroup($data)
 * @method array updateSpAdGroup($data)
 * @method array deleteSpAdGroup($data)
 *
 * @method array listExtendSpAdGroup($data = [])
 * @method \Generator eachExtendSpAdGroup($condition = [], $batchSize = 100)
 * @method \Generator batchExtendSpAdGroup($condition = [], $batchSize = 100)
 * @method array getExtendSpAdGroup($data)
 *
 * @method array listSpAds($data = [])
 * @method \Generator eachSpAd($condition = [], $batchSize = 100)
 * @method \Generator batchSpAd($condition = [], $batchSize = 100)
 * @method array getSpAd($data)
 * @method array createSpAd($data)
 * @method array updateSpAd($data)
 * @method array deleteSpAd($data)
 *
 * @method array listExtendSpAd($data = [])
 * @method \Generator eachExtendSpAd($condition = [], $batchSize = 100)
 * @method \Generator batchExtendSpAd($condition = [], $batchSize = 100)
 * @method array getExtendSpAd($data)
 *
 * @method array listSpKeywords($data = [])
 * @method \Generator eachSpKeyword($condition = [], $batchSize = 100)
 * @method \Generator batchSpKeyword($condition = [], $batchSize = 100)
 * @method array getSpKeyword($data)
 * @method array createSpKeyword($data)
 * @method array updateSpKeyword($data)
 * @method array deleteSpKeyword($data)
 *
 * @method array listExtendSpKeyword($data = [])
 * @method \Generator eachExtendSpKeyword($condition = [], $batchSize = 100)
 * @method \Generator batchExtendSpKeyword($condition = [], $batchSize = 100)
 * @method array getExtendSpKeyword($data)
 *
 * @method array listSpNegativeKeywords($data = [])
 * @method \Generator eachSpNegativeKeyword($condition = [], $batchSize = 100)
 * @method \Generator batchSpNegativeKeyword($condition = [], $batchSize = 100)
 * @method array getSpNegativeKeyword($data)
 * @method array createSpNegativeKeyword($data)
 * @method array updateSpNegativeKeyword($data)
 * @method array deleteSpNegativeKeyword($data)
 *
 * @method array listExtendSpNegativeKeyword($data = [])
 * @method \Generator eachExtendSpNegativeKeyword($condition = [], $batchSize = 100)
 * @method \Generator batchExtendSpNegativeKeyword($condition = [], $batchSize = 100)
 * @method array getExtendSpNegativeKeyword($data)
 *
 * @method array listSpCampaignNegativeKeywords($data = [])
 * @method \Generator eachSpCampaignNegativeKeyword($condition = [], $batchSize = 100)
 * @method \Generator batchSpCampaignNegativeKeyword($condition = [], $batchSize = 100)
 * @method array getSpCampaignNegativeKeyword($data)
 * @method array createSpCampaignNegativeKeyword($data)
 * @method array updateSpCampaignNegativeKeyword($data)
 * @method array deleteSpCampaignNegativeKeyword($data)
 *
 * @method array listExtendSpCampaignNegativeKeyword($data = [])
 * @method \Generator eachExtendSpCampaignNegativeKeyword($condition = [], $batchSize = 100)
 * @method \Generator batchExtendSpCampaignNegativeKeyword($condition = [], $batchSize = 100)
 * @method array getExtendSpCampaignNegativeKeyword($data)
 *
 * @method array listSpTargets($data = [])
 * @method \Generator eachSpTarget($condition = [], $batchSize = 100)
 * @method \Generator batchSpTarget($condition = [], $batchSize = 100)
 * @method array getSpTarget($data)
 * @method array createSpTarget($data)
 * @method array updateSpTarget($data)
 * @method array deleteSpTarget($data)
 *
 * @method array listExtendSpTarget($data = [])
 * @method \Generator eachExtendSpTarget($condition = [], $batchSize = 100)
 * @method \Generator batchExtendSpTarget($condition = [], $batchSize = 100)
 * @method array getExtendSpTarget($data)
 * @method array getSpGroupSuggestedKeywords($data)
 * @method array getExtendSpGroupSuggestedKeywords($data)
 * @method array getAsinSuggestedKeywords($data)
 * @method array createSpSnapshot($data)
 * @method array getSpSnapshot($data)
 * @method array createSpReport($data)
 * @method array createAsinReport($data)
 * @method array getSpReport($data)
 * @method array downloadSpReport($data)
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
        'SpCampaign' => '/v2/sp/campaigns',
        'SpAdGroup' => '/v2/sp/adGroups',
        'SpAd' => '/v2/sp/productAds',
        'SpKeyword' => '/v2/sp/keywords',
        'SpNegativeKeyword' => '/v2/sp/negativeKeywords',
        'SpCampaignNegativeKeyword' => '/v2/sp/campaignNegativeKeywords',
        'SpTarget' => '/v2/sp/targets',
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
        'getSpGroupSuggestedKeywords' => ['GET', '/v2/sp/adGroups/{adGroupId}/suggested/keywords'],
        'getExtendSpGroupSuggestedKeywords' => ['GET', '/v2/sp/adGroups/{adGroupId}/suggested/keywords/extended'],
        'getAsinSuggestedKeywords' => ['GET', '/v2/sp/asins/{asinValue}/suggested/keywords'],

        'createSpSnapshot' => ['GET', '/v2/sp/{recordType}/snapshot'],
        'getSpSnapshot' => ['GET', '/v2/sp/snapshots/{snapshotId}'],

        'createSpReport' => ['POST', '/v2/sp/{recordType}/report'],
        'createAsinReport' => ['POST', '/v2/asins/report'],
        'getSpReport' => ['GET', '/v2/reports/{id}'],
        'downloadSpReport' => ['GET', '/v2/reports/{id}/download'],
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

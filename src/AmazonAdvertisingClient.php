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
 *
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
        'spCampaign' => '/v2/sp/campaigns',
        'spAdGroup' => '/v2/sp/adGroups',
        'spAd' => '/v2/sp/productAds',
        'spKeyword' => '/v2/sp/keywords',
        'spNegativeKeyword' => '/v2/sp/negativeKeywords',
        'spCampaignNegativeKeyword' => '/v2/sp/campaignNegativeKeywords',
        'spTarget' => '/v2/sp/targets',
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

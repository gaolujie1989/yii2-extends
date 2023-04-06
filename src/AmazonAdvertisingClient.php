<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\amazon\advertising;

use lujie\extend\authclient\OAuthExtendTrait;
use lujie\extend\authclient\RestApiTrait;
use lujie\extend\authclient\RestOAuth2;
use lujie\extend\helpers\HttpClientHelper;
use yii\authclient\InvalidResponseException;
use yii\authclient\OAuth2;
use yii\authclient\OAuthToken;
use yii\httpclient\Request;

/**
 * Class AmazonAdvertisingClient
 *
 * @method array listV2Profiles($data = [])
 * @method \Generator eachV2Profiles($condition = [], $batchSize = 100)
 * @method \Generator batchV2Profiles($condition = [], $batchSize = 100)
 * @method array getV2Profile($data)
 *
 * @method array listV2Portfolios($data = [])
 * @method \Generator eachV2Portfolios($condition = [], $batchSize = 100)
 * @method \Generator batchV2Portfolios($condition = [], $batchSize = 100)
 * @method array getV2Portfolio($data)
 * @method array createV2Portfolio($data)
 * @method array updateV2Portfolio($data)
 *
 * @method array createV2BrandReport($data)
 * @method array createV2DisplayReport($data)
 * @method array getV2Report($data)
 * @method array downloadV2Report($data)
 * @method array createV3Report($data)
 * @method array getV3Report($data)
 *
 * @method array createV2ProductSnapshot($data)
 * @method array createV2BrandSnapshot($data)
 * @method array getV2ProductSnapshot($data)
 * @method array getV2BrandSnapshot($data)
 * @method array downloadV2ProductSnapshot($data)
 * @method array downloadV2BrandSnapshot($data)
 *
 * @method array createV3DisplaySnapshot($data)
 * @method array getV3DisplaySnapshot($data)
 * @method array downloadV3DisplaySnapshot($data)
 *
 * @package lujie\amazon\advertising
 * @author Lujie Zhou <gao_lujie@live.cn>
 * @document https://advertising.amazon.com/API/docs/en-us/index
 */
class AmazonAdvertisingClient extends RestOAuth2
{
    use AmazonAdvertisingExtendTrait;

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
        'V2Profile' => '/v2/profiles',
        'V2Portfolio' => '/v2/portfolios',
    ];

    /**
     * @var array
     */
    public $extraActions = [
        'V2Profile' => [
            'create' => false,
            'update' => false,
            'delete' => false,
        ],
        'V2Portfolio' => [
            'delete' => false,
        ],
    ];

    /**
     * @var array
     */
    public $extraMethods = [
        'createV2BrandReport' => ['POST', '/v2/hsa/{recordType}/report', true],
        'createV2DisplayReport' => ['POST', '/sd/{recordType}/report', true],
        'getV2Report' => ['GET', '/v2/reports/{id}', true],
        'downloadV2Report' => ['GET', '/v2/reports/{id}/download', true],

        'createV3Report' => ['POST', '/reporting/reports'],
        'getV3Report' => ['POST', '/reporting/reports/{id}', true],

        'createV2ProductSnapshot' => ['POST', '/v2/sp/{recordType}/snapshot', true],
        'createV2BrandSnapshot' => ['POST', '/v2/hsa/{recordType}/snapshot', true],
        'getV2ProductSnapshot' => ['GET', '/v2/sp/snapshots/{id}', true],
        'getV2BrandSnapshot' => ['GET', '/v2/hsa/snapshots/{id}', true],
        'downloadV2ProductSnapshot' => ['GET', '/v2/sp/snapshots/{id}/download', true],
        'downloadV2BrandSnapshot' => ['GET', '/v2/hsa/snapshots/{id}/download', true],

        'createV3DisplaySnapshot' => ['POST', '/sd/{recordType}/snapshot', true],
        'getV3DisplaySnapshot' => ['GET', '/sd/snapshots/{id}', true],
        'downloadV3DisplaySnapshot' => ['GET', '/sd/snapshots/{id}/download', true],
    ];

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
        $request->addHeaders([
            'Amazon-Advertising-API-ClientId' => $this->clientId,
            'Authorization' => 'Bearer ' . $accessToken->getToken(),
        ]);
        if ($this->profileId) {
            $request->addHeaders([
                'Amazon-Advertising-API-Scope' => $this->profileId,
            ]);
        }
        if (strpos($request->getUrl(), 'reporting/reports') !== false) {
            $request->addHeaders([
                'Content-Type' => 'application/vnd.createasyncreportrequest.v3+json'
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

<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\amazon\advertising;

use DoubleBreak\Spapi\Client;
use DoubleBreak\Spapi\Credentials;
use lujie\extend\authclient\BatchApiTrait;
use lujie\extend\authclient\RestOAuth2;
use lujie\extend\helpers\HttpClientHelper;
use yii\authclient\InvalidResponseException;
use yii\authclient\OAuthToken;
use yii\base\InvalidCallException;
use yii\httpclient\Request;

/**
 * Class BaseAmazonAdvertisingClient
 * @package lujie\amazon\sp
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BaseAmazonAdvertisingClient extends RestOAuth2
{
    use BatchApiTrait;

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

    #region auth

    /**
     * @param string $profileId
     * @return $this
     * @inheritdoc
     */
    public function setProfileId(string $profileId): self
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
        $headerCollection = $request->getHeaders();
        foreach ($headers as $key => $header) {
            $headerCollection->set($key, $header);
        }
    }

    #endregion
}

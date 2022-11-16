<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\amazon\sp;

use lujie\extend\authclient\OAuthExtendTrait;
use yii\authclient\OAuth2;

/**
 * Class AmazonSellerClient
 * @package kiwi\amazon\seller
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AmazonSellerClient extends OAuth2
{
    use OAuthExtendTrait;

    /**
     * @var string
     */
    public $apiBaseUrl = AmazonSPConst::API_URL_EU;

    /**
     * @var string
     */
    public $authUrl = AmazonSPConst::AUTH_URL_EU;

    /**
     * @var string
     */
    public $tokenUrl = AmazonSPConst::TOKEN_URL;

    /**
     * @var string
     */
    public $appId;

    /**
     * @var string
     */
    public $appVersion = 'beta';

    /**
     * @param array $params
     * @return string
     * @inheritdoc
     */
    public function buildAuthUrl(array $params = []): string
    {
        $params['application_id'] = $this->appId;
        if ($this->appVersion) {
            $params['version'] = $this->appVersion;
        }
        return parent::buildAuthUrl($params);
    }

//    /**
//     * @param Request $request
//     * @param OAuthToken $accessToken
//     * @inheritdoc
//     */
//    public function applyAccessTokenToRequest($request, $accessToken): void
//    {
//        $request->addHeaders([
//            'x-amz-access-token' => $accessToken->getToken(),
//            'x-amz-date' => gmdate('Ymd\THis\Z'),
//            'user-agent' => strtr('{appId}/{appVersion} (Language=PHP/{phpVersion}; Platform={platform})', [
//                '{appId}' => 'AmazonSellerClient',
//                '{appVersion}' => 1.0,
//                '{phpVersion}' => PHP_VERSION,
//                '{platform}' => 'Ubuntu/1804',
//            ])
//        ]);
//    }
}
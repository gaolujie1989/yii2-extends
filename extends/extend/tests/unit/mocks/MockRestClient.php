<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\mocks;

use Iterator;
use lujie\extend\authclient\RestClientTrait;
use yii\authclient\BaseOAuth;
use yii\authclient\OAuthToken;
use yii\base\NotSupportedException;

/**
 * Class MockRestClient
 * @package lujie\extend\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockRestClient extends BaseOAuth
{
    use RestClientTrait;

    public $resources = [];

    public $extraActions = [];

    public $extraMethods = [];

    public $suffix = '';

    public static $batchData = [];

    /**
     * @var string[]
     */
    public $httpClientOptions = [
        'transport' => MockTransport::class,
    ];

    protected function initUserAttributes()
    {
    }

    /**
     * @return OAuthToken
     * @inheritdoc
     */
    public function getAccessToken(): OAuthToken
    {
        return new OAuthToken([
            'tokenParamKey' => 'access_token',
            'params' => [
                'access_token' => 'mocked_token',
                'expires_in' => '86400',
            ]
        ]);
    }

    public function batch(string $resource, array $condition = [], int $batchSize = 100): Iterator
    {
        yield from self::$batchData[$resource];
    }

    /**
     * @param OAuthToken $token
     * @return void|OAuthToken
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function refreshAccessToken(OAuthToken $token)
    {
        throw new NotSupportedException('MockRestClient method `refreshAccessToken` not supported');
    }

    /**
     * @param \yii\httpclient\Request $request
     * @param OAuthToken $accessToken
     * @inheritdoc
     */
    public function applyAccessTokenToRequest($request, $accessToken)
    {
        $request->addData(['access_token' => $accessToken->getToken()]);
    }
}

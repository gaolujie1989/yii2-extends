<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\mocks;

use Iterator;
use lujie\extend\authclient\RestOAuth2Client;
use yii\authclient\OAuthToken;

/**
 * Class MockCookieClient
 * @package lujie\extend\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockOAuth2Client extends RestOAuth2Client
{
    public $httpClientOptions = [
        'transport' => MockTransport::class
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
        // TODO: Implement batch() method.
    }
}

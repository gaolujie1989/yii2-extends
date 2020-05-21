<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\tests\unit\mocks;

use Iterator;
use yii\authclient\BaseOAuth;
use yii\authclient\OAuthToken;

/**
 * Class MockApiClient
 * @package lujie\data\recording\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockApiClient extends BaseOAuth
{
    public static $responses = [];

    protected function initUserAttributes()
    {
    }

    public function batch(string $resource, array $condition = [], int $batchSize = 100): Iterator
    {
        // TODO: Implement batch() method.
    }

    public function api($apiSubUrl, $method = 'GET', $data = [], $headers = [])
    {
        return array_shift(static::$responses);
    }

    public function refreshAccessToken(OAuthToken $token)
    {
    }

    public function applyAccessTokenToRequest($request, $accessToken)
    {
    }
}

<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\plentyMarkets\tests\unit\mocks;

use lujie\plentyMarkets\PlentyMarketsRestClient;

/**
 * Class MockPlentyMarketsRestClient
 * @package lujie\plentyMarkets\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockPlentyMarketsRestClient extends PlentyMarketsRestClient
{
    public static $RESPONSE_DATA;

    /**
     * @param string $apiSubUrl
     * @param string $method
     * @param array $data
     * @param array $headers
     * @return array|mixed
     * @inheritdoc
     */
    public function api($apiSubUrl, $method = 'GET', $data = [], $headers = [])
    {
        return array_shift(static::$RESPONSE_DATA);
    }
}
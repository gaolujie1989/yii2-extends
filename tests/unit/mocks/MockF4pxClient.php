<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tests\unit\mocks;


use lujie\extend\authclient\JsonRpcResponse;
use lujie\fulfillment\f4px\F4pxClient;

/**
 * Class MockF4pxClient
 * @package lujie\fulfillment\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockF4pxClient extends F4pxClient
{
    public static $RESPONSE_DATA = [];

    /**
     * @param string $method
     * @param array $data
     * @return JsonRpcResponse
     * @throws \yii\authclient\InvalidResponseException
     * @throws \yii\base\NotSupportedException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function call(string $method, $data = []): JsonRpcResponse
    {
        return new JsonRpcResponse([
            'success' => 1,
            'data' => array_shift(static::$RESPONSE_DATA),
        ]);
    }
}
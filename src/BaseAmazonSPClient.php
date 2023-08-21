<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\amazon\sp;

use DoubleBreak\Spapi\Client;
use lujie\extend\authclient\BatchApiTrait;

/**
 * Class BaseAmazonSPClient
 * @package lujie\amazon\sp
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BaseAmazonSPClient extends Client
{
    use BatchApiTrait;

    /**
     * @param string $apiSubUrl
     * @param string $method
     * @param array|string $data
     * @param array $headers
     * @return mixed
     * @inheritdoc
     */
    public function api(string|array $apiSubUrl, string $method = 'GET', array|string $data = [], array $headers = []): mixed
    {
        $requestOptions = [
            'method' => $method,
            'headers' => $headers,
        ];
        if ($data) {
            if ($method === 'GET') {
                $requestOptions['query'] = $data;
            } else {
                $requestOptions['json'] = $data;
            }
        }
        return $this->send($apiSubUrl, $requestOptions);
    }

    /**
     * @param array $responseData
     * @param array $condition
     * @return array|null
     * @inheritdoc
     */
    protected function getNextPageCondition(array $responseData, array $condition): ?array
    {
        return array_filter($responseData['payload']['pagination'] ?? []);
    }

    /**
     * @param array $responseData
     * @param string $method
     * @return array
     * @inheritdoc
     */
    protected function getPageData(array $responseData, string $method): array
    {
        $payload = $responseData['payload'] ?? [];
        unset($payload['pagination']);
        return $payload ? reset($payload) : [];
    }
}

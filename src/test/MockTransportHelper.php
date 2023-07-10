<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\test;

use lujie\extend\httpclient\Response;
use yii\authclient\BaseClient;
use yii\base\InvalidArgumentException;
use yii\httpclient\MockTransport;

class MockTransportHelper
{
    /**
     * @param BaseClient $baseClient
     * @param array $responseFiles
     * @return MockTransport
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public static function appendMockResponseFiles(BaseClient $baseClient, array $responseFiles): MockTransport
    {
        /** @var MockTransport $mockTransport */
        $mockTransport = $baseClient->getHttpClient()->getTransport();
        foreach ($responseFiles as $responseFile) {
            $mockResponse = new Response([
                'headers' => [
                    'http-code' => 200,
                    'content-type' => 'json'
                ],
                'content' => file_get_contents($responseFile),
            ]);
            if (str_ends_with($responseFile, '.gz')) {
                $mockResponse->headers['content-encoding'] = 'gzip';
            }
            $mockTransport->appendResponse($mockResponse);
        }
        return $mockTransport;
    }

    /**
     * @param BaseClient $baseClient
     * @param array $responses
     * @return MockTransport
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public static function appendMockResponses(BaseClient $baseClient, array $responses): MockTransport
    {
        /** @var MockTransport $mockTransport */
        $mockTransport = $baseClient->getHttpClient()->getTransport();
        foreach ($responses as $response) {
            if ($response instanceof Response) {
                $mockResponse = $response;
            } else if (is_string($response)) {
                $mockResponse = new Response([
                    'headers' => [
                        'http-code' => 200,
                        'content-type' => 'json'
                    ],
                    'content' => $response
                ]);
            } else if (is_array($response)) {
                $mockResponse = new Response(array_merge([
                    'headers' => [
                        'http-code' => 200,
                        'content-type' => 'json'
                    ],
                ], $response));
            } else {
                throw new InvalidArgumentException('Invalid response type');
            }
            $mockTransport->appendResponse($mockResponse);
        }
        return $mockTransport;
    }
}

<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\amazon\sp;

use DoubleBreak\Spapi\Client;
use DoubleBreak\Spapi\Credentials;
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
     * @var Credentials
     */
    protected $credentialsInstance;

    /**
     * @var array[]
     */
    public $rdtOperations = [
        '/vendor/directFulfillment/orders/v1/purchaseOrders' => [
            'dataElements' => ['shipToParty']
        ],
        '/vendor/directFulfillment/orders/2021-12-28/purchaseOrders' => [
            'dataElements' => ['shipToParty']
        ],
        '/vendor/directFulfillment/shipping/v1/shippingLabels' => [
            'dataElements' => ['labelData']
        ],
        '/vendor/directFulfillment/shipping/v1/packingSlips' => [
            'dataElements' => ['content']
        ],
        '/vendor/directFulfillment/shipping/v1/customerInvoices' => [
            'dataElements' => ['content']
        ],
        '/vendor/directFulfillment/shipping/2021-12-28/shippingLabels' => [
            'dataElements' => ['labelData']
        ],
        '/vendor/directFulfillment/shipping/2021-12-28/packingSlips' => [
            'dataElements' => ['content']
        ],
        '/vendor/directFulfillment/shipping/2021-12-28/customerInvoices' => [
            'dataElements' => ['content']
        ],
    ];

    /**
     * @param Credentials $credentials
     * @param array $config
     * @throws \Exception
     */
    public function __construct(Credentials $credentials, array $config = [])
    {
        $this->credentialsInstance = $credentials;
        parent::__construct($credentials->getCredentials(), $config);
    }

    /**
     * @param string|array $apiSubUrl
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
        $uri = is_array($apiSubUrl) ? $apiSubUrl[0] : $apiSubUrl;
        if (is_array($apiSubUrl)) {
            $requestOptions['query'] = $apiSubUrl;
            unset($requestOptions['query'][0]);
        }
        if ($data) {
            $requestOptions['json'] = $data;
        }

        if ($restrictedResource = $this->getRestrictedResource($uri, $method)) {
            $this->credentials = $this->credentialsInstance->getRdtCredentials($restrictedResource);
        } else {
            $this->credentials = $this->credentialsInstance->getCredentials();
        }
        return $this->send($uri, $requestOptions);
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

    /**
     * @param string $uriPath
     * @return array|null
     * @inheritdoc
     */
    protected function getRestrictedResource(string $uriPath, string $method): ?array
    {
        foreach ($this->rdtOperations as $urlPrefix => $restrictedResource) {
            if (str_starts_with($urlPrefix, $urlPrefix)) {
                $restrictedResource['method'] = $method;
                $restrictedResource['path'] = $uriPath;
                return $restrictedResource;
            }
        }
        return null;
    }
}

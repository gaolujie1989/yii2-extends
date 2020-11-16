<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\authclient;

use lujie\extend\helpers\HttpClientHelper;
use yii\authclient\BaseClient;
use yii\base\InvalidCallException;
use yii\base\NotSupportedException;
use yii\httpclient\Client;
use yii\httpclient\CurlTransport;
use yii\httpclient\Request;
use yii\httpclient\RequestEvent;
use yii\httpclient\Response;

/**
 * Class BaseJsonRpcClient
 * @package lujie\extend\authclient
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseJsonRpcClient extends BaseClient
{
    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $appKey;

    /**
     * @var string
     */
    public $appSecret;

    /**
     * @var array
     */
    public $methods = [
        'getSkuList' => [
            'method' => 'fu.wms.sku.getlist',
        ]
    ];

    /**
     * @var array
     */
    public $httpClientOptions = [
        'transport' => CurlTransport::class
    ];

    /**
     * @return string
     * @inheritdoc
     */
    public function getId(): string
    {
        return $this->appKey;
    }

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if ($this->httpClientOptions) {
            $this->setHttpClient($this->httpClientOptions);
        }
    }

    /**
     * @return Request
     * @inheritdoc
     */
    public function createRpcRequest(): Request
    {
        $request = $this->createRequest()
            ->setFormat(Client::FORMAT_JSON)
            ->setMethod('POST')
            ->setUrl($this->url);
        $request->on(Request::EVENT_BEFORE_SEND, [$this, 'beforeRpcRequestSend']);
        return $request;
    }

    /**
     * @param RequestEvent $event
     * @inheritdoc
     */
    public function beforeRpcRequestSend(RequestEvent $event): void
    {
        $this->applySignToRequest($event->request);
    }

    /**
     * @param Request $request
     * @inheritdoc
     */
    public function applySignToRequest(Request $request): void
    {
        $data = array_merge($request->getData(), $this->getCommonData());
        $data['sign'] = $this->getSign($data);
        $request->setData($data);
    }

    /**
     * @return array
     * @inheritdoc
     */
    abstract protected function getCommonData(): array;

    /**
     * @param array $data
     * @return string
     */
    abstract protected function getSign(array $data): string;

    /**
     * @param Response $response
     * @return JsonRpcResponse
     */
    abstract protected function createRpcResponse(Response $response): JsonRpcResponse;

    /**
     * @param string $method
     * @param array $data
     * @return JsonRpcResponse
     * @throws NotSupportedException
     * @throws \yii\authclient\InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function call(string $method, $data = []): JsonRpcResponse
    {
        if (empty($this->methods[$method])) {
            throw new NotSupportedException('Method not supported');
        }
        $data = array_merge($this->methods[$method], $data);
        $request = $this->createRpcRequest()->setData($data);
        $response = HttpClientHelper::sendRequest($request);
        return $this->createRpcResponse($response);
    }

    /**
     * @param string $name
     * @param array $params
     * @return JsonRpcResponse|mixed
     * @throws NotSupportedException
     * @throws \yii\authclient\InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function __call($name, $params)
    {
        if (isset($this->methods[$name])) {
            return $this->call($name, $params[0]);
        }
        return parent::__call($name, $params);
    }
}
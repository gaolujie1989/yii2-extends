<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\authclient;

use lujie\extend\helpers\HttpClientHelper;
use Yii;
use yii\authclient\BaseClient;
use yii\base\NotSupportedException;
use yii\helpers\Json;
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
    use BatchApiTrait;

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
        'transport' => CurlTransport::class,
        'requestConfig' => [
            'format' => 'json'
        ],
    ];

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        //JsonRPC not need to storage token state
        if ($this->httpClientOptions) {
            $this->setHttpClient($this->httpClientOptions);
        }
        if ($this->getId() === $this->getName()) {
            $this->setId($this->getId() . '-' . $this->appKey);
        }
    }

    #region Base Auth

    /**
     * @return Request
     * @inheritdoc
     */
    public function createRpcRequest(): Request
    {
        $request = $this->createRequest()
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
        $commonData = $this->getCommonData();
        $requestData = array_merge($commonData, $request->getData());
        $requestData['sign'] = $this->getSign($requestData);
        $request->setData($requestData);
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

    #endregion

    /**
     * @param string $method
     * @param array $data
     * @return JsonRpcResponse
     * @throws NotSupportedException
     * @throws \yii\authclient\InvalidResponseException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function call(string $method, array $data = []): JsonRpcResponse
    {
        if (empty($this->methods[$method])) {
            throw new NotSupportedException('Method not supported');
        }
        $request = $this->createRpcRequest();
        $data = array_merge($this->methods[$method], $data);
        foreach ($data as $key => $value) {
            if (!is_array($value) && strrpos($value, '@') === 0) {
                $file = substr($value, 1);
                $request->addFile($key, $file);
                unset($data[$key]);
            }
        }
        if ($data) {
            $request->setData($data);
        }
        $response = HttpClientHelper::sendRequest($request);
        return $this->createRpcResponse($response);
    }

    /**
     * @param JsonRpcResponse $response
     * @return array|string|null
     * @throws JsonRpcException
     * @inheritdoc
     */
    public function getResponseData(JsonRpcResponse $response)
    {
        if (!$response->success) {
            throw new JsonRpcException($response, 'JsonRpc error: ' . $response->message . Json::encode($response->errors));
        }
        return $response->data;
    }

    /**
     * @param string $name
     * @param array $params
     * @return array|\Iterator|mixed|null
     * @throws JsonRpcException
     * @throws NotSupportedException
     * @throws \yii\authclient\InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @throws \Exception
     * @inheritdoc
     */
    public function __call($name, $params)
    {
        $param0 = (array)($params[0] ?? []);
        if (isset($this->methods[$name])) {
            $jsonRpcResponse = $this->call($name, $param0);
            return $this->getResponseData($jsonRpcResponse);
        }

        $param1 = $params[1] ?? 100;
        if (strpos($name, 'batch') === 0) {
            return $this->batch(substr($name, 5), $param0, $param1);
        }
        if (strpos($name, 'each') === 0) {
            return $this->each(substr($name, 4), $param0, $param1);
        }

        return parent::__call($name, $params);
    }
}

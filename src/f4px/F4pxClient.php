<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\f4px;

use lujie\extend\authclient\BaseJsonRpcClient;
use lujie\extend\authclient\JsonRpcResponse;
use yii\base\NotSupportedException;
use yii\helpers\Json;
use yii\httpclient\CurlTransport;
use yii\httpclient\Request;
use yii\httpclient\Response;

/**
 * Class F4pxClient
 * @package lujie\fulfillment\f4px
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class F4pxClient extends BaseJsonRpcClient
{
    /**
     * @var string
     */
    public $productionUrl = 'http://open.4px.com/router/api/service';

    /**
     * @var string
     */
    public $sandboxUrl = 'http://open.sandbox.4px.com/router/api/service';

    /**
     * @var string
     */
    public $version = '1.0.0';

    /**
     * @var string
     */
    public $language = 'en';

    /**
     * @var array
     */
    public $httpClientOptions = [
        'transport' => CurlTransport::class,
        'requestConfig' => [
            'format' => 'json'
        ],
        'responseConfig' => [
            'format' => 'json'
        ],
    ];

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
     * @return array|void
     * @throws NotSupportedException
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        throw new NotSupportedException('');
    }

    /**
     * @param Request $request
     * @inheritdoc
     */
    public function applySignToRequest(Request $request): void
    {
        $data = $request->data;
        $signData = $this->getCommonData();
        $signData['method'] = $data['method'];
        unset($data['method']);
        $request->setData($data);

        $signData['body'] = Json::encode($data);
        $signData['sign'] = $this->getSign($signData);
        $request->setUrl(array_merge([$this->url], $signData));
    }

    /**
     * @return array
     * @inheritdoc
     */
    protected function getCommonData(): array
    {
        return [
            'app_key' => $this->appKey,
            'v' => $this->version,
            'timestamp' => time() * 1000,
            'format' => 'json',
            'language' => $this->language,
        ];
    }

    /**
     * @param array $data
     * @return string
     * @inheritdoc
     */
    protected function getSign(array $data): string
    {
        ksort($data);
        $signString = '';
        foreach ($data as $key => $value) {
            if (in_array($key, ['access_token', 'language'], true)) {
                continue;
            }
            $signString .= $key . $value;
        }
        return md5($signString);
    }

    /**
     * @param Response $response
     * @return JsonRpcResponse
     * @inheritdoc
     */
    protected function createRpcResponse(Response $response): JsonRpcResponse
    {
        $data = $response->data;
        $jsonRpcResponse = new JsonRpcResponse();
        $jsonRpcResponse->success = $data['result'] && true;
        $jsonRpcResponse->message = $data['msg'];
        $jsonRpcResponse->data = $data['data'] ?? [];
        $jsonRpcResponse->errors = $data['errors'] ?? [];
        return $jsonRpcResponse;
    }
}
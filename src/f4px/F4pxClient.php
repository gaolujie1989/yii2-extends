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
 *
 * @method JsonRpcResponse getSkuList(array $data)
 * @method JsonRpcResponse editSkuPicture(array $data)
 * @method JsonRpcResponse uploadSkuReport(array $data)
 * @method JsonRpcResponse createSku(array $data)
 * @method JsonRpcResponse getInventory(array $data)
 * @method JsonRpcResponse getInventoryLog(array $data)
 * @method JsonRpcResponse getInventoryDetail(array $data)
 * @method JsonRpcResponse getInboundList(array $data)
 * @method JsonRpcResponse createInbound(array $data)
 * @method JsonRpcResponse cancelInbound(array $data)
 * @method JsonRpcResponse getInboundLabel(array $data)
 * @method JsonRpcResponse uploadInboundInvoiceFile(array $data)
 * @method JsonRpcResponse getOutboundList(array $data)
 * @method JsonRpcResponse createOutbound(array $data)
 * @method JsonRpcResponse cancelOutbound(array $data)
 * @method JsonRpcResponse getShipmentLabel(array $data)
 * @method JsonRpcResponse createShipment(array $data)
 * @method JsonRpcResponse cancelShipment(array $data)
 *
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
    public $methods = [
        'getSkuList' => [
            'method' => 'fu.wms.sku.getlist',
        ],
        'editSkuPicture' => [
            'method' => 'fu.wms.sku.editpicture',
        ],
        'uploadSkuReport' => [
            'method' => 'fu.wms.sku.uploadsreport',
        ],
        'createSku' => [
            'method' => 'fu.wms.sku.create',
        ],
        'getInventory' => [
            'method' => 'fu.wms.inventory.get',
        ],
        'getInventoryLog' => [
            'method' => 'fu.wms.inventory.getlog',
        ],
        'getInventoryDetail' => [
            'method' => 'fu.wms.inventory.getdetail',
        ],
        'getInboundList' => [
            'method' => 'fu.wms.inbound.getlist',
        ],
        'createInbound' => [
            'method' => 'fu.wms.inbound.create',
        ],
        'cancelInbound' => [
            'method' => 'fu.wms.inbound.cancel',
        ],
        'getInboundLabel' => [
            'method' => 'fu.wms.inbound.getinboundlabel',
        ],
        'uploadInboundInvoiceFile' => [
            'method' => 'fu.wms.inbound.uploadinvoicefile',
        ],
        'getOutboundList' => [
            'method' => 'fu.wms.outbound.getlist',
        ],
        'createOutbound' => [
            'method' => 'fu.wms.outbound.create',
        ],
        'cancelOutbound' => [
            'method' => 'fu.wms.outbound.cancel',
        ],
        'getShipmentLabel' => [
            'method' => 'fu.wms.shipment.getlabel',
        ],
        'createShipment' => [
            'method' => 'fu.wms.shipment.create',
        ],
        'cancelShipment' => [
            'method' => 'fu.wms.shipment.cancel',
        ],
    ];

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
        if (empty($this->url)) {
            $this->url = $this->productionUrl;
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
     * @param bool $sandbox
     * @inheritdoc
     */
    public function setSandbox($sandbox = true)
    {
        $this->url = $sandbox ? $this->sandboxUrl : $this->productionUrl;
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
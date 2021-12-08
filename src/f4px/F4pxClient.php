<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\f4px;

use Iterator;
use lujie\extend\authclient\BaseJsonRpcClient;
use lujie\extend\authclient\JsonRpcResponse;
use Yii;
use yii\base\NotSupportedException;
use yii\helpers\Json;
use yii\httpclient\CurlTransport;
use yii\httpclient\Request;
use yii\httpclient\Response;

/**
 * Class F4pxClient
 *
 * @method array getSkuList(array $data)
 * @method array editSkuPicture(array $data)
 * @method array uploadSkuReport(array $data)
 * @method array createSku(array $data)
 * @method array getInventory(array $data)
 * @method array getInventoryLog(array $data)
 * @method array getInventoryDetail(array $data)
 * @method array getInboundList(array $data)
 * @method array createInbound(array $data)
 * @method array cancelInbound(array $data)
 * @method array getInboundLabel(array $data)
 * @method array uploadInboundInvoiceFile(array $data)
 * @method array getOutboundList(array $data)
 * @method array createOutbound(array $data)
 * @method array cancelOutbound(array $data)
 * @method array getShipmentLabel(array $data)
 * @method array createShipment(array $data)
 * @method array cancelShipment(array $data)
 * @method array getWarehouseList(array $data)
 * @method array getLogisticsList(array $data)
 * @method array getBilling(array $data)
 *
 * @method \Generator eachInboundList(array $data)
 * @method \Generator batchInboundList(array $data)
 * @method \Generator eachOutboundList(array $data)
 * @method \Generator batchOutboundList(array $data)
 * @method \Generator eachInventory(array $data)
 * @method \Generator batchInventory(array $data)
 * @method \Generator eachInventoryLog(array $data)
 * @method \Generator batchInventoryLog(array $data)
 * @method \Generator eachInventoryDetail(array $data)
 * @method \Generator batchInventoryDetail(array $data)
 *
 * @package lujie\fulfillment\f4px
 * @author Lujie Zhou <gao_lujie@live.cn>
 * @document http://open.4px.com/apiInfo/apiDetail?itemId=1&mainId=106#
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
    public $version = '1.0';

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
//            'method' => 'fu.wms.sku.create',
            'method' => 'fu.wms.sku.newcreate',
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
        'getWarehouseList' => [
            'method' => 'com.basis.warehouse.getlist',
        ],
        'getLogisticsList' => [
            'method' => 'com.basis.logistics_product.getlist',
        ],
        'getBilling' => [
            'method' => 'com.basis.billing.getbilling',
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
    public function setSandbox(bool $sandbox = true): void
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
        $commonData = $this->getCommonData();
        $commonData['method'] = $data['method'];
        unset($data['method']);
        $request->setData($data);

        //yii default json option will cause wrong sign
        $signData = array_merge($commonData, ['body' => Json::encode($data, 0)]);
        $commonData['sign'] = $this->getSign($signData);
        $request->setUrl(array_merge([$this->url], $commonData));
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
            if (in_array($key, ['access_token', 'language', 'body'], true)) {
                continue;
            }
            $signString .= $key . $value;
        }
        $signString .= $data['body'] . $this->appSecret;
        Yii::debug([$data, $signString], __METHOD__);
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
        $jsonRpcResponse->success = (bool)$data['result'];
        $jsonRpcResponse->message = $data['msg'];
        $jsonRpcResponse->data = $data['data'] ?? [];
        $jsonRpcResponse->errors = $data['errors'] ?? [];
        return $jsonRpcResponse;
    }

    /**
     * @param string $resource
     * @param array $condition
     * @param int $batchSize
     * @return Iterator
     * @throws NotSupportedException
     * @throws \lujie\extend\authclient\JsonRpcException
     * @throws \yii\authclient\InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function batch(string $resource, array $condition = [], int $batchSize = 100): Iterator
    {
        $listMethod = 'get' . $resource;
        if ($this->reverse) {
            $rpcResponse = $this->call($listMethod, $condition);
            $responseData = $this->getResponseData($rpcResponse);

            $firstPageItems = $responseData['data'] ?? [];

            $firstPage = $responseData['page_no'] ?? 1;
            $condition['page_no'] = (int)ceil($responseData['total'] / $responseData['page_size']);

            while ($condition['page_no'] > $firstPage) {
                $rpcResponse = $this->call($listMethod, $condition);
                $responseData = $this->getResponseData($rpcResponse);

                $items = $responseData['data'] ?? [];

                $items = array_reverse($items);
                yield $items;

                $condition['page_no']--;
            }

            $firstPageItems = array_reverse($firstPageItems);
            yield $firstPageItems;
        } else {
            do {
                $rpcResponse = $this->call($listMethod, $condition);
                $responseData = $this->getResponseData($rpcResponse);

                $items = $responseData['data'] ?? [];
                yield $items;

                $pageCount = (int)ceil($responseData['total'] / $responseData['page_size']);
                $condition['page_no'] =  $responseData['page_no'] ?? 1;
                $condition['page_no']++;
            } while ($condition['page_no'] <= $pageCount);
        }
    }
}

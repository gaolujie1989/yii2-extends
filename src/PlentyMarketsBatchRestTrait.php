<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\plentyMarkets;

use yii\helpers\Json;

/**
 *
 * @method array batchRequest($data)
 *
 * Trait PlentyMarketsBatchRestTrait
 * @package lujie\plentyMarkets
 */
trait PlentyMarketsBatchRestTrait
{
    /**
     * @param array $variationIds
     * @return array
     * @inheritdoc
     */
    public function getWarehouseStocksByVariationIds(array $variationIds): array
    {
        $payloads = array_map(static function ($variationId) {
            return [
                'resource' => 'rest/stockmanagement/stock?variationId=' . $variationId,
                'method' => 'GET',
            ];
        }, $variationIds);
        $batchResponse = $this->batchRequest(['payloads' => $payloads]);
        $warehouseStocks = [];
        foreach ($batchResponse as $response) {
            $content = Json::decode($response['content']);
            $warehouseStocks[] = $content['entries'];
        }
        return array_merge(...$warehouseStocks);
    }

    /**
     * @param array $orderIds
     * @return array
     * @inheritdoc
     */
    public function getOrdersByExternalOrderNos(array $orderNos): array
    {
        $payloads = array_map(static function ($orderNo) {
            return [
                'resource' => 'rest/orders',
                'method' => 'GET',
                'body' => [
                    'externalOrderId' => $orderNo,
                ],
            ];
        }, $orderNos);
        $batchResponse = $this->batchRequest(['payloads' => $payloads]);
        $orders = [];
        foreach ($batchResponse as $response) {
            $content = Json::decode($response['content']);
            $entries = $content['entries'];
            $orders[] = $entries;
        }
        return array_merge(...$orders);
    }

    /**
     * @param array $orderIds
     * @return array
     * @inheritdoc
     */
    public function getOrdersByOrderIds(array $orderIds): array
    {
        $payloads = array_map(static function ($orderId) {
            return [
                'resource' => "rest/orders/{$orderId}",
                'method' => 'GET',
            ];
        }, $orderIds);
        $batchResponse = $this->batchRequest(['payloads' => $payloads]);
        $orders = [];
        foreach ($batchResponse as $response) {
            $orderId = substr($response['resource'], 12);
            $content = Json::decode($response['content']);
            $orders[$orderId] = $content;
        }
        return $orders;
    }

    /**
     * @param array $orderIds
     * @return array
     * @inheritdoc
     */
    public function getOrderPackageNumbersByOrderIds(array $orderIds): array
    {
        $payloads = array_map(static function ($orderId) {
            return [
                'resource' => "rest/orders/{$orderId}/packagenumbers",
                'method' => 'GET',
            ];
        }, $orderIds);
        $batchResponse = $this->batchRequest(['payloads' => $payloads]);
        $orderPackageNumbers = [];
        foreach ($batchResponse as $response) {
            $orderId = substr($response['resource'], 12, -15);
            $content = Json::decode($response['content']);
            $orderPackageNumbers[$orderId] = $content;
        }
        return $orderPackageNumbers;
    }
}

<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\plentyMarkets;

use yii\helpers\ArrayHelper;

/**
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
        /** @var PlentyMarketBatchRequest $batchRequest */
        $batchRequest = $this->createBatchRequest();
        foreach ($variationIds as $variationId) {
            $batchRequest->listStocks(['variationId' => $variationId]);
        }
        $batchResponses = $batchRequest->send();
        $entries = ArrayHelper::getColumn($batchResponses, 'content.entries');
        return array_merge(...array_filter($entries));
    }

    /**
     * @param array $orderIds
     * @return array
     * @inheritdoc
     */
    public function getOrdersByExternalOrderNos(array $orderNos): array
    {
        /** @var PlentyMarketBatchRequest $batchRequest */
        $batchRequest = $this->createBatchRequest();
        foreach ($orderNos as $orderNo) {
            $batchRequest->listOrders(['externalOrderId' => $orderNo]);
        }
        $batchResponses = $batchRequest->send();
        $entries = ArrayHelper::getColumn($batchResponses, 'content.entries');
        return array_merge(...array_filter($entries));
    }

    /**
     * @param array $orderIds
     * @return array
     * @inheritdoc
     */
    public function getOrdersByOrderIds(array $orderIds): array
    {
        /** @var PlentyMarketBatchRequest $batchRequest */
        $batchRequest = $this->createBatchRequest();
        foreach ($orderIds as $orderId) {
            $batchRequest->getOrder(['id' => $orderId]);
        }
        $batchResponses = $batchRequest->send();
        return ArrayHelper::map($batchResponses, 'content.id', 'content');
    }

    /**
     * @param array $orderIds
     * @return array
     * @inheritdoc
     */
    public function getOrderPackageNumbersByOrderIds(array $orderIds): array
    {
        /** @var PlentyMarketBatchRequest $batchRequest */
        $batchRequest = $this->createBatchRequest();
        foreach ($orderIds as $orderId) {
            $batchRequest->getOrderPackageNumbers(['orderId' => $orderId]);
        }
        $batchResponses = $batchRequest->send();
        return ArrayHelper::map($batchResponses, static function ($response) {
            $parts = parse_url($response['resource']);
            return substr($parts['path'], 12, -15);
        }, 'content');
    }
}

<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\plentyMarkets;

use lujie\sales\channel\channels\pm\PmSalesChannel;

/**
 * Trait PlentyMarketsBatchRestTrait
 * @package lujie\plentyMarkets
 */
trait PlentyMarketsRestExtendTrait
{
    /**
     * @param int $warehouseId
     * @param int $variationId
     * @param int $reasonId
     * @return array
     * @inheritdoc
     */
    public function clearWarehouseLocationStocks(int $warehouseId, int $variationId, int $reasonId = 300): array
    {
        $locationStocks = $this->eachWarehouseLocationStocks(['warehouseId' => $warehouseId, 'variationId' => $variationId]);
        $corrections = [];
        foreach ($locationStocks as $locationStock) {
            $corrections[] = [
                "variationId" => $variationId,
                'reasonId' => $reasonId,
                "quantity" => 0,
                "storageLocationId" => $locationStock['storageLocationId'],
            ];
        }
        if ($corrections) {
            $this->correctStock([
                'warehouseId' => $warehouseId,
                'corrections' => $corrections
            ]);
        }
        return $corrections;
    }

    /**
     * @param int $warehouseId
     * @param int $fromVariationId
     * @param int $toVariationId
     * @param int $reasonId
     * @param string $deliveredAt
     * @return array
     * @inheritdoc
     */
    public function moveWarehouseLocationStocks(int $warehouseId, int $fromVariationId, int $toVariationId, int $reasonId = 116, string $deliveredAt = 'now'): array
    {
        $locationStocks = $this->eachWarehouseLocationStocks(['warehouseId' => $warehouseId, 'variationId' => $fromVariationId]);
        $incomingItems = [];
        foreach ($locationStocks as $locationStock) {
            if ($locationStock['quantity'] > 0) {
                $incomingItems[] = [
                    "variationId" => $toVariationId,
                    'reasonId' => $reasonId,
                    "quantity" => $locationStock['quantity'],
                    "storageLocationId" => $locationStock['storageLocationId'],
                    "deliveredAt" => date('c', strtotime($deliveredAt)),
                ];
            }
        }
        if ($incomingItems) {
            $this->bookIncomingStock([
                'warehouseId' => $warehouseId,
                'incomingItems' => $incomingItems
            ]);
        }
        return $incomingItems;
    }
}

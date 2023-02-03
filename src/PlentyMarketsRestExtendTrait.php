<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\plentyMarkets;

use lujie\extend\helpers\ValueHelper;
use yii\helpers\ArrayHelper;

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

    /**
     * @param int $itemId
     * @param int $variationId
     * @param array $data
     * @return array
     * @inheritdoc
     */
    public function saveVariationBundleComponents(int $itemId, int $variationId, array $values = [], ?array $existValues = null): array
    {
        return $this->saveVariationRelationParts($itemId, $variationId, 'ItemVariationBundle', $values, $existValues, ['componentVariationId'], ['componentQuantity']);
    }

    /**
     * @param int $itemId
     * @param int $variationId
     * @param array $data
     * @return array
     * @inheritdoc
     */
    public function saveVariationMarkets(int $itemId, int $variationId, array $values = [], ?array $existValues = null): array
    {
        return $this->saveVariationRelationParts($itemId, $variationId, 'ItemVariationMarket', $values, $existValues, ['marketId']);
    }

    /**
     * @param int $itemId
     * @param int $variationId
     * @param array $data
     * @return array
     * @inheritdoc
     */
    public function saveVariationSkus(int $itemId, int $variationId, array $values = [], ?array $existValues = null): array
    {
        return $this->saveVariationRelationParts($itemId, $variationId, 'ItemVariationSku', $values, $existValues, ['marketId', 'accountId'], ['sku', 'parentSku']);
    }

    /**
     * @param int $itemId
     * @param int $variationId
     * @param array $data
     * @return array
     * @inheritdoc
     */
    public function saveVariationImages(int $itemId, int $variationId, array $values = [], ?array $existValues = null): array
    {
        return $this->saveVariationRelationParts($itemId, $variationId, 'ItemVariationImage', $values, $existValues, ['imageId']);
    }


    /**
     * @param int $itemId
     * @param int $variationId
     * @param string $relationType
     * @param array $relationValues
     * @return array
     * @inheritdoc
     */
    protected function saveVariationRelationParts(
        int    $itemId,
        int    $variationId,
        string $relationType,
        array  $saveRelationValues = [],
        ?array $existRelationValues = null,
        array  $indexKeys = [],
        array  $updateKeys = []): array
    {
        $relationType = ucfirst($relationType);
        $eachMethod = 'each' . $relationType;
        $createMethod = 'create' . $relationType;
        $updateMethod = 'update' . $relationType;
        $deleteMethod = 'delete' . $relationType;
        if ($existRelationValues === null) {
            $existRelationValues = $this->{$eachMethod}([
                'variationId' => $variationId,
                'itemId' => $itemId,
            ]);
            $existRelationValues = iterator_to_array($existRelationValues, false);
        }

        $indexKeyFunc = static function ($values) use ($indexKeys) {
            ValueHelper::getIndexValues($values, $indexKeys);
        };
        $saveRelationValues = ArrayHelper::index($saveRelationValues, $indexKeyFunc);
        $existRelationValues = ArrayHelper::index($existRelationValues, $indexKeyFunc);

        $batchRequest = $this->createBatchRequest();
        $toCreateValues = array_diff_key($saveRelationValues, $existRelationValues);
        $toDeleteValues = array_diff_key($existRelationValues, $saveRelationValues);
        $toUpdateValues = [];
        if ($updateKeys) {
            $shouldUpdateValues = array_intersect_key($saveRelationValues, $existRelationValues);
            foreach ($shouldUpdateValues as $key => $toUpdateValue) {
                $existValue = $existRelationValues[$key];
                foreach ($updateKeys as $updateKey) {
                    if ((string)$toUpdateValue[$updateKey] !== (string)$existValue[$updateKey]) {
                        $toUpdateValue['id'] = $existValue['id'];
                        $toUpdateValues[] = $toUpdateValue;
                    }
                }
            }
        }
        $actionMethodValues = [
            $createMethod => $toCreateValues,
            $updateMethod => $toUpdateValues,
            $deleteMethod => $toDeleteValues,
        ];
        foreach ($actionMethodValues as $actionMethod => $actionValues) {
            foreach ($actionValues as $actionValue) {
                $actionValue['variationId'] = $variationId;
                $actionValue['itemId'] = $itemId;
                $batchRequest->{$actionMethod}($actionValue);
            }
        }
        return $batchRequest->send();
    }
}

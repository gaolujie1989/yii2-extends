<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\plentyMarkets;

use lujie\extend\helpers\ValueHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

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
     * @param int $imageId
     * @param array $values
     * @param array|null $existValues
     * @return array
     * @throws \yii\authclient\InvalidResponseException
     * @inheritdoc
     */
    public function saveItemImageAttributeValueMarkets(int $itemId, int $imageId, array $values = [], ?array $existValues = null): array
    {
        $relationIds = ['itemId' => $itemId, 'imageId' => $imageId];
        return $this->saveRelationParts($relationIds, 'ItemImageAttributeValueMarket', $values, $existValues, ['valueId'], ['valueId']);
    }

    /**
     * @param int $itemId
     * @param int $variationId
     * @param array $values
     * @param array|null $existValues
     * @return array
     * @throws \yii\authclient\InvalidResponseException
     * @inheritdoc
     */
    public function saveVariationSalesPrices(int $itemId, int $variationId, array $values = [], ?array $existValues = null): array
    {
        $relationIds = ['itemId' => $itemId, 'variationId' => $variationId];
        return $this->saveRelationParts($relationIds, 'ItemVariationSalesPrice', $values, $existValues, ['salesPriceId'], ['price']);
    }

    /**
     * @param int $itemId
     * @param int $variationId
     * @param array $values
     * @param array|null $existValues
     * @return array
     * @throws \yii\authclient\InvalidResponseException
     * @inheritdoc
     */
    public function saveVariationBarcodes(int $itemId, int $variationId, array $values = [], ?array $existValues = null): array
    {
        $relationIds = ['itemId' => $itemId, 'variationId' => $variationId];
        return $this->saveRelationParts($relationIds, 'ItemVariationBarcode', $values, $existValues, ['barcodeId'], ['code']);
    }

    /**
     * @param int $itemId
     * @param int $variationId
     * @param array $values
     * @param array|null $existValues
     * @return array
     * @throws \yii\authclient\InvalidResponseException
     * @inheritdoc
     */
    public function saveVariationBundleComponents(int $itemId, int $variationId, array $values = [], ?array $existValues = null): array
    {
        $relationIds = ['itemId' => $itemId, 'variationId' => $variationId];
        return $this->saveRelationParts($relationIds, 'ItemVariationBundle', $values, $existValues, ['componentVariationId'], ['componentQuantity']);
    }

    /**
     * @param int $itemId
     * @param int $variationId
     * @param array $values
     * @param array|null $existValues
     * @return array
     * @throws \yii\authclient\InvalidResponseException
     * @inheritdoc
     */
    public function saveVariationMarkets(int $itemId, int $variationId, array $values = [], ?array $existValues = null): array
    {
        $relationIds = ['itemId' => $itemId, 'variationId' => $variationId];
        return $this->saveRelationParts($relationIds, 'ItemVariationMarket', $values, $existValues, ['marketId']);
    }

    /**
     * @param int $itemId
     * @param int $variationId
     * @param array $values
     * @param array|null $existValues
     * @return array
     * @throws \yii\authclient\InvalidResponseException
     * @inheritdoc
     */
    public function saveVariationSkus(int $itemId, int $variationId, array $values = [], ?array $existValues = null): array
    {
        $relationIds = ['itemId' => $itemId, 'variationId' => $variationId];
        return $this->saveRelationParts($relationIds, 'ItemVariationSku', $values, $existValues, ['marketId', 'accountId'], ['sku', 'parentSku']);
    }

    /**
     * @param int $itemId
     * @param int $variationId
     * @param array $values
     * @param array|null $existValues
     * @return array
     * @throws \yii\authclient\InvalidResponseException
     * @inheritdoc
     */
    public function saveVariationImages(int $itemId, int $variationId, array $values = [], ?array $existValues = null): array
    {
        $relationIds = ['itemId' => $itemId, 'variationId' => $variationId];
        return $this->saveRelationParts($relationIds, 'ItemVariationImage', $values, $existValues, ['imageId']);
    }

    /**
     * @param array $relationIds
     * @param string $relationType
     * @param array $saveRelationValues
     * @param array|null $existRelationValues
     * @param array $indexKeys
     * @param array $updateKeys
     * @return array
     * @throws \yii\authclient\InvalidResponseException
     * @inheritdoc
     */
    protected function saveRelationParts(
        array  $relationIds,
        string $relationType,
        array  $saveRelationValues = [],
        ?array $existRelationValues = null,
        array  $indexKeys = [],
        array  $updateKeys = []): array
    {
        $relationType = ucfirst($relationType);
        $eachMethod = 'each' . $relationType;
        if ($existRelationValues === null) {
            $existRelationValues = $this->{$eachMethod}($relationIds);
            $existRelationValues = iterator_to_array($existRelationValues, false);
        }

        $mergeRelationIdsFuck = static function($values) use ($relationIds) {
            return array_merge($values, $relationIds);
        };
        $saveRelationValues = array_map($mergeRelationIdsFuck, $saveRelationValues);
        $existRelationValues = array_map($mergeRelationIdsFuck, $existRelationValues);

        $indexKeyFunc = static function ($values) use ($indexKeys) {
            return ValueHelper::getIndexValues($values, $indexKeys);
        };
        $saveRelationValues = ArrayHelper::index($saveRelationValues, $indexKeyFunc);
        $existRelationValues = ArrayHelper::index($existRelationValues, $indexKeyFunc);

        $toCreateValues = array_diff_key($saveRelationValues, $existRelationValues);
        $toDeleteValues = array_diff_key($existRelationValues, $saveRelationValues);
        $toUpdateValues = [];
        $shouldUpdateValues = array_intersect_key($saveRelationValues, $existRelationValues);
        if ($updateKeys && $shouldUpdateValues) {
            foreach ($shouldUpdateValues as $key => $toUpdateValue) {
                $existValue = $existRelationValues[$key];
                foreach ($updateKeys as $updateKey) {
                    if ((string)$toUpdateValue[$updateKey] !== (string)$existValue[$updateKey]) {
                        if (isset($existValue['id'])) {
                            $toUpdateValue['id'] = $existValue['id'];
                        }
                        $toUpdateValues[] = $toUpdateValue;
                    }
                }
            }
        }

        $bulkParts = ['ItemVariationSalesPrice', 'ItemVariationMarket', 'ItemVariationProperty', 'ItemShippingProfile'];
        $isBulkParts = in_array($relationType, $bulkParts, true);
        if ($isBulkParts) {
            $bulkCreateMethod = 'bulkCreate' . Inflector::pluralize($relationType);
            $bulkUpdateMethod = 'bulkUpdate' . Inflector::pluralize($relationType);
            $bulkDeleteMethod = 'bulkDelete' . Inflector::pluralize($relationType);
            $responseData = [];
            //傻逼PM, 如果删除了所有ItemVariationSalesPrice，价格会从MainVariation里面复制过来
            if ($toDeleteValues && $relationType !== 'ItemVariationSalesPrice') {
                $this->{$bulkDeleteMethod}($relationIds);
                if ($createValues = array_merge($toCreateValues, $shouldUpdateValues)) {
                    return $this->{$bulkCreateMethod}($createValues);
                }
                return [];
            } else {
                if ($toDeleteValues) {
                    $deleteMethod = 'delete' . $relationType;
                    $batchRequest = $this->createBatchRequest();
                    foreach ($toDeleteValues as $toDeleteValue) {
                        $batchRequest->{$deleteMethod}($toDeleteValue);
                    }
                    $batchRequest->send();
                }
                $actionMethodValues = array_filter([
                    $bulkUpdateMethod => $toUpdateValues,
                    $bulkCreateMethod => $toCreateValues,
                ]);
                foreach ($actionMethodValues as $actionMethod => $actionValues) {
                    $responseData[$actionMethod] = $this->{$actionMethod}($actionValues);
                }
                return array_merge(...array_values($responseData));
            }
        } else {
            $createMethod = 'create' . $relationType;
            $updateMethod = 'update' . $relationType;
            $deleteMethod = 'delete' . $relationType;
            $actionMethodValues = array_filter([
                $deleteMethod => $toDeleteValues,
                $updateMethod => $toUpdateValues,
                $createMethod => $toCreateValues,
            ]);
            $batchRequest = $this->createBatchRequest();
            foreach ($actionMethodValues as $actionMethod => $actionValues) {
                foreach ($actionValues as $actionValue) {
                    $batchRequest->{$actionMethod}($actionValue);
                }
            }
            return $batchRequest->send();
        }
    }
}

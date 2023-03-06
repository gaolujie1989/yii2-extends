<?php

namespace lujie\fulfillment\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[FulfillmentItemValue]].
 *
 * @method FulfillmentItemValueQuery id($id)
 * @method FulfillmentItemValueQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method FulfillmentItemValueQuery fulfillmentItemValueId($fulfillmentItemValueId)
 * @method FulfillmentItemValueQuery fulfillmentDailyStockMovementId($fulfillmentDailyStockMovementId)
 * @method FulfillmentItemValueQuery externalItemKey($externalItemKey, bool|string $like = false)
 * @method FulfillmentItemValueQuery externalWarehouseKey($externalWarehouseKey, bool|string $like = false)
 * @method FulfillmentItemValueQuery valueDate($valueDate)
 * @method FulfillmentItemValueQuery valueDateBefore($valueDate)

 * @method FulfillmentItemValueQuery valueDateBetween($from, $to = null)
 * @method FulfillmentItemValueQuery createdAtBetween($from, $to = null)
 * @method FulfillmentItemValueQuery updatedAtBetween($from, $to = null)
 *
 * @method FulfillmentItemValueQuery latest()
 *
 * @method FulfillmentItemValueQuery orderByFulfillmentItemValueId($sort = SORT_ASC)
 * @method FulfillmentItemValueQuery orderByFulfillmentDailyStockMovementId($sort = SORT_ASC)
 * @method FulfillmentItemValueQuery orderByValueDate($sort = SORT_ASC)
 * @method FulfillmentItemValueQuery orderByCreatedAt($sort = SORT_ASC)
 * @method FulfillmentItemValueQuery orderByUpdatedAt($sort = SORT_ASC)
 *
 * @method FulfillmentItemValueQuery indexByFulfillmentItemValueId()
 * @method FulfillmentItemValueQuery indexByFulfillmentDailyStockMovementId()
 * @method FulfillmentItemValueQuery indexByExternalItemKey()
 * @method FulfillmentItemValueQuery indexByExternalWarehouseKey()
 *
 * @method array getFulfillmentItemValueIds()
 * @method array getFulfillmentDailyStockMovementIds()
 * @method array getExternalItemKeys()
 * @method array getExternalWarehouseKeys()
 *
 * @method array|FulfillmentItemValue[] all($db = null)
 * @method array|FulfillmentItemValue|null one($db = null)
 * @method array|FulfillmentItemValue[] each($batchSize = 100, $db = null)
 *
 * @see FulfillmentItemValue
 */
class FulfillmentItemValueQuery extends \yii\db\ActiveQuery
{

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            'fieldQuery' => [
                'class' => FieldQueryBehavior::class,
                'queryFields' => [
                    'fulfillmentItemValueId' => 'fulfillment_item_value_id',
                    'fulfillmentDailyStockMovementId' => 'fulfillment_daily_stock_movement_id',
                    'externalItemKey' => ['external_item_key' => FieldQueryBehavior::TYPE_STRING],
                    'externalWarehouseKey' => ['external_warehouse_key' => FieldQueryBehavior::TYPE_STRING],
                    'valueDateBetween' => ['value_date' => 'BETWEEN'],
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                    'updatedAtBetween' => ['updated_at' => 'BETWEEN'],
                    'valueDate' => 'value_date',
                    'valueDateBefore' => ['value_date' => '<'],
                ],
                'queryConditions' => [
                    'latest' => ['latest' => 1],
                ],
                'querySorts' => [
                    'orderByFulfillmentItemValueId' => 'fulfillment_item_value_id',
                    'orderByFulfillmentDailyStockMovementId' => 'fulfillment_daily_stock_movement_id',
                    'orderByValueDate' => 'value_date',
                    'orderByCreatedAt' => 'created_at',
                    'orderByUpdatedAt' => 'updated_at',
                ],
                'queryIndexes' => [
                    'indexByFulfillmentItemValueId' => 'fulfillment_item_value_id',
                    'indexByFulfillmentDailyStockMovementId' => 'fulfillment_daily_stock_movement_id',
                    'indexByExternalItemKey' => 'external_item_key',
                    'indexByExternalWarehouseKey' => 'external_warehouse_key',
                ],
                'queryReturns' => [
                    'getFulfillmentItemValueIds' => ['fulfillment_item_value_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getFulfillmentDailyStockMovementIds' => ['fulfillment_daily_stock_movement_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getExternalItemKeys' => ['external_item_key', FieldQueryBehavior::RETURN_COLUMN],
                    'getExternalWarehouseKeys' => ['external_warehouse_key', FieldQueryBehavior::RETURN_COLUMN],
                ],
            ]
        ];
    }
}

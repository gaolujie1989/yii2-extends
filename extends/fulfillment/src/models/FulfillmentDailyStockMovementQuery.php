<?php

namespace lujie\fulfillment\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[FulfillmentDailyStockMovement]].
 *
 * @method FulfillmentDailyStockMovementQuery id($id)
 * @method FulfillmentDailyStockMovementQuery orderById($sort = SORT_ASC)
 * @method FulfillmentDailyStockMovementQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method FulfillmentDailyStockMovementQuery fulfillmentDailyStockMovementId($fulfillmentDailyStockMovementId)
 * @method FulfillmentDailyStockMovementQuery fulfillmentAccountId($fulfillmentAccountId)
 * @method FulfillmentDailyStockMovementQuery externalItemKey($externalItemKey, bool|string $like = false)
 * @method FulfillmentDailyStockMovementQuery externalWarehouseKey($externalWarehouseKey, bool|string $like = false)
 * @method FulfillmentDailyStockMovementQuery movementType($movementType)
 * @method FulfillmentDailyStockMovementQuery movementDate($movementDate)
 *
 * @method FulfillmentDailyStockMovementQuery movementDateBetween($from, $to = null)
 * @method FulfillmentDailyStockMovementQuery createdAtBetween($from, $to = null)
 * @method FulfillmentDailyStockMovementQuery updatedAtBetween($from, $to = null)
 *
 * @method FulfillmentDailyStockMovementQuery orderByFulfillmentDailyStockMovementId($sort = SORT_ASC)
 * @method FulfillmentDailyStockMovementQuery orderByFulfillmentAccountId($sort = SORT_ASC)
 * @method FulfillmentDailyStockMovementQuery orderByMovementDate($sort = SORT_ASC)
 * @method FulfillmentDailyStockMovementQuery orderByCreatedAt($sort = SORT_ASC)
 * @method FulfillmentDailyStockMovementQuery orderByUpdatedAt($sort = SORT_ASC)
 *
 * @method FulfillmentDailyStockMovementQuery indexByFulfillmentDailyStockMovementId()
 * @method FulfillmentDailyStockMovementQuery indexByFulfillmentAccountId()
 * @method FulfillmentDailyStockMovementQuery indexByExternalItemKey()
 * @method FulfillmentDailyStockMovementQuery indexByExternalWarehouseKey()
 *
 * @method string maxMovementDate()
 *
 * @method array getFulfillmentDailyStockMovementIds()
 * @method array getFulfillmentAccountIds()
 * @method array getExternalItemKeys()
 * @method array getExternalWarehouseKeys()
 *
 * @method array|FulfillmentDailyStockMovement[] all($db = null)
 * @method array|FulfillmentDailyStockMovement|null one($db = null)
 * @method array|FulfillmentDailyStockMovement[] each($batchSize = 100, $db = null)
 *
 * @see FulfillmentDailyStockMovement
 */
class FulfillmentDailyStockMovementQuery extends \yii\db\ActiveQuery
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
                    'fulfillmentDailyStockMovementId' => 'fulfillment_daily_stock_movement_id',
                    'fulfillmentAccountId' => 'fulfillment_account_id',
                    'externalItemKey' => ['external_item_key' => FieldQueryBehavior::TYPE_STRING],
                    'externalWarehouseKey' => ['external_warehouse_key' => FieldQueryBehavior::TYPE_STRING],
                    'movementType' => 'movement_type',
                    'movementDate' => 'movement_date',
                    'movementDateFrom' => ['movement_date' => '>='],
                    'movementDateTo' => ['movement_date' => '<='],
                    'movementDateBetween' => ['movement_date' => 'BETWEEN'],
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                    'updatedAtBetween' => ['updated_at' => 'BETWEEN'],
                ],
                'queryConditions' => [],
                'querySorts' => [
                    'orderByFulfillmentDailyStockMovementId' => 'fulfillment_daily_stock_movement_id',
                    'orderByFulfillmentAccountId' => 'fulfillment_account_id',
                    'orderByMovementDate' => 'movement_date',
                    'orderByCreatedAt' => 'created_at',
                    'orderByUpdatedAt' => 'updated_at',
                ],
                'queryIndexes' => [
                    'indexByFulfillmentDailyStockMovementId' => 'fulfillment_daily_stock_movement_id',
                    'indexByFulfillmentAccountId' => 'fulfillment_account_id',
                    'indexByExternalItemKey' => 'external_item_key',
                    'indexByExternalWarehouseKey' => 'external_warehouse_key',
                ],
                'queryReturns' => [
                    'getFulfillmentDailyStockMovementIds' => ['fulfillment_daily_stock_movement_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getFulfillmentAccountIds' => ['fulfillment_account_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getExternalItemKeys' => ['external_item_key', FieldQueryBehavior::RETURN_COLUMN],
                    'getExternalWarehouseKeys' => ['external_warehouse_key', FieldQueryBehavior::RETURN_COLUMN],
                    'maxMovementDate' => ['movement_date', FieldQueryBehavior::RETURN_MAX],
                ]
            ]
        ];
    }

}

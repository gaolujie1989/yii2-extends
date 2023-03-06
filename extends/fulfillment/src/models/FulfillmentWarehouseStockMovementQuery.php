<?php

namespace lujie\fulfillment\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[FulfillmentWarehouseStockMovement]].
 *
 * @method FulfillmentWarehouseStockMovementQuery id($id)
 * @method FulfillmentWarehouseStockMovementQuery orderById($sort = SORT_ASC)
 * @method FulfillmentWarehouseStockMovementQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method FulfillmentWarehouseStockMovementQuery fulfillmentWarehouseStockMovementId($fulfillmentWarehouseStockMovementId)
 * @method FulfillmentWarehouseStockMovementQuery fulfillmentAccountId($fulfillmentAccountId)
 * @method FulfillmentWarehouseStockMovementQuery externalItemKey($externalItemKey, bool|string $like = false)
 * @method FulfillmentWarehouseStockMovementQuery externalWarehouseKey($externalWarehouseKey, bool|string $like = false)
 * @method FulfillmentWarehouseStockMovementQuery externalMovementKey($externalMovementKey, bool|string $like = false)
 * @method FulfillmentWarehouseStockMovementQuery movementType($movementType)
 * @method FulfillmentWarehouseStockMovementQuery relatedType($relatedType)
 * @method FulfillmentWarehouseStockMovementQuery relatedKey($relatedKey, bool|string $like = false)
 *
 * @method FulfillmentWarehouseStockMovementQuery externalCreatedAtBetween($from, $to = null)
 * @method FulfillmentWarehouseStockMovementQuery createdAtBetween($from, $to = null)
 * @method FulfillmentWarehouseStockMovementQuery updatedAtBetween($from, $to = null)
 *
 * @method FulfillmentWarehouseStockMovementQuery orderByFulfillmentWarehouseStockMovementId($sort = SORT_ASC)
 * @method FulfillmentWarehouseStockMovementQuery orderByFulfillmentAccountId($sort = SORT_ASC)
 * @method FulfillmentWarehouseStockMovementQuery orderByExternalCreatedAt($sort = SORT_ASC)
 * @method FulfillmentWarehouseStockMovementQuery orderByCreatedAt($sort = SORT_ASC)
 * @method FulfillmentWarehouseStockMovementQuery orderByUpdatedAt($sort = SORT_ASC)
 *
 * @method FulfillmentWarehouseStockMovementQuery indexByFulfillmentWarehouseStockMovementId()
 * @method FulfillmentWarehouseStockMovementQuery indexByFulfillmentAccountId()
 * @method FulfillmentWarehouseStockMovementQuery indexByExternalItemKey()
 * @method FulfillmentWarehouseStockMovementQuery indexByExternalWarehouseKey()
 * @method FulfillmentWarehouseStockMovementQuery indexByExternalMovementKey()
 * @method FulfillmentWarehouseStockMovementQuery indexByRelatedKey()
 *
 * @method array getFulfillmentWarehouseStockMovementIds()
 * @method array getFulfillmentAccountIds()
 * @method array getExternalItemKeys()
 * @method array getExternalWarehouseKeys()
 * @method array getExternalMovementKeys()
 * @method array getRelatedKeys()
 *
 * @method array|FulfillmentWarehouseStockMovement[] all($db = null)
 * @method array|FulfillmentWarehouseStockMovement|null one($db = null)
 * @method array|FulfillmentWarehouseStockMovement[] each($batchSize = 100, $db = null)
 *
 * @see FulfillmentWarehouseStockMovement
 */
class FulfillmentWarehouseStockMovementQuery extends \yii\db\ActiveQuery
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
                    'fulfillmentWarehouseStockMovementId' => 'fulfillment_warehouse_stock_movement_id',
                    'fulfillmentAccountId' => 'fulfillment_account_id',
                    'externalItemKey' => ['external_item_key' => FieldQueryBehavior::TYPE_STRING],
                    'externalWarehouseKey' => ['external_warehouse_key' => FieldQueryBehavior::TYPE_STRING],
                    'externalMovementKey' => ['external_movement_key' => FieldQueryBehavior::TYPE_STRING],
                    'movementType' => 'movement_type',
                    'relatedType' => 'related_type',
                    'relatedKey' => 'related_key',
                    'externalCreatedAtBetween' => ['external_created_at' => 'BETWEEN'],
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                    'updatedAtBetween' => ['updated_at' => 'BETWEEN'],
                ],
                'queryConditions' => [],
                'querySorts' => [
                    'orderByFulfillmentWarehouseStockMovementId' => 'fulfillment_warehouse_stock_movement_id',
                    'orderByFulfillmentAccountId' => 'fulfillment_account_id',
                    'orderByExternalCreatedAt' => 'external_created_at',
                    'orderByCreatedAt' => 'created_at',
                    'orderByUpdatedAt' => 'updated_at',
                ],
                'queryIndexes' => [
                    'indexByFulfillmentWarehouseStockMovementId' => 'fulfillment_warehouse_stock_movement_id',
                    'indexByFulfillmentAccountId' => 'fulfillment_account_id',
                    'indexByExternalItemKey' => 'external_item_key',
                    'indexByExternalWarehouseKey' => 'external_warehouse_key',
                    'indexByExternalMovementKey' => 'external_movement_key',
                    'indexByRelatedKey' => 'related_key',
                ],
                'queryReturns' => [
                    'getFulfillmentWarehouseStockMovementIds' => ['fulfillment_warehouse_stock_movement_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getFulfillmentAccountIds' => ['fulfillment_account_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getExternalItemKeys' => ['external_item_key', FieldQueryBehavior::RETURN_COLUMN],
                    'getExternalWarehouseKeys' => ['external_warehouse_key', FieldQueryBehavior::RETURN_COLUMN],
                    'getExternalMovementKeys' => ['external_movement_key', FieldQueryBehavior::RETURN_COLUMN],
                    'getRelatedKeys' => ['related_key', FieldQueryBehavior::RETURN_COLUMN],
                ]
            ]
        ];
    }

}

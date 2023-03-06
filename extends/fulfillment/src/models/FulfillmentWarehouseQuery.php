<?php

namespace lujie\fulfillment\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use lujie\extend\constants\StatusConst;

/**
 * This is the ActiveQuery class for [[FulfillmentWarehouse]].
 *
 * @method FulfillmentWarehouseQuery id($id)
 * @method FulfillmentWarehouseQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method FulfillmentWarehouseQuery fulfillmentWarehouseId($fulfillmentWarehouseId)
 * @method FulfillmentWarehouseQuery fulfillmentAccountId($fulfillmentAccountId)
 * @method FulfillmentWarehouseQuery warehouseId($warehouseId)
 * @method FulfillmentWarehouseQuery externalWarehouseKey($externalWarehouseKey, bool|string $like = false)
 *
 * @method FulfillmentWarehouseQuery externalMovementAtBetween($from, $to = null)
 * @method FulfillmentWarehouseQuery createdAtBetween($from, $to = null)
 * @method FulfillmentWarehouseQuery updatedAtBetween($from, $to = null)
 * @method FulfillmentWarehouseQuery externalMovementAtBefore($externalMovementAtBefore)
 *
 * @method FulfillmentWarehouseQuery supportMovement()
 * @method FulfillmentWarehouseQuery mappedWarehouse()
 *
 * @method FulfillmentWarehouseQuery orderByFulfillmentWarehouseId($sort = SORT_ASC)
 * @method FulfillmentWarehouseQuery orderByFulfillmentAccountId($sort = SORT_ASC)
 * @method FulfillmentWarehouseQuery orderByWarehouseId($sort = SORT_ASC)
 * @method FulfillmentWarehouseQuery orderByExternalMovementAt($sort = SORT_ASC)
 * @method FulfillmentWarehouseQuery orderByCreatedAt($sort = SORT_ASC)
 * @method FulfillmentWarehouseQuery orderByUpdatedAt($sort = SORT_ASC)
 *
 * @method FulfillmentWarehouseQuery indexByFulfillmentWarehouseId()
 * @method FulfillmentWarehouseQuery indexByFulfillmentAccountId()
 * @method FulfillmentWarehouseQuery indexByWarehouseId()
 * @method FulfillmentWarehouseQuery indexByExternalWarehouseKey()
 *
 * @method array getFulfillmentWarehouseIds()
 * @method array getFulfillmentAccountIds()
 * @method array getWarehouseIds()
 * @method array getExternalWarehouseKeys()
 *
 * @method array|FulfillmentWarehouse[] all($db = null)
 * @method array|FulfillmentWarehouse|null one($db = null)
 * @method array|FulfillmentWarehouse[] each($batchSize = 100, $db = null)
 *
 * @see FulfillmentWarehouse
 */
class FulfillmentWarehouseQuery extends \yii\db\ActiveQuery
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
                    'fulfillmentWarehouseId' => 'fulfillment_warehouse_id',
                    'fulfillmentAccountId' => 'fulfillment_account_id',
                    'warehouseId' => 'warehouse_id',
                    'externalWarehouseKey' => ['external_warehouse_key' => FieldQueryBehavior::TYPE_STRING],
                    'externalMovementAtBetween' => ['external_movement_at' => 'BETWEEN'],
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                    'updatedAtBetween' => ['updated_at' => 'BETWEEN'],
                    'externalMovementAtBefore' => ['external_movement_at' => '<='],
                ],
                'queryConditions' => [
                    'supportMovement' => ['support_movement' => 1],
                    'mappedWarehouse' => ['>', 'warehouse_id', 0],
                ],
                'querySorts' => [
                    'orderByFulfillmentWarehouseId' => 'fulfillment_warehouse_id',
                    'orderByFulfillmentAccountId' => 'fulfillment_account_id',
                    'orderByWarehouseId' => 'warehouse_id',
                    'orderByExternalMovementAt' => 'external_movement_at',
                    'orderByCreatedAt' => 'created_at',
                    'orderByUpdatedAt' => 'updated_at',
                ],
                'queryIndexes' => [
                    'indexByFulfillmentWarehouseId' => 'fulfillment_warehouse_id',
                    'indexByFulfillmentAccountId' => 'fulfillment_account_id',
                    'indexByWarehouseId' => 'warehouse_id',
                    'indexByExternalWarehouseKey' => 'external_warehouse_key',
                ],
                'queryReturns' => [
                    'getFulfillmentWarehouseIds' => ['fulfillment_warehouse_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getFulfillmentAccountIds' => ['fulfillment_account_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getWarehouseIds' => ['warehouse_id', FieldQueryBehavior::RETURN_COLUMN, 'external_warehouse_key'],
                    'getExternalWarehouseKeys' => ['external_warehouse_key', FieldQueryBehavior::RETURN_COLUMN, 'warehouse_id'],
                ],
            ]
        ];
    }
}

<?php

namespace lujie\fulfillment\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[FulfillmentWarehouseStockMovement]].
 *
 * @method FulfillmentWarehouseStockMovementQuery id($id)
 * @method FulfillmentWarehouseStockMovementQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method FulfillmentWarehouseStockMovementQuery fulfillmentWarehouseStockMovementId($fulfillmentWarehouseStockMovementId)
 * @method FulfillmentWarehouseStockMovementQuery fulfillmentAccountId($fulfillmentAccountId)
 * @method FulfillmentWarehouseStockMovementQuery itemId($itemId)
 * @method FulfillmentWarehouseStockMovementQuery warehouseId($warehouseId)
 * @method FulfillmentWarehouseStockMovementQuery externalItemKey($externalItemKey)
 * @method FulfillmentWarehouseStockMovementQuery externalWarehouseKey($externalWarehouseKey)
 * @method FulfillmentWarehouseStockMovementQuery externalMovementKey($externalMovementKey)
 * @method FulfillmentWarehouseStockMovementQuery relatedType($relatedType)
 * @method FulfillmentWarehouseStockMovementQuery relatedKey($relatedKey)
 *
 * @method array getExternalMovementKey()
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
                    'itemId' => 'item_id',
                    'warehouseId' => 'warehouse_id',
                    'externalItemKey' => 'external_item_key',
                    'externalWarehouseKey' => 'external_warehouse_key',
                    'externalMovementKey' => 'external_movement_key',
                    'relatedType' => 'related_type',
                    'relatedKey' => 'related_key',
                ],
                'queryReturns' => [
                    'getExternalMovementKey' => ['external_movement_key', FieldQueryBehavior::RETURN_COLUMN],
                ]
            ]
        ];
    }

}

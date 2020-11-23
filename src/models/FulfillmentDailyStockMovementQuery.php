<?php

namespace lujie\fulfillment\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[FulfillmentDailyStockMovement]].
 *
 * @method FulfillmentDailyStockMovementQuery id($id)
 * @method FulfillmentDailyStockMovementQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method FulfillmentDailyStockMovementQuery fulfillmentDailyStockMovementId($fulfillmentDailyStockMovementId)
 * @method FulfillmentDailyStockMovementQuery fulfillmentAccountId($fulfillmentAccountId)
 * @method FulfillmentDailyStockMovementQuery itemId($itemId)
 * @method FulfillmentDailyStockMovementQuery warehouseId($warehouseId)
 * @method FulfillmentDailyStockMovementQuery externalItemKey($externalItemKey)
 * @method FulfillmentDailyStockMovementQuery externalWarehouseKey($externalWarehouseKey)
 * @method FulfillmentDailyStockMovementQuery movedDate($movedDate)
 * @method FulfillmentDailyStockMovementQuery movedDateFrom($movedDateFrom)
 * @method FulfillmentDailyStockMovementQuery movedDateTo($movedDateTo)
 *
 * @method string maxMovedDate()
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
                    'itemId' => 'item_id',
                    'warehouseId' => 'warehouse_id',
                    'externalItemKey' => 'external_item_key',
                    'externalWarehouseKey' => 'external_warehouse_key',
                    'movedDate' => 'moved_date',
                    'movedDateFrom' => ['moved_date' => '>='],
                    'movedDateTo' => ['moved_date' => '<='],
                ],
                'queryReturns' => [
                    'maxMovedDate' => ['moved_date', FieldQueryBehavior::RETURN_MAX],
                ],
            ]
        ];
    }

}

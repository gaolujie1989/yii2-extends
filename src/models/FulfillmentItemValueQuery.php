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
 * @method FulfillmentItemValueQuery itemId($itemId)
 * @method FulfillmentItemValueQuery warehouseId($warehouseId)
 * @method FulfillmentItemValueQuery externalItemKey($externalItemKey)
 * @method FulfillmentItemValueQuery externalWarehouseKey($externalWarehouseKey)
 * @method FulfillmentItemValueQuery valueDate($valueDate)
 * @method FulfillmentItemValueQuery valueDateBefore($valueDate)
 *
 * @method FulfillmentItemValueQuery orderByValueDate($sort = SORT_ASC)
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
                    'itemId' => 'item_id',
                    'warehouseId' => 'warehouse_id',
                    'externalItemKey' => 'external_item_key',
                    'externalWarehouseKey' => 'external_warehouse_key',
                    'valueDate' => 'value_date',
                    'valueDateBefore' => ['value_date' => '<'],
                ],
                'querySorts' => [
                    'orderByValueDate' => ['value_date']
                ]
            ]
        ];
    }
}

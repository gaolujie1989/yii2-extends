<?php

namespace lujie\fulfillment\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use PHPStan\Rules\Comparison\StrictComparisonOfDifferentTypesRule;

/**
 * This is the ActiveQuery class for [[FulfillmentDailyStock]].
 *
 * @method FulfillmentDailyStockQuery id($id)
 * @method FulfillmentDailyStockQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method FulfillmentDailyStockQuery fulfillmentDailyStockId($fulfillmentDailyStockId)
 * @method FulfillmentDailyStockQuery fulfillmentAccountId($fulfillmentAccountId)
 * @method FulfillmentDailyStockQuery itemId($itemId)
 * @method FulfillmentDailyStockQuery warehouseId($warehouseId)
 * @method FulfillmentDailyStockQuery externalItemKey($externalItemKey)
 * @method FulfillmentDailyStockQuery externalWarehouseKey($externalWarehouseKey)
 * @method FulfillmentDailyStockQuery stockDate($stockDate)
 *
 * @method string maxStockDate()
 *
 * @method array|FulfillmentDailyStock[] all($db = null)
 * @method array|FulfillmentDailyStock|null one($db = null)
 * @method array|FulfillmentDailyStock[] each($batchSize = 100, $db = null)
 *
 * @see FulfillmentDailyStock
 */
class FulfillmentDailyStockQuery extends \yii\db\ActiveQuery
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
                    'fulfillmentDailyStockId' => 'fulfillment_daily_stock_id',
                    'fulfillmentAccountId' => 'fulfillment_account_id',
                    'itemId' => 'item_id',
                    'warehouseId' => 'warehouse_id',
                    'externalItemKey' => 'external_item_key',
                    'externalWarehouseKey' => 'external_warehouse_key',
                    'stockDate' => 'stock_date',
                ],
                'queryReturns' => [
                    'maxStockDate' => ['stock_date', FieldQueryBehavior::RETURN_MAX],
                ],
            ]
        ];
    }
}

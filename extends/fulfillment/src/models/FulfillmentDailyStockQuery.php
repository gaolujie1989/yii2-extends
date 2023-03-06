<?php

namespace lujie\fulfillment\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[FulfillmentDailyStock]].
 *
 * @method FulfillmentDailyStockQuery id($id)
 * @method FulfillmentDailyStockQuery orderById($sort = SORT_ASC)
 * @method FulfillmentDailyStockQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method FulfillmentDailyStockQuery fulfillmentDailyStockId($fulfillmentDailyStockId)
 * @method FulfillmentDailyStockQuery fulfillmentAccountId($fulfillmentAccountId)
 * @method FulfillmentDailyStockQuery externalItemKey($externalItemKey, bool|string $like = false)
 * @method FulfillmentDailyStockQuery externalWarehouseKey($externalWarehouseKey, bool|string $like = false)
 * @method FulfillmentDailyStockQuery stockDate($stockDate)
 *
 * @method FulfillmentDailyStockQuery stockDateBetween($from, $to = null)
 * @method FulfillmentDailyStockQuery createdAtBetween($from, $to = null)
 * @method FulfillmentDailyStockQuery updatedAtBetween($from, $to = null)
 *
 * @method FulfillmentDailyStockQuery orderByFulfillmentDailyStockId($sort = SORT_ASC)
 * @method FulfillmentDailyStockQuery orderByFulfillmentAccountId($sort = SORT_ASC)
 * @method FulfillmentDailyStockQuery orderByStockDate($sort = SORT_ASC)
 * @method FulfillmentDailyStockQuery orderByCreatedAt($sort = SORT_ASC)
 * @method FulfillmentDailyStockQuery orderByUpdatedAt($sort = SORT_ASC)
 *
 * @method FulfillmentDailyStockQuery indexByFulfillmentDailyStockId()
 * @method FulfillmentDailyStockQuery indexByFulfillmentAccountId()
 * @method FulfillmentDailyStockQuery indexByExternalItemKey()
 * @method FulfillmentDailyStockQuery indexByExternalWarehouseKey()
 *
 * @method array getFulfillmentDailyStockIds()
 * @method array getFulfillmentAccountIds()
 * @method array getExternalItemKeys()
 * @method array getExternalWarehouseKeys()
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
                    'externalItemKey' => ['external_item_key' => FieldQueryBehavior::TYPE_STRING],
                    'externalWarehouseKey' => ['external_warehouse_key' => FieldQueryBehavior::TYPE_STRING],
                    'stockDate' => 'stock_date',
                    'stockDateBetween' => ['stock_date' => 'BETWEEN'],
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                    'updatedAtBetween' => ['updated_at' => 'BETWEEN'],
                ],
                'queryConditions' => [],
                'querySorts' => [
                    'orderByFulfillmentDailyStockId' => 'fulfillment_daily_stock_id',
                    'orderByFulfillmentAccountId' => 'fulfillment_account_id',
                    'orderByStockDate' => 'stock_date',
                    'orderByCreatedAt' => 'created_at',
                    'orderByUpdatedAt' => 'updated_at',
                ],
                'queryIndexes' => [
                    'indexByFulfillmentDailyStockId' => 'fulfillment_daily_stock_id',
                    'indexByFulfillmentAccountId' => 'fulfillment_account_id',
                    'indexByExternalItemKey' => 'external_item_key',
                    'indexByExternalWarehouseKey' => 'external_warehouse_key',
                ],
                'queryReturns' => [
                    'getFulfillmentDailyStockIds' => ['fulfillment_daily_stock_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getFulfillmentAccountIds' => ['fulfillment_account_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getExternalItemKeys' => ['external_item_key', FieldQueryBehavior::RETURN_COLUMN],
                    'getExternalWarehouseKeys' => ['external_warehouse_key', FieldQueryBehavior::RETURN_COLUMN],
                    'maxStockDate' => ['stock_date', FieldQueryBehavior::RETURN_MAX],
                ]
            ]
        ];
    }

}

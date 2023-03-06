<?php

namespace lujie\fulfillment\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the ActiveQuery class for [[FulfillmentWarehouseStock]].
 *
 * @method FulfillmentWarehouseStockQuery id($id)
 * @method FulfillmentWarehouseStockQuery orderById($sort = SORT_ASC)
 * @method FulfillmentWarehouseStockQuery indexById()
 * @method int getId()
 * @method array getIds()
 *
 * @method FulfillmentWarehouseStockQuery fulfillmentWarehouseStockId($fulfillmentWarehouseStockId)
 * @method FulfillmentWarehouseStockQuery fulfillmentAccountId($fulfillmentAccountId)
 * @method FulfillmentWarehouseStockQuery externalItemKey($externalItemKey, bool|string $like = false)
 * @method FulfillmentWarehouseStockQuery externalWarehouseKey($externalWarehouseKey, bool|string $like = false)
 *
 * @method FulfillmentWarehouseStockQuery externalUpdatedAtBetween($from, $to = null)
 * @method FulfillmentWarehouseStockQuery stockPulledAtBetween($from, $to = null)
 * @method FulfillmentWarehouseStockQuery createdAtBetween($from, $to = null)
 * @method FulfillmentWarehouseStockQuery updatedAtBetween($from, $to = null)
 *
 * @method FulfillmentWarehouseStockQuery orderByFulfillmentWarehouseStockId($sort = SORT_ASC)
 * @method FulfillmentWarehouseStockQuery orderByFulfillmentAccountId($sort = SORT_ASC)
 * @method FulfillmentWarehouseStockQuery orderByExternalUpdatedAt($sort = SORT_ASC)
 * @method FulfillmentWarehouseStockQuery orderByStockPulledAt($sort = SORT_ASC)
 * @method FulfillmentWarehouseStockQuery orderByCreatedAt($sort = SORT_ASC)
 * @method FulfillmentWarehouseStockQuery orderByUpdatedAt($sort = SORT_ASC)
 *
 * @method FulfillmentWarehouseStockQuery indexByFulfillmentWarehouseStockId()
 * @method FulfillmentWarehouseStockQuery indexByFulfillmentAccountId()
 * @method FulfillmentWarehouseStockQuery indexByExternalItemKey()
 * @method FulfillmentWarehouseStockQuery indexByExternalWarehouseKey()
 *
 * @method array getFulfillmentWarehouseStockIds()
 * @method array getFulfillmentAccountIds()
 * @method array getExternalItemKeys()
 * @method array getExternalWarehouseKeys()
 *
 * @method array|FulfillmentWarehouseStock[] all($db = null)
 * @method array|FulfillmentWarehouseStock|null one($db = null)
 * @method array|FulfillmentWarehouseStock[] each($batchSize = 100, $db = null)
 *
 * @see FulfillmentWarehouseStock
 */
class FulfillmentWarehouseStockQuery extends \yii\db\ActiveQuery
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
                    'fulfillmentWarehouseStockId' => 'fulfillment_warehouse_stock_id',
                    'fulfillmentAccountId' => 'fulfillment_account_id',
                    'externalItemKey' => ['external_item_key' => FieldQueryBehavior::TYPE_STRING],
                    'externalWarehouseKey' => ['external_warehouse_key' => FieldQueryBehavior::TYPE_STRING],
                    'externalUpdatedAtBetween' => ['external_updated_at' => 'BETWEEN'],
                    'stockPulledAtBetween' => ['stock_pulled_at' => 'BETWEEN'],
                    'createdAtBetween' => ['created_at' => 'BETWEEN'],
                    'updatedAtBetween' => ['updated_at' => 'BETWEEN'],
                ],
                'queryConditions' => [],
                'querySorts' => [
                    'orderByFulfillmentWarehouseStockId' => 'fulfillment_warehouse_stock_id',
                    'orderByFulfillmentAccountId' => 'fulfillment_account_id',
                    'orderByExternalUpdatedAt' => 'external_updated_at',
                    'orderByStockPulledAt' => 'stock_pulled_at',
                    'orderByCreatedAt' => 'created_at',
                    'orderByUpdatedAt' => 'updated_at',
                ],
                'queryIndexes' => [
                    'indexByFulfillmentWarehouseStockId' => 'fulfillment_warehouse_stock_id',
                    'indexByFulfillmentAccountId' => 'fulfillment_account_id',
                    'indexByExternalItemKey' => 'external_item_key',
                    'indexByExternalWarehouseKey' => 'external_warehouse_key',
                ],
                'queryReturns' => [
                    'getFulfillmentWarehouseStockIds' => ['fulfillment_warehouse_stock_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getFulfillmentAccountIds' => ['fulfillment_account_id', FieldQueryBehavior::RETURN_COLUMN],
                    'getExternalItemKeys' => ['external_item_key', FieldQueryBehavior::RETURN_COLUMN],
                    'getExternalWarehouseKeys' => ['external_warehouse_key', FieldQueryBehavior::RETURN_COLUMN],
                ]
            ]
        ];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function getWarehouseStocks(): array
    {
        $stocks = $this->innerJoinWith(['fulfillmentItem fi', 'fulfillmentWarehouse fw'], false)
            ->select(['fi.item_id', 'fw.warehouse_id', 'stock_qty'])
            ->asArray()
            ->all();
        return ArrayHelper::map($stocks, 'warehouse_id', 'stock_qty', 'item_id');
    }
}

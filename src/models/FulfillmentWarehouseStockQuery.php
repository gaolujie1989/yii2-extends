<?php

namespace lujie\fulfillment\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the ActiveQuery class for [[FulfillmentWarehouseStock]].
 *
 * @method FulfillmentWarehouseStockQuery id($id)
 * @method FulfillmentWarehouseStockQuery orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
 * @method FulfillmentWarehouseStockQuery fulfillmentWarehouseStockId($fulfillmentWarehouseStockId)
 * @method FulfillmentWarehouseStockQuery fulfillmentAccountId($fulfillmentAccountId)
 * @method FulfillmentWarehouseStockQuery itemId($itemId)
 * @method FulfillmentWarehouseStockQuery warehouseId($warehouseId)
 * @method FulfillmentWarehouseStockQuery externalItemKey($externalItemKey)
 * @method FulfillmentWarehouseStockQuery externalWarehouseKey($externalWarehouseKey)
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
                    'itemId' => 'item_id',
                    'warehouseId' => 'warehouse_id',
                    'externalItemKey' => 'external_item_key',
                    'externalWarehouseKey' => 'external_warehouse_key',
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
        $barcodes = $this->select(['item_id', 'warehouse_id', 'stock_qty'])->asArray()->all();
        return ArrayHelper::map($barcodes, 'warehouse_id', 'stock_qty', 'item_id');
    }
}

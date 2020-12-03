<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\searches;

use lujie\fulfillment\models\FulfillmentWarehouseStock;
use lujie\fulfillment\models\FulfillmentWarehouseStockQuery;

/**
 * Class FulfillmentWarehouseStockSearch
 * @package lujie\fulfillment\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FulfillmentWarehouseStockSearch extends FulfillmentWarehouseStock
{
    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['fulfillment_account_id', 'item_id', 'warehouse_id', 'external_item_key', 'external_warehouse_key'], 'safe']
        ];
    }

    /**
     * @return FulfillmentWarehouseStockQuery
     * @inheritdoc
     */
    public function query(): FulfillmentWarehouseStockQuery
    {
        return static::find()
            ->andFilterWhere([
                'fulfillment_account_id' => $this->fulfillment_account_id,
                'item_id' => $this->item_id,
                'warehouse_id' => $this->warehouse_id,
                'external_item_key' => $this->external_item_key,
                'external_warehouse_key' => $this->external_warehouse_key,
            ]);
    }
}

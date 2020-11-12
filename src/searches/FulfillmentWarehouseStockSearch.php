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
            [['fulfillment_account_id', 'fulfillment_item_id', 'fulfillment_warehouse_id',
                'item_id', 'warehouse_id', 'external_item_key', 'external_warehouse_key'], 'safe']
        ];
    }

    /**
     * @return FulfillmentWarehouseStockQuery
     * @inheritdoc
     */
    public function query(): FulfillmentWarehouseStockQuery
    {
        return static::find()->alias('fws')
            ->andFilterWhere([
                'fws.fulfillment_account_id' => $this->fulfillment_account_id,
                'fws.fulfillment_item_id' => $this->fulfillment_item_id,
                'fws.fulfillment_warehouse_id' => $this->fulfillment_warehouse_id,
                'fws.item_id' => $this->item_id,
                'fws.warehouse_id' => $this->warehouse_id,
                'fws.external_item_key' => $this->external_item_key,
                'fws.external_warehouse_key' => $this->external_warehouse_key,
            ]);
    }
}

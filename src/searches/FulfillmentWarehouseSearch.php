<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\searches;

use lujie\fulfillment\models\FulfillmentAccountQuery;
use lujie\fulfillment\models\FulfillmentWarehouse;
use lujie\fulfillment\models\FulfillmentWarehouseQuery;

/**
 * Class FulfillmentItemSearch
 * @package lujie\fulfillment\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FulfillmentWarehouseSearch extends FulfillmentWarehouse
{
    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['fulfillment_account_id', 'warehouse_id', 'external_warehouse_id', 'external_warehouse_name'], 'safe']
        ];
    }

    /**
     * @return FulfillmentWarehouseQuery
     * @inheritdoc
     */
    public function query(): FulfillmentWarehouseQuery
    {
        return static::find()->with('fulfillmentAccount')
            ->andFilterWhere(['LIKE', 'external_warehouse_name', $this->external_warehouse_name])
            ->andFilterWhere([
                'fulfillment_account_id' => $this->fulfillment_account_id,
                'warehouse_id' => $this->warehouse_id,
                'external_warehouse_id' => $this->external_warehouse_id
            ]);
    }

    /**
     * @return FulfillmentAccountQuery
     * @inheritdoc
     */
    public function getFulfillmentAccount(): FulfillmentAccountQuery
    {
        return parent::getFulfillmentAccount()->select(['fulfillment_account_id', 'name']);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        return array_merge(parent::fields(), [
            'fulfillment_account_name' => 'fulfillmentAccount.name',
        ]);
    }
}

<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\searches;

use lujie\alias\behaviors\TimestampAliasBehavior;
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
            [['fulfillment_account_id', 'warehouse_id', 'external_warehouse_key'], 'safe']
        ];
    }

    /**
     * @return FulfillmentWarehouseQuery
     * @inheritdoc
     */
    public function query(): FulfillmentWarehouseQuery
    {
        return static::find()
            ->andFilterWhere([
                'fulfillment_account_id' => $this->fulfillment_account_id,
                'warehouse_id' => $this->warehouse_id,
                'external_warehouse_key' => $this->external_warehouse_key
            ]);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'tsAlias' => [
                'class' => TimestampAliasBehavior::class,
                'aliasProperties' => [
                    'external_movement_time' => 'external_movement_at',
                ]
            ]
        ]);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        return array_merge(parent::fields(), [
            'external_movement_time' => 'external_movement_time',
        ]);
    }
}

<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\models;

use yii\db\ActiveQuery;

/**
 * Trait FulfillmentItemRelationTrait
 *
 * @property FulfillmentWarehouse $fulfillmentWarehouse
 *
 * @package lujie\fulfillment\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait FulfillmentWarehouseRelationTrait
{
    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getFulfillmentWarehouse(): ActiveQuery
    {
        return $this->hasOne(FulfillmentWarehouse::class, ['external_warehouse_key' => 'external_warehouse_key']);
    }
}
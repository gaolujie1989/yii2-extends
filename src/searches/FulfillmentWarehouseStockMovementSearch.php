<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\searches;

use lujie\extend\db\SearchTrait;
use lujie\fulfillment\models\FulfillmentWarehouseStockMovement;
use yii\db\ActiveQueryInterface;

/**
 * Class FulfillmentWarehouseStockSearch
 * @package lujie\fulfillment\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FulfillmentWarehouseStockMovementSearch extends FulfillmentWarehouseStockMovement
{
    use SearchTrait, FulfillmentItemWarehouseSearchTrait;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return array_merge($this->searchRules(), $this->searchItemWarehouseRules());
    }

    /**
     * @return ActiveQueryInterface
     * @inheritdoc
     */
    public function query(): ActiveQueryInterface
    {
        $query = $this->searchQuery();
        $this->searchItemWarehouseQuery($query);
        return $query;
    }
}

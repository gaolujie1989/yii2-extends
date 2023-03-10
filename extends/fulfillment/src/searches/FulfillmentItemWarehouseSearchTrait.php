<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\searches;

use lujie\extend\helpers\QueryHelper;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;

/**
 * Trait FulfillmentItemWarehouseSearchTrait
 * @package lujie\fulfillment\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait FulfillmentItemWarehouseSearchTrait
{
    public $item_id;

    public $warehouse_id;

    public function searchItemWarehouseRules(): array
    {
        return [
            [['item_id', 'warehouse_id'], 'safe'],
        ];
    }

    /**
     * @param ActiveQueryInterface|ActiveQuery $query
     * @param bool $eagerLoading
     * @inheritdoc
     */
    public function searchItemWarehouseQuery(ActiveQueryInterface $query, bool $eagerLoading = false): void
    {
        if ($this->item_id) {
            $query->innerJoinWith(['fulfillmentItem fi'], $eagerLoading);
            QueryHelper::filterValue($query, $this->getAttributes(['item_id']), false, 'fi');
        }
        if ($this->warehouse_id) {
            $query->innerJoinWith(['fulfillmentWarehouse fw'], $eagerLoading);
            QueryHelper::filterValue($query, $this->getAttributes(['warehouse_id']), false, 'fw');
        }
    }
}
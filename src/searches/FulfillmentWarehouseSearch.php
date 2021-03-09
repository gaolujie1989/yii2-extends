<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\searches;

use lujie\extend\base\SearchTrait;
use lujie\extend\helpers\ModelHelper;
use lujie\fulfillment\models\FulfillmentWarehouse;

/**
 * Class FulfillmentItemSearch
 * @package lujie\fulfillment\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FulfillmentWarehouseSearch extends FulfillmentWarehouse
{
    use SearchTrait;

    /**
     * @param array $row
     * @return mixed
     * @throws \Exception
     * @inheritdoc
     */
    public static function prepareArray(array $row): array
    {
        $alias = ['external_movement_time' => 'external_movement_at'];
        return ModelHelper::prepareArray($row, static::class, $alias);
    }
}

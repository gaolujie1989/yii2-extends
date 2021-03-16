<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\searches;

use lujie\extend\db\SearchTrait;
use lujie\fulfillment\models\FulfillmentWarehouseStock;

/**
 * Class FulfillmentWarehouseStockSearch
 * @package lujie\fulfillment\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FulfillmentWarehouseStockSearch extends FulfillmentWarehouseStock
{
    use SearchTrait;
}

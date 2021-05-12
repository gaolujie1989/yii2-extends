<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\controllers\backend;

use lujie\extend\rest\ActiveController;
use lujie\fulfillment\models\FulfillmentWarehouseStock;

/**
 * Class FulfillmentWarehouseStockController
 * @package lujie\fulfillment\controllers\backend
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FulfillmentWarehouseStockController extends ActiveController
{
    public $modelClass = FulfillmentWarehouseStock::class;
}
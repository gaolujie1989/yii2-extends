<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\controllers\backend;

use lujie\extend\rest\ActiveController;
use lujie\fulfillment\models\FulfillmentWarehouse;

/**
 * Class FulfillmentWarehouseController
 * @package lujie\fulfillment\controllers\backend
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FulfillmentWarehouseController extends ActiveController
{
    public $modelClass = FulfillmentWarehouse::class;
}
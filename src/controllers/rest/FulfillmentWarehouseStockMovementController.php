<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\controllers\backend;

use lujie\extend\rest\ActiveController;
use lujie\fulfillment\models\FulfillmentWarehouseStockMovement;

/**
 * Class FulfillmentWarehouseStockMovementController
 * @package lujie\fulfillment\controllers\backend
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FulfillmentWarehouseStockMovementController extends ActiveController
{
    public $modelClass = FulfillmentWarehouseStockMovement::class;
}
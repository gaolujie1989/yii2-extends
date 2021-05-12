<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\controllers\backend;

use lujie\extend\rest\ActiveController;
use lujie\fulfillment\models\FulfillmentOrder;

/**
 * Class FulfillmentOrderController
 * @package lujie\fulfillment\controllers\backend
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FulfillmentOrderController extends ActiveController
{
    public $modelClass = FulfillmentOrder::class;
}
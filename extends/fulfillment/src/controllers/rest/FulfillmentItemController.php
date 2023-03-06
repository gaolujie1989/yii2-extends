<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\controllers\rest;

use lujie\extend\rest\ActiveController;
use lujie\fulfillment\models\FulfillmentItem;

/**
 * Class FulfillmentItemController
 * @package lujie\fulfillment\controllers\backend
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FulfillmentItemController extends ActiveController
{
    public $modelClass = FulfillmentItem::class;
}
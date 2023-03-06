<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\shipping\controllers\rest;

use lujie\common\shipping\models\ShippingRule;
use lujie\extend\rest\ActiveController;

/**
 * Class ShippingRuleController
 * @package lujie\common\shipping\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingRuleController extends ActiveController
{
    public $modelClass = ShippingRule::class;
}
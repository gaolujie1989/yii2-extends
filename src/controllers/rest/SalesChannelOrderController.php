<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace kiwi\sales\channel\controllers\rest;

use lujie\extend\rest\ActiveController;
use lujie\sales\channel\models\SalesChannelOrder;

/**
 * Class SalesChannelOrderController
 * @package kiwi\sales\channel\controllers\backend
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SalesChannelOrderController extends ActiveController
{
    public $modelClass = SalesChannelOrder::class;
}
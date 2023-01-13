<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\controllers\rest;

use lujie\extend\rest\ActiveController;
use lujie\sales\channel\models\SalesChannelItem;

/**
 * Class SalesChannelOrderController
 * @package kiwi\sales\channel\controllers\backend
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SalesChannelItemController extends ActiveController
{
    public $modelClass = SalesChannelItem::class;
}
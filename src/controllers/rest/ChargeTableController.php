<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\controllers\rest;


use lujie\charging\models\ChargeTable;
use lujie\extend\rest\ActiveController;

/**
 * Class ChargeTableController
 * @package lujie\charging\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ChargeTableController extends ActiveController
{
    public $modelClass = ChargeTable::class;
}
<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option\controllers\rest;

use lujie\extend\rest\ActiveController;
use lujie\common\option\models\Option;

/**
 * Class ModelOptionController
 * @package lujie\common\option\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OptionController extends ActiveController
{
    public $modelClass = Option::class;
}

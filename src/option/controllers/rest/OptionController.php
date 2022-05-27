<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option\controllers\rest;

use lujie\common\option\models\Option;
use lujie\extend\rest\ActiveController;

/**
 * Class CommentController
 * @package lujie\common\comment\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OptionController extends ActiveController
{
    public $modelClass = Option::class;
}
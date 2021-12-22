<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\category\controllers\rest;

use lujie\common\category\models\Category;
use lujie\extend\rest\ActiveController;

/**
 * Class CategoryController
 * @package lujie\common\category\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CategoryController extends ActiveController
{
    public $modelClass = Category::class;
}
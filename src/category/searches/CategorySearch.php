<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\category\searches;

use lujie\common\category\models\Category;
use lujie\extend\db\SearchTrait;

/**
 * Class CategorySearch
 * @package lujie\common\category\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CategorySearch extends Category
{
    use SearchTrait;
}
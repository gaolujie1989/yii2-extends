<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\searches;

use lujie\extend\db\SearchTrait;
use lujie\sales\channel\models\OttoCategory;

/**
 * Class OttoCategorySearch
 * @package lujie\sales\channel\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OttoCategorySearch extends OttoCategory
{
    use SearchTrait;
}
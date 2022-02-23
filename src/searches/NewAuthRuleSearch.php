<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\searches;

use lujie\auth\models\NewAuthRule;
use lujie\extend\db\SearchTrait;

/**
 * Class NewAuthRuleSearch
 * @package lujie\auth\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class NewAuthRuleSearch extends NewAuthRule
{
    use SearchTrait;
}
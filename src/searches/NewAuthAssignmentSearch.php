<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\searches;

use lujie\auth\models\NewAuthAssignment;
use lujie\extend\db\SearchTrait;

/**
 * Class NewAuthAssignmentSearch
 * @package lujie\auth\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class NewAuthAssignmentSearch extends NewAuthAssignment
{
    use SearchTrait;
}
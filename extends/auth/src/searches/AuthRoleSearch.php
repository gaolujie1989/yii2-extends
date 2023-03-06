<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\searches;

use yii\rbac\Item;

/**
 * Class AuthItemSearch
 * @package lujie\auth\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AuthRoleSearch extends AuthItemSearch
{
    public const TYPE = Item::TYPE_ROLE;
}
<?php

namespace lujie\user\searches;

use lujie\extend\db\SearchTrait;
use lujie\user\models\UserAccessToken;

/**
 * Class UserAccessTokenSearch
 * @package lujie\user\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UserAccessTokenSearch extends UserAccessToken
{
    use SearchTrait;
}

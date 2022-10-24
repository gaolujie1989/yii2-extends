<?php

namespace lujie\common\oauth\searches;

use lujie\common\oauth\models\AuthToken;
use lujie\extend\db\SearchTrait;

/**
 * Class AuthTokenSearch
 * @package lujie\common\oauth\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AuthTokenSearch extends AuthToken
{
    use SearchTrait;
}

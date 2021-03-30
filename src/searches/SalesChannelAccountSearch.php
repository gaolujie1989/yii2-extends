<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\searches;

use lujie\common\account\searches\AccountSearch;
use lujie\sales\channel\models\SalesChannelAccount;

/**
 * Class SalesChannelAccountSearch
 * @package lujie\sales\channel\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SalesChannelAccountSearch extends AccountSearch
{
    public const MODEL_TYPE = SalesChannelAccount::MODEL_TYPE;
}

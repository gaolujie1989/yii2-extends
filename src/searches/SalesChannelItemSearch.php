<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\searches;

use lujie\extend\db\SearchTrait;
use lujie\sales\channel\models\SalesChannelItem;

/**
 * Class SalesChannelOrderSearch
 * @package lujie\sales\channel\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SalesChannelItemSearch extends SalesChannelItem
{
    use SearchTrait;
}

<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\searches;

use lujie\extend\base\SearchTrait;
use lujie\sales\channel\models\SalesChannelOrder;

/**
 * Class SalesChannelOrderSearch
 * @package lujie\sales\channel\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SalesChannelOrderSearch extends SalesChannelOrder
{
    use SearchTrait;
}
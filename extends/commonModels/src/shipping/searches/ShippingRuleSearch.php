<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\shipping\searches;


use lujie\common\shipping\models\ShippingRule;
use lujie\extend\db\SearchTrait;

/**
 * Class ShippingRuleSearch
 * @package lujie\common\shipping\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingRuleSearch extends ShippingRule
{
    use SearchTrait;
}
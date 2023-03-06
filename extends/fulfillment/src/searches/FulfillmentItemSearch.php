<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\searches;

use lujie\extend\db\SearchTrait;
use lujie\fulfillment\models\FulfillmentItem;

/**
 * Class FulfillmentItemSearch
 * @package lujie\fulfillment\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FulfillmentItemSearch extends FulfillmentItem
{
    use SearchTrait;
}

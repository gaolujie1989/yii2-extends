<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\searches;

use lujie\common\account\searches\AccountSearch;
use lujie\fulfillment\models\FulfillmentAccount;

/**
 * Class FulfillmentAccountSearch
 * @package lujie\fulfillment\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FulfillmentAccountSearch extends AccountSearch
{
    public const MODEL_TYPE = FulfillmentAccount::MODEL_TYPE;
}

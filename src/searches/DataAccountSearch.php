<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\searches;


use lujie\common\account\searches\AccountSearch;
use lujie\data\recording\models\DataAccount;

/**
 * Class DataAccountSearch
 * @package lujie\data\recording\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DataAccountSearch extends AccountSearch
{
    public const MODEL_TYPE = DataAccount::MODEL_TYPE;
}
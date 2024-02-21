<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\common\history\searches;

use lujie\common\history\models\ModelHistory;
use lujie\extend\db\SearchTrait;
use lujie\extend\helpers\QueryHelper;
use yii\db\ActiveQueryInterface;

/**
 * Class ModelHistorySearch
 * @package lujie\common\history\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ModelHistorySearch extends ModelHistory
{
    use ModelHistorySearchTrait;
}

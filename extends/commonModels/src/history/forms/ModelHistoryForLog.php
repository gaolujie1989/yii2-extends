<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\common\history\forms;

use lujie\common\history\models\ModelHistory;
use lujie\extend\db\FormTrait;

/**
 * Class ModelHistoryForLog
 * @package lujie\common\history\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ModelHistoryForLog extends ModelHistory
{
    use ModelHistoryForLogTrait;
}

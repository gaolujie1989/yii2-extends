<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\forms;


use lujie\common\account\forms\AccountForm;
use lujie\data\recording\models\DataAccount;

/**
 * Class DataAccountForm
 * @package lujie\data\recording\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DataAccountForm extends AccountForm
{
    public const MODEL_TYPE = DataAccount::MODEL_TYPE;
}
<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\forms;

use lujie\auth\models\NewAuthRule;
use lujie\extend\db\FormTrait;

/**
 * Class NewAuthRuleForm
 * @package lujie\auth\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class NewAuthRuleForm extends NewAuthRule
{
    use FormTrait;
}
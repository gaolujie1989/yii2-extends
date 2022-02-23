<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\forms;

use lujie\auth\models\NewAuthAssignment;
use lujie\extend\db\FormTrait;

/**
 * Class NewAuthAssignmentForm
 * @package lujie\auth\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class NewAuthAssignmentForm extends NewAuthAssignment
{
    use FormTrait;
}
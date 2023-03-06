<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\shipping\forms;


use lujie\common\shipping\models\ShippingRule;
use lujie\extend\db\FormTrait;

/**
 * Class ShippingRuleForm
 * @package lujie\common\shipping\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingRuleForm extends ShippingRule
{
    use FormTrait;
}
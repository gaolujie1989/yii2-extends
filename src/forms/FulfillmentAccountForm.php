<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\forms;

use lujie\common\account\forms\AccountForm;
use lujie\fulfillment\models\FulfillmentAccount;

/**
 * Class FulfillmentAccountForm
 * @package lujie\fulfillment\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FulfillmentAccountForm extends AccountForm
{
    public const MODEL_TYPE = FulfillmentAccount::MODEL_TYPE;
}
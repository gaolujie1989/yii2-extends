<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\forms;


use lujie\common\account\forms\AccountForm;
use lujie\sales\channel\models\SalesChannelAccount;

/**
 * Class SalesChannelAccountForm
 * @package lujie\sales\channel\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SalesChannelAccountForm extends AccountForm
{
    public const MODEL_TYPE = SalesChannelAccount::MODEL_TYPE;
}
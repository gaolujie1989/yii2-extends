<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\account\controllers\rest;

use lujie\common\account\models\Account;
use lujie\extend\rest\ActiveController;

/**
 * Class AccountController
 * @package lujie\common\account\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AccountController extends ActiveController
{
    public $modelClass = Account::class;
}

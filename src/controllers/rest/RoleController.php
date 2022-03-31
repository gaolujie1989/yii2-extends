<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\controllers\rest;

use lujie\auth\forms\AuthRoleForm;
use lujie\auth\models\AuthItem;
use lujie\auth\searches\AuthRoleSearch;
use lujie\extend\rest\ActiveController;

/**
 * Class RoleController
 * @package lujie\auth\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RoleController extends ActiveController
{
    public $modelClass = AuthItem::class;

    public $formClass = AuthRoleForm::class;

    public $searchClass = AuthRoleSearch::class;
}
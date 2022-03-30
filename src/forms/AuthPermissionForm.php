<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\forms;

use lujie\auth\models\AuthItem;
use lujie\extend\db\FormTrait;
use lujie\extend\helpers\ModelHelper;
use yii\di\Instance;
use yii\mongodb\rbac\MongoDbManager;
use yii\rbac\BaseManager;
use yii\rbac\DbManager;
use yii\rbac\Item;

/**
 * Class AuthItemForm
 * @package lujie\auth\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AuthPermissionForm extends AuthItemForm
{
    public const TYPE = Item::TYPE_PERMISSION;
}
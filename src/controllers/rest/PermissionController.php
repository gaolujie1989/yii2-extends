<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\controllers\rest;

use lujie\auth\forms\AuthPermissionForm;
use lujie\auth\helpers\AuthHelper;
use lujie\auth\models\AuthItem;
use lujie\auth\searches\AuthPermissionSearch;
use lujie\extend\rest\ActiveController;
use Yii;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\rbac\DbManager;
use yii\rbac\ManagerInterface;

/**
 * Class RoleController
 * @package lujie\auth\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PermissionController extends ActiveController
{
    public $modelClass = AuthItem::class;

    public $formClass = AuthPermissionForm::class;

    public $searchClass = AuthPermissionSearch::class;

    /**
     * @var DbManager
     */
    public $authManager = 'authManager';

    /**
     * @var string[]
     */
    public $treeChildrenKeys = ['modules', 'groups', 'permissions'];

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actionTree(): array
    {
        $this->authManager = Instance::ensure($this->authManager, DbManager::class);
        return AuthHelper::generatePermissionTree($this->authManager, $this->treeChildrenKeys);
    }
}
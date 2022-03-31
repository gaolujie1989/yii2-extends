<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\controllers\console;

use lujie\auth\helpers\AuthHelper;
use Yii;
use yii\console\Controller;
use yii\di\Instance;
use yii\helpers\VarDumper;
use yii\rbac\BaseManager;

/**
 * Class PermissionController
 * @package lujie\auth\controllers\console
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PermissionController extends Controller
{
    /**
     * @var BaseManager
     */
    public $authManager = 'authManager';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->authManager = Instance::ensure($this->authManager, BaseManager::class);
    }

    /**
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function actionSync(): void
    {
        AuthHelper::syncPermissions(Yii::$app->params['permissions'] ?? [], $this->authManager);
    }

    /**
     * @param string $module
     * @inheritdoc
     */
    public function actionGenerate(string $module): void
    {
        $permissions = AuthHelper::generatePermissions($module);
        echo "\n", VarDumper::export($permissions), "\n";
    }
}
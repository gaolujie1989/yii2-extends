<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\controllers\console;

use lujie\auth\helpers\AuthHelper;
use Yii;
use yii\console\Controller;
use yii\di\Instance;
use yii\rbac\ManagerInterface;

/**
 * Class AuthController
 * @package lujie\auth\controllers\console
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AuthController extends Controller
{
    /**
     * @var ManagerInterface
     */
    public $authManager = 'authManager';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->authManager = Instance::ensure($this->authManager, ManagerInterface::class);
    }

    /**
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function actionSyncPermissions(): void
    {
        AuthHelper::syncPermissions(Yii::$app->params['permissions'] ?? [], $this->authManager);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actionSyncRules(): void
    {
        AuthHelper::syncRules(Yii::$app->params['rules'] ?? [], $this->authManager);
    }
}
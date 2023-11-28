<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\controllers\console;

use yii\console\Controller;
use yii\di\Instance;
use yii\rbac\ManagerInterface;

/**
 * Class AuthController
 * @package lujie\auth\controllers\console
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseAuthController extends Controller
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
}

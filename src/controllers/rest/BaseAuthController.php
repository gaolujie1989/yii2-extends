<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\controllers\rest;


use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\rbac\ManagerInterface;
use yii\rest\Controller;

/**
 * Class AuthController
 * @package lujie\auth\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseAuthController extends Controller
{
    /**
     * @var ManagerInterface
     */
    public $authManager = 'authManager';

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->authManager = Instance::ensure($this->authManager, ManagerInterface::class);
    }
}

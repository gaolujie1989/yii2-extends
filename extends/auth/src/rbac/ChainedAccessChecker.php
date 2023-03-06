<?php
/**
 * @copyright Copyright (c) 2016
 */

namespace lujie\auth\rbac;

use yii\base\BaseObject;
use yii\di\Instance;
use yii\rbac\CheckAccessInterface;

/**
 * Class ChainedAccessChecker
 * @package lujie\auth\rbac
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ChainedAccessChecker extends BaseObject implements CheckAccessInterface
{
    /**
     * @var CheckAccessInterface[]
     */
    public $accessCheckers = [];

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        foreach ($this->accessCheckers as $key => $accessChecker) {
            $this->accessCheckers[$key] = Instance::ensure($accessChecker, CheckAccessInterface::class);
        }
    }

    /**
     * @inheritdoc
     */
    public function checkAccess($userId, $permissionName, $params = []): bool
    {
        foreach ($this->accessCheckers as $accessChecker) {
            if ($accessChecker->checkAccess($userId, $permissionName, $params)) {
                return true;
            }
        }
        return false;
    }
}

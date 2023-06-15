<?php
/**
 * @copyright Copyright (c) 2016
 */

namespace lujie\auth\rbac;

use yii\base\BaseObject;
use yii\rbac\CheckAccessInterface;

/**
 * Class StaticUserAccessChecker
 * @package lujie\auth\rbac
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class StaticUserAccessChecker extends BaseObject implements CheckAccessInterface
{
    /**
     * @var array
     */
    public $accessUserIds = [];

    /**
     * @var string
     */
    public $permissionPrefix = '';

    /**
     * @var bool
     */
    public $allow = true;

    /**
     * @param int|string $userId
     * @param string $permissionName
     * @param array $params
     * @return bool
     * @throws \Throwable
     * @inheritdoc
     */
    public function checkAccess($userId, $permissionName, $params = []): bool
    {
        if (str_starts_with($permissionName, $this->permissionPrefix)
            && in_array($userId, $this->accessUserIds, true)) {
            return  $this->allow;
        }
        return !$this->allow;
    }
}

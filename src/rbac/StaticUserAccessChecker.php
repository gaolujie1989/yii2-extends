<?php
/**
 * @copyright Copyright (c) 2016
 */

namespace lujie\auth\rbac;

use lujie\extend\file\FileReaderInterface;
use yii\base\BaseObject;
use yii\rbac\CheckAccessInterface;
use function PHPUnit\Framework\assertFileIsReadable;

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
     * @var array
     */
    public $permissionAllows = [];

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
        if (in_array($userId, $this->accessUserIds, true)) {
            foreach ($this->permissionAllows as $permissionPrefix => $allow) {
                if (str_starts_with($permissionName, $permissionPrefix)) {
                    return $allow;
                }
            }
        }
        return false;
    }
}

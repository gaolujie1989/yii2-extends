<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\rbac;

use yii\base\BaseObject;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\rbac\CheckAccessInterface;
use yii\rbac\Permission;
use yii\rbac\Rule;
use yii\web\IdentityInterface;

/**
 * Class UserPermissionAccessChecker
 * @package lujie\auth\rbac
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UserPermissionAccessChecker extends BaseObject implements CheckAccessInterface
{
    /**
     * @var IdentityInterface
     */
    public $identityClass;

    /**
     * @var string
     */
    public $dataKey = 'permissions';

    /**
     * @var array
     */
    public $permissionRules = [];

    /**
     * @var array
     */
    public $rules = [];

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
        $identity = $this->identityClass::findIdentity($userId);
        if ($identity === null) {
            return false;
        }

        $userPermissions = ArrayHelper::getValue($identity, $this->dataKey, []);
        if (!in_array($permissionName, $userPermissions, true)) {
            return false;
        }

        if (isset($this->permissionRules[$permissionName])) {
            $ruleName = $this->permissionRules[$permissionName];
            /** @var Rule $rule */
            $rule = Instance::ensure($this->rules[$ruleName], Rule::class);
            $rule->name = $ruleName;
            $item = new Permission(['name' => $permissionName]);
            return $rule->execute($userId, $item, $params);
        }
        return true;
    }
}

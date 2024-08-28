<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\forms;

use yii\base\UserException;
use yii\helpers\ArrayHelper;
use yii\rbac\Item;
use yii\rbac\Permission;

/**
 * Class AuthRoleForm
 *
 * @property string[] $permissions
 *
 * @package lujie\auth\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AuthRoleForm extends AuthItemForm
{
    public const TYPE = Item::TYPE_ROLE;

    /**
     * 理论上不应该Role包含Role,容易造成死循环，应该用Permission包含Permission替代，Role只包含Permission
     * @var string[]
     */
    private $_permissionsNames;

    /**
     * @var Permission[]
     */
    private $_permissions;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return array_merge($this->formRules(), [
            [['permissions'], 'each', 'rule' => ['string']],
            [['permissions'], 'validatePermissions'],
        ]);
    }

    /**
     * @return bool
     * @inheritdoc
     */
    public function validatePermissions(): bool
    {
        $permissionNames = $this->_permissionsNames;
        $invalidPermissionNames = [];
        $validPermissions = [];
        foreach ($permissionNames as $permissionName) {
            $permission = $this->authManager->getPermission($permissionName);
            if ($permission === null) {
                $invalidPermissionNames[] = $permissionName;
            } else {
                $validPermissions[$permissionName] = $permission;
            }
        }
        if ($invalidPermissionNames) {
            $this->addError('permissions', 'Invalid permissions:' . implode(',', $invalidPermissionNames));
            return false;
        } else {
            $this->_permissions = $validPermissions;
        }
        return true;
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes): void
    {
        $this->savePermissions();
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function savePermissions(): void
    {
        $permissions = $this->_permissions;
        $parent = $this->authManager->getRole($this->name);
        if (empty($permissions)) {
            $this->authManager->removeChildren($parent);
            return;
        }
        $childPermissions = $this->authManager->getChildren($this->name);
        $childPermissions = ArrayHelper::index($childPermissions, 'name');
        foreach ($permissions as $permissionName => $permission) {
            if (isset($childPermissions[$permissionName])) {
                unset($childPermissions[$permissionName]);
            } else {
                $this->authManager->addChild($parent, $permission);
            }
        }
        foreach ($childPermissions as $childPermission) {
            $this->authManager->removeChild($parent, $childPermission);
        }
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function extraFields(): array
    {
        return array_merge(parent::extraFields(), [
            'permissions' => 'permissions',
        ]);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function getPermissions(): array
    {
        if ($this->_permissionsNames === null) {
            $permissions = $this->authManager->getChildren($this->name);
            $permissions = array_filter($permissions, static function(Permission $permission) {
                return $permission->description;
            });
            $this->_permissionsNames = array_values(ArrayHelper::getColumn($permissions, 'name'));
        }
        return $this->_permissionsNames;
    }

    /**
     * @param array $permissions
     * @inheritdoc
     */
    public function setPermissions(array $permissions): void
    {
        $this->_permissionsNames = $permissions;
    }
}

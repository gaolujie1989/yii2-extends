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
 * Class AuthItemForm
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
    private $_permissions;

    /**
     * @var Permission[]
     */
    private $_permissionItems;

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
     * @return array
     * @inheritdoc
     */
    public function validatePermissions(): void
    {
        $permissionNames = $this->_permissions;
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
        } else {
            $this->_permissionItems = $validPermissions;
        }
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
        $permissions = $this->_permissionItems;
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
        if (!$this->_permissions === null) {
            $permissions = $this->authManager->getPermissionsByRole($this->name);
            $permissions = array_filter($permissions, static function(Permission $permission) {
                return $permission->description;
            });
            $this->_permissions = ArrayHelper::getColumn($permissions, 'name');
        }
        return $this->_permissions;
    }

    /**
     * @param array $permissions
     * @inheritdoc
     */
    public function setPermission(array $permissions): void
    {
        $this->_permissions = $permissions;
    }
}
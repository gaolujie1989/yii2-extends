<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\helpers;

use yii\helpers\ArrayHelper;
use yii\rbac\BaseManager;
use yii\rbac\Permission;

class AuthHelper
{
    /**
     * @param $config
     * @param array $replaces
     * @return Permission
     * @inheritdoc
     */
    public static function getPermission($config, array $replaces = []): Permission
    {
        if (!is_array($config)) {
            $config = [
                'name' => $config,
                'label' => null,
            ];
        }

        $permission = new Permission();
        $permission->name = $config['name'];
        $permission->description = $config['label'];
        if (isset($config['ruleName'])) {
            $permission->ruleName = $config['ruleName'];
        }
        if (isset($config['data'])) {
            $permission->data = $config['data'];
        }
        $permission->name = strtr($permission->name, $replaces);
        return $permission;
    }

    /**
     * @param array $permissionTree
     * @param BaseManager $manager
     * @param string $separator
     * @throws \yii\base\Exception
     * @throws \Exception
     * @inheritdoc
     */
    public static function updatePermissions(array $permissionTree, BaseManager $manager, string $separator = '_', $replaces = []): void
    {
        $permissions = [];
        $childrenPermissions = [];
        foreach ($permissionTree as $module => $moduleConfig) {
            foreach ($moduleConfig['groups'] as $group => $groupConfig) {
                foreach ($groupConfig['permissions'] as $key => $permissionConfig) {
                    $permissionConfig['name'] = implode($separator, [$module, $group, $key]);
                    $permission = static::getPermission($permissionConfig, $replaces);
                    $permissions[$permission->name] = $permission;

                    if (isset($permissionConfig['permissionKeys'])) {
                        foreach ($permissionConfig['permissionKeys'] as $permissionKey) {
                            $childPermission = static::getPermission($permissionKey, $replaces);
                            $permissions[$childPermission->name] = $childPermission;
                            $childrenPermissions[$permission->name][$childPermission->name] = $childPermission;
                        }
                    }
                }
            }
        }

        $existPermissions = ArrayHelper::index($manager->getPermissions(), 'name');
        $addPermissions = array_diff_key($permissions, $existPermissions);
        foreach ($addPermissions as $name => $permission) {
            if ($manager->add($permission)) {
                echo 'Add Permission ', $name, " Success\n";
            } else {
                echo 'Add Permission ', $name, " Failed\n";
            }
        }
        $updatePermissions = array_intersect_key($permissions, $existPermissions);
        foreach ($updatePermissions as $name => $permission) {
            if ($manager->update($name, $permission)) {
                echo 'Update Permission ', $name, " Success\n";
            } else {
                echo 'Update Permission ', $name, " Failed\n";
            }
            $manager->update($name, $permission);
        }
        $deletePermissions = array_diff_key($existPermissions, $permissions);
        foreach ($deletePermissions as $name => $permission) {
            $manager->remove($permission);
            echo 'Remove Permission ', $name, "\n";
        }

        foreach ($childrenPermissions as $parentName => $childPermissions) {
            $parentPermission = $permissions[$parentName];
            $existChildren = ArrayHelper::index($manager->getChildren($parentName), 'name');
            $addChildren = array_diff_key($childPermissions, $existChildren);
            foreach ($addChildren as $name => $childPermission) {
                if ($manager->addChild($parentPermission, $childPermission)) {
                    echo 'Add Child ', $name, ' -> ', $parentName, " Success\n";
                } else {
                    echo 'Add Child ', $name, ' -> ', $parentName, " Failed\n";
                }
            }
            $deleteChildren = array_diff_key($existPermissions, $permissions);
            foreach ($deleteChildren as $name => $childPermission) {
                if ($manager->removeChild($parentPermission, $childPermission)) {
                    if ($manager->addChild($parentPermission, $childPermission)) {
                        echo 'Remove Child ', $name, ' -> ', $parentName, " Success\n";
                    } else {
                        echo 'Remove Child ', $name, ' -> ', $parentName, " Failed\n";
                    }
                }
            }
        }
    }
}
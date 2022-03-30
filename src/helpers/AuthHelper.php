<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\helpers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\rbac\BaseManager;
use yii\rbac\Permission;

/**
 * Class AuthHelper
 * @package lujie\auth\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AuthHelper
{
    /**
     * @param string $module
     * @return string
     * @inheritdoc
     */
    public static function generatePermissions(string $module): array
    {
        $activeActionPermissions = [
            'index' => [
                'label' => 'List',
                'sort' => 10,
                'keys' => ['export'],
            ],
            'view' => [
                'label' => 'View',
                'sort' => 20,
                'keys' => ['download']
            ],
            'edit' => [
                'label' => 'Edit',
                'sort' => 30,
                'keys' => ['create', 'update', 'upload', 'import', 'batch-update'],
            ],
            'delete' => [
                'label' => 'Delete',
                'sort' => 40,
                'keys' => ['create', 'update', 'import', 'batch-delete']
            ],
        ];
        $components = Yii::$app->getComponents();
        $controllers = $components['urlManager']['rules'][$module]['controller'];
        $permissionGroups = [];
        $sort = 100;
        foreach ($controllers as $key => $controllerId) {
            $groupName = Inflector::singularize($key);
            $permissionGroups[$groupName] = [
                'label' => ucfirst($groupName),
                'sort' => $sort,
                'permissions' => $activeActionPermissions,
            ];
            $sort += 100;
        }
        return [
            $module => [
                'label' => ucfirst($module),
                'sort' => 100,
                'groups' => $permissionGroups
            ]
        ];
    }

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
    public static function syncPermissions(array $permissionTree, BaseManager $manager, string $separator = '_', $replaces = []): void
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
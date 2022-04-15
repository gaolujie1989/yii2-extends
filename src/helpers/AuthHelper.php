<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\helpers;

use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\rbac\ManagerInterface;
use yii\rbac\Permission;
use yii\rbac\Rule;

/**
 * Class AuthHelper
 * @package lujie\auth\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AuthHelper
{
    /**
     * @var array[]
     */
    public static $defaultActionPermissions = [
        'index' => [
            'label' => 'List',
            'sort' => 10,
            'actionKeys' => ['export'],
        ],
        'view' => [
            'label' => 'View',
            'sort' => 20,
            'actionKeys' => ['download']
        ],
        'edit' => [
            'label' => 'Edit',
            'sort' => 30,
            'actionKeys' => ['create', 'update', 'upload', 'import', 'batch-update'],
        ],
        'delete' => [
            'label' => 'Delete',
            'sort' => 40,
            'actionKeys' => ['batch-delete']
        ],
    ];

    /**
     * @var string[]
     */
    public static $permissionDataKeys = ['sort', 'position', 'data', 'rule'];

    /**
     * @var string
     */
    private static $globalPrefix = '';

    public static $replaceKeys = ['_', '.'];

    /**
     * @param $config
     * @param array $replaces
     * @return Permission
     * @inheritdoc
     */
    public static function createPermission($config, array $replaces = []): Permission
    {
        if (!is_array($config)) {
            return new Permission(['name' => strtr($config, $replaces)]);
        }

        $permission = new Permission();
        $permission->name = strtr($config['name'], $replaces);
        $permission->description = $config['label'] ?? $config['description'] ?? null;
        $permission->ruleName = $config['ruleName'] ?? null;
        $permission->data = ArrayHelper::filter($config, static::$permissionDataKeys);
        return $permission;
    }

    /**
     * @param array $permissionTree
     * @param array|string[] $childrenKeys
     * @param string $prefix
     * @param string $separator
     * @param array $replaces
     * @return array
     * @inheritdoc
     */
    protected static function createPermissions(
        array  $permissionTree,
        array  $childrenKeys = ['items'],
        string $prefix = '',
        string $separator = '_',
        array  $replaces = []
    ): array
    {
        if ($prefix) {
            $prefix = rtrim($prefix, $separator) . $separator;
        }
        $replaces = array_merge(array_fill_keys(static::$replaceKeys, $separator), $replaces);
        $childrenKey = count($childrenKeys) > 1 ? array_shift($childrenKeys) : reset($childrenKeys);

        $permissions = [];
        $permissionChildren = [];
        $treePermissions = [];
        $treePermissionChildren = [];
        foreach ($permissionTree as $key => $subTree) {
            if (static::$globalPrefix = $subTree['prefix'] ?? static::$globalPrefix) {
                static::$globalPrefix = rtrim(static::$globalPrefix, $separator) . $separator;
            }
            $permissionKey = $prefix . $key;
            $subTree['name'] = $permissionKey;
            $permission = static::createPermission($subTree, $replaces);
            $treePermissions[$permission->name] = $permission;

            if (isset($subTree['actionKeys'])) {
                foreach ($subTree['actionKeys'] as $actionKey) {
                    $permissionKey = $prefix . $actionKey;
                    $childPermission = static::createPermission($permissionKey, $replaces);
                    $treePermissions[$childPermission->name] = $childPermission;
                    $treePermissionChildren[$permission->name][$childPermission->name] = $childPermission;
                }
            }
            if (isset($subTree['permissionKeys'])) {
                foreach ($subTree['permissionKeys'] as $permissionKey) {
                    $permissionKey = static::$globalPrefix . $permissionKey;
                    $childPermission = static::createPermission($permissionKey, $replaces);

                    $treePermissions[$childPermission->name] = $childPermission;
                    $treePermissionChildren[$permission->name][$childPermission->name] = $childPermission;
                }
            }

            if ($childrenTree = $subTree[$childrenKey] ?? []) {
                [$childPermissions, $childPermissionChildren] = static::createPermissions($childrenTree, $childrenKeys, $permission->name, $separator, $replaces);
                $permissions[] = $childPermissions;
                $permissionChildren[] = $childPermissionChildren;
                foreach ($childrenTree as $childKey => $childTree) {
                    $childPermissionKey = $permission->name . $separator . $childKey;
                    $treePermissionChildren[$permission->name][$childPermissionKey] = $childPermissions[$childPermissionKey];
                }
            }
        }
        $permissions = array_merge($treePermissions, ...$permissions);
        $permissionChildren = array_merge($treePermissionChildren, ...$permissionChildren);
        return [$permissions, $permissionChildren];
    }

    /**
     * @param array $permissionTree
     * @param ManagerInterface $manager
     * @param string $prefix
     * @param string $separator
     * @param array $replaces
     * @throws \yii\base\Exception
     * @throws \Exception
     * @inheritdoc
     */
    public static function syncPermissions(
        array       $permissionTree,
        ManagerInterface $manager,
        array       $childrenKeys = ['modules', 'groups', 'permissions'],
        string      $separator = '_',
        array       $replaces = []
    ): void
    {
        [$permissions, $permissionChildren] = static::createPermissions($permissionTree, $childrenKeys, '', $separator, $replaces);
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
        }

        foreach ($permissionChildren as $parentName => $childPermissions) {
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
            /** @var Permission[] $deleteChildren */
            $deleteChildren = array_diff_key($existPermissions, $permissions);
            foreach ($deleteChildren as $name => $childPermission) {
                if ($manager->removeChild($parentPermission, $childPermission)) {
                    if ($manager->addChild($parentPermission, $childPermission)) {
                        echo 'Remove Child ', $name, ' -> ', $parentName, " Success\n";
                    } else {
                        echo 'Remove Child ', $name, ' -> ', $parentName, " Failed\n";
                    }
                }
                if (empty($permissions[$childPermission->name])) {
                    if ($manager->remove($childPermission)) {
                        echo 'Remove Permission ', $childPermission->name, " Success\n";
                    } else {
                        echo 'Remove Permission ', $childPermission->name, " Failed\n";
                    }
                }
            }
        }
    }

    /**
     * @param array $rules
     * @param ManagerInterface $manager
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     * @inheritdoc
     */
    public static function syncRules(array $rules, ManagerInterface $manager): void
    {
        foreach ($rules as $ruleName => $ruleConfig) {
            $ruleInstance = Instance::ensure($ruleConfig, Rule::class);
            if ($manager->getRule($ruleName)) {
                if ($manager->update($ruleName, $ruleInstance)) {
                    echo 'Update Rule ', $ruleName, " Success\n";
                } else {
                    echo 'Update Rule ', $ruleName, " Failed\n";
                }
                continue;
            }
            if ($manager->add($ruleInstance)) {
                echo 'Add Rule ', $ruleName, " Success\n";
            } else {
                echo 'Add Rule ', $ruleName, " Failed\n";
            }
        }
    }
}
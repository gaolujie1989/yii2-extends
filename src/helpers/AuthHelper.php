<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\helpers;

use lujie\auth\models\AuthItem;
use yii\db\Query;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\rbac\DbManager;
use yii\rbac\Item;
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
        $childPermissions = [];
        $permissionChildren = [];
        $treePermissions = [];
        $treeChildPermissions = [];
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
                    $treeChildPermissions[$childPermission->name] = $childPermission;
                    $treePermissionChildren[$permission->name][$childPermission->name] = $childPermission;
                }
            }
            if (isset($subTree['permissionKeys'])) {
                foreach ($subTree['permissionKeys'] as $permissionKey) {
                    $permissionKey = static::$globalPrefix . $permissionKey;
                    $childPermission = static::createPermission($permissionKey, $replaces);
                    $treeChildPermissions[$childPermission->name] = $childPermission;
                    $treePermissionChildren[$permission->name][$childPermission->name] = $childPermission;
                }
            }

            if ($childrenTree = $subTree[$childrenKey] ?? []) {
                [$childTreePermissions, $childTreeChildPermissions, $childTreePermissionChildren] = static::createPermissions($childrenTree, $childrenKeys, $permission->name, $separator, $replaces);
                $permissions[] = $childTreePermissions;
                $childPermissions[] = $childTreeChildPermissions;
                $permissionChildren[] = $childTreePermissionChildren;
                foreach ($childrenTree as $childKey => $childTree) {
                    $childTreePermissionKey = $permission->name . $separator . $childKey;
                    $treePermissionChildren[$permission->name][$childTreePermissionKey] = $childTreePermissions[$childTreePermissionKey];
                }
            }
        }
        $permissions = array_merge($treePermissions, ...$permissions);
        $childPermissions = array_merge($treeChildPermissions, ...$childPermissions);
        $permissionChildren = array_merge($treePermissionChildren, ...$permissionChildren);
        return [$permissions, $childPermissions, $permissionChildren];
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
        [$permissions, $childPermissions, $permissionChildren] = static::createPermissions($permissionTree, $childrenKeys, '', $separator, $replaces);
        $permissions = array_merge($childPermissions, $permissions);
        unset($childPermissions);
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
            $deleteChildren = array_diff_key($existChildren, $childPermissions);
            foreach ($deleteChildren as $name => $childPermission) {
                if ($manager->removeChild($parentPermission, $childPermission)) {
                    echo 'Remove Child ', $name, ' -> ', $parentName, " Success\n";
                } else {
                    echo 'Remove Child ', $name, ' -> ', $parentName, " Failed\n";
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

    /**
     * @param DbManager $manager
     * @param array|string[] $childrenKeys
     * @return array
     * @inheritdoc
     */
    public static function generatePermissionTree(DbManager $manager, array $childrenKeys = ['items']): array
    {
        $permissions = (new Query())->from($manager->itemTable)
            ->andWhere(['type' => Item::TYPE_PERMISSION])
            ->andWhere(['IS NOT', 'description', null])
            ->select(['name', 'description', 'data'])
            ->indexBy('name')
            ->all($manager->db);
        foreach ($permissions as $key => $permission) {
            $data = $permission['data'];
            if ($data !== null) {
                $data = is_resource($data) ? stream_get_contents($data) : $data;
                /** @noinspection UnserializeExploitsInspection */
                $permission['data'] = @unserialize($data);
                $permissions[$key] = $permission;
            }
        }
        $permissionNames = array_keys($permissions);

        $itemChildren = (new Query())->from($manager->itemChildTable)
            ->andWhere(['parent' => $permissionNames])
            ->select(['parent', 'child'])
            ->all();
        $parentChildren = ArrayHelper::map($itemChildren, 'child', 'child', 'parent');

        $childNames = array_unique(ArrayHelper::getColumn($itemChildren, 'child'));
        $rootPermissionNames = array_diff($permissionNames, $childNames);
        return static::createPermissionTree($rootPermissionNames, $permissions, $parentChildren, $childrenKeys);
    }

    /**
     * @param array $parents
     * @param array $permissions
     * @param array $itemChildren
     * @param array|string[] $childrenKeys
     * @return array
     * @inheritdoc
     */
    public static function createPermissionTree(array $parents, array $permissions, array $itemChildren, array $childrenKeys = ['items']): array
    {
        $permissionTree = [];
        $childrenKey = count($childrenKeys) > 1 ? array_shift($childrenKeys) : reset($childrenKeys);
        foreach ($parents as $parent) {
            if (empty($permissions[$parent])) {
                continue;
            }
            $permission = $permissions[$parent];
            $permissionTree[$parent] = [
                'name' => $permission['name'],
                'label' => $permission['description'],
                'sort' => $permission['data']['sort'] ?? $permission['data']['position'] ?? 0,
            ];
            if (empty($itemChildren[$parent])) {
                continue;
            }
            if ($subTree = static::createPermissionTree($itemChildren[$parent], $permissions, $itemChildren, $childrenKeys)) {
                $permissionTree[$parent][$childrenKey] = $subTree;
            }
        }
        uasort($permissionTree, static function($a, $b) {
            return $a['sort'] <=> $b['sort'];
        });
        return array_values($permissionTree);
    }
}
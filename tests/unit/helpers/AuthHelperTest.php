<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\tests\unit\rbac;

use lujie\auth\helpers\AuthHelper;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\rbac\DbManager;

class AuthHelperTest extends \Codeception\Test\Unit
{
    /**
     * @throws \Throwable
     * @inheritdoc
     */
    public function testSyncPermissions(): void
    {
        $childrenKeys = ['modules', 'groups', 'permissions'];
        $authManager = new DbManager();
        $permissions = ['app' => [
            'label' => 'AppXX',
            'prefix' => 'app',
            'sort' => 10,
            'modules' => require __DIR__ . '/../fixtures/data/permissions.php',
        ]];
        $permissions2 = ['app' => [
            'label' => 'AppXX',
            'prefix' => 'app',
            'sort' => 10,
            'modules' => require __DIR__ . '/../fixtures/data/permissions2.php',
        ]];
        $query = (new Query())->from($authManager->itemTable)->select(['name']);
        $childQuery = (new Query())->from($authManager->itemChildTable);
        $this->assertEquals(0, $query->count());
        AuthHelper::syncPermissions($permissions, $authManager, $childrenKeys);
        $childExpected = [
            'app' => [
                'app_xxxModuleA',
            ],
            'app_xxxModuleA' => [
                'app_xxxModuleA_xxxControllerA',
            ],
            'app_xxxModuleA_xxxControllerA' => [
                'app_xxxModuleA_xxxControllerA_xxxAction',
                'app_xxxModuleA_xxxControllerA_xxxActionA',
            ],
            'app_xxxModuleA_xxxControllerA_xxxAction' => [
                'app_xxxModuleA_xxxControllerA_xxxAction2',
                'app_xxxModuleA_xxxControllerA_xxxAction3',
                'app_xxxModule2_xxxController2_xxxAction2',
                'app_xxxModule3_xxxController3_xxxAction3',
            ],
            'app_xxxModuleA_xxxControllerA_xxxActionA' => [
                'app_xxxModuleA_xxxControllerA_xxxAction2',
                'app_xxxModule2_xxxController2_xxxAction2',
            ],
        ];
        $expected = array_unique(array_merge(array_keys($childExpected), ...array_values($childExpected)));
        sort($expected);
        $childExpected = array_map(static function ($keys) {
            return array_combine($keys, $keys);
        }, $childExpected);
        $this->assertEquals($expected, $query->column());
        $this->assertEquals($childExpected, ArrayHelper::map($childQuery->all(), 'child', 'child', 'parent'));
        AuthHelper::syncPermissions($permissions2, $authManager, $childrenKeys);

        $childExpected = [
            'app' => [
                'app_xxxModuleA',
            ],
            'app_xxxModuleA' => [
                'app_xxxModuleA_xxxControllerA',
            ],
            'app_xxxModuleA_xxxControllerA' => [
                'app_xxxModuleA_xxxControllerA_xxxActionA',
                'app_xxxModuleA_xxxControllerA_xxxActionB',
            ],
            'app_xxxModuleA_xxxControllerA_xxxActionA' => [
                'app_xxxModuleA_xxxControllerA_xxxAction2',
                'app_xxxModuleA_xxxControllerA_xxxAction3',
                'app_xxxModule2_xxxController2_xxxAction2',
                'app_xxxModule3_xxxController3_xxxAction3',
            ],
            'app_xxxModuleA_xxxControllerA_xxxActionB' => [
                'app_xxxModuleA_xxxControllerA_xxxAction3',
                'app_xxxModule3_xxxController3_xxxAction3',
            ],
        ];
        $expected = array_unique(array_merge(array_keys($childExpected), ...array_values($childExpected)));
        sort($expected);
        $childExpected = array_map(static function ($keys) {
            return array_combine($keys, $keys);
        }, $childExpected);
        $this->assertEquals($expected, $query->column());
        $this->assertEquals($childExpected, ArrayHelper::map($childQuery->all(), 'child', 'child', 'parent'));
    }

    /**
     * @throws \Throwable
     * @inheritdoc
     */
    public function testSyncRules(): void
    {
        $authManager = new DbManager();
        $rules = require __DIR__ . '/../fixtures/data/rules.php';
        $rules2 = require __DIR__ . '/../fixtures/data/rules2.php';
        $query = (new Query())->from($authManager->ruleTable)->select(['name']);
        $this->assertEquals(0, $query->count());
        AuthHelper::syncRules($rules, $authManager);
        $this->assertEquals(array_keys(array_merge($rules)), $query->column());
        AuthHelper::syncRules($rules2, $authManager);
        $this->assertEquals(array_keys(array_merge($rules, $rules2)), $query->column());
    }
}

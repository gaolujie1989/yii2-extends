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
        $query = (new Query())->from($authManager->itemTable)->select(['description'])->indexBy('name');
        $childQuery = (new Query())->from($authManager->itemChildTable);
        $this->assertEquals(0, $query->count());
        AuthHelper::syncPermissions($permissions, $authManager, $childrenKeys);
        $expected = [
            'app' => 'AppXX',
            'app_xxxModuleA' => 'xxxModuleA',
            'app_xxxModuleA_xxxControllerA' => 'xxxControllerA',
            'app_xxxModuleA_xxxControllerA_xxxAction' => 'xxxAction',
            'app_xxxModuleA_xxxControllerA_xxxActionA' => 'xxxActionA',
            'app_xxxModuleA_xxxControllerA_xxxAction2' => null,
            'app_xxxModuleA_xxxControllerA_xxxAction3' => null,
            'app_xxxModule2_xxxController2_xxxAction2' => null,
            'app_xxxModule3_xxxController3_xxxAction3' => null,
        ];
        $childExpected = [
            'app' => [
                'app_xxxModuleA' => 'app_xxxModuleA',
            ],
            'app_xxxModuleA' => [
                'app_xxxModuleA_xxxControllerA' => 'app_xxxModuleA_xxxControllerA',
            ],
            'app_xxxModuleA_xxxControllerA' => [
                'app_xxxModuleA_xxxControllerA_xxxAction' => 'app_xxxModuleA_xxxControllerA_xxxAction',
                'app_xxxModuleA_xxxControllerA_xxxActionA' => 'app_xxxModuleA_xxxControllerA_xxxActionA',
            ],
            'app_xxxModuleA_xxxControllerA_xxxAction' => [
                'app_xxxModuleA_xxxControllerA_xxxAction2' => 'app_xxxModuleA_xxxControllerA_xxxAction2',
                'app_xxxModuleA_xxxControllerA_xxxAction3' => 'app_xxxModuleA_xxxControllerA_xxxAction3',
                'app_xxxModule2_xxxController2_xxxAction2' => 'app_xxxModule2_xxxController2_xxxAction2',
                'app_xxxModule3_xxxController3_xxxAction3' => 'app_xxxModule3_xxxController3_xxxAction3',
            ],
            'app_xxxModuleA_xxxControllerA_xxxActionA' => [
                'app_xxxModuleA_xxxControllerA_xxxAction2' => 'app_xxxModuleA_xxxControllerA_xxxAction2',
                'app_xxxModule2_xxxController2_xxxAction2' => 'app_xxxModule2_xxxController2_xxxAction2',
            ],
        ];
        $this->assertEquals($expected, $query->column());
        $this->assertEquals($childExpected, ArrayHelper::map($childQuery->all(), 'child', 'child', 'parent'));
        AuthHelper::syncPermissions($permissions2, $authManager, $childrenKeys);

        $expected = [
            'app' => 'AppXX',
            'app_xxxModuleA' => 'xxxModuleA',
            'app_xxxModuleA_xxxControllerA' => 'xxxControllerA',
            'app_xxxModuleA_xxxControllerA_xxxActionA' => 'xxxActionA',
            'app_xxxModuleA_xxxControllerA_xxxActionB' => 'xxxActionB',
            'app_xxxModuleA_xxxControllerA_xxxAction2' => null,
            'app_xxxModuleA_xxxControllerA_xxxAction3' => null,
            'app_xxxModule2_xxxController2_xxxAction2' => null,
            'app_xxxModule3_xxxController3_xxxAction3' => null,
        ];
        $childExpected = [
            'app' => [
                'app_xxxModuleA' => 'app_xxxModuleA',
            ],
            'app_xxxModuleA' => [
                'app_xxxModuleA_xxxControllerA' => 'app_xxxModuleA_xxxControllerA',
            ],
            'app_xxxModuleA_xxxControllerA' => [
                'app_xxxModuleA_xxxControllerA_xxxActionA' => 'app_xxxModuleA_xxxControllerA_xxxActionA',
                'app_xxxModuleA_xxxControllerA_xxxActionB' => 'app_xxxModuleA_xxxControllerA_xxxActionB',
            ],
            'app_xxxModuleA_xxxControllerA_xxxActionA' => [
                'app_xxxModuleA_xxxControllerA_xxxAction2' => 'app_xxxModuleA_xxxControllerA_xxxAction2',
                'app_xxxModuleA_xxxControllerA_xxxAction3' => 'app_xxxModuleA_xxxControllerA_xxxAction3',
                'app_xxxModule2_xxxController2_xxxAction2' => 'app_xxxModule2_xxxController2_xxxAction2',
                'app_xxxModule3_xxxController3_xxxAction3' => 'app_xxxModule3_xxxController3_xxxAction3',
            ],
            'app_xxxModuleA_xxxControllerA_xxxActionB' => [
                'app_xxxModuleA_xxxControllerA_xxxAction3' => 'app_xxxModuleA_xxxControllerA_xxxAction3',
                'app_xxxModule3_xxxController3_xxxAction3' => 'app_xxxModule3_xxxController3_xxxAction3',
            ],
        ];
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

    /**
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function testGeneratePermissionTree(): void
    {
        $childrenKeys = ['modules', 'groups', 'permissions'];
        $authManager = new DbManager();
        $permissions = ['app' => [
            'label' => 'AppXX',
            'prefix' => 'app',
            'sort' => 10,
            'modules' => require __DIR__ . '/../fixtures/data/permissions.php',
        ]];
        $query = (new Query())->from($authManager->itemTable)->select(['name'])->indexBy('name');
        $this->assertEquals(0, $query->count());
        AuthHelper::syncPermissions($permissions, $authManager, $childrenKeys);
        $permissionTree = AuthHelper::generatePermissionTree($authManager, $childrenKeys);
        $expected = [
            'app' => [
                'name' => 'app',
                'label' => 'AppXX',
                'sort' => 10,
                'modules' => [
                    'app_xxxModuleA' => [
                        'name' => 'app_xxxModuleA',
                        'label' => 'xxxModuleA',
                        'sort' => 10,
                        'groups' => [
                            'app_xxxModuleA_xxxControllerA' => [
                                'name' => 'app_xxxModuleA_xxxControllerA',
                                'label' => 'xxxControllerA',
                                'sort' => 10,
                                'permissions' => [
                                    'app_xxxModuleA_xxxControllerA_xxxAction' => [
                                        'name' => 'app_xxxModuleA_xxxControllerA_xxxAction',
                                        'label' => 'xxxAction',
                                        'sort' => 20,
                                    ],
                                    'app_xxxModuleA_xxxControllerA_xxxActionA' => [
                                        'name' => 'app_xxxModuleA_xxxControllerA_xxxActionA',
                                        'label' => 'xxxActionA',
                                        'sort' => 10,
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
        codecept_debug($permissionTree);
        $this->assertEquals($expected, $permissionTree);
    }
}

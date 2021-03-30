<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\tests\unit;

use lujie\auth\rbac\UserPermissionAccessChecker;
use lujie\auth\rules\AuthorRule;
use lujie\auth\tests\unit\fixtures\TestModel;
use lujie\auth\tests\unit\fixtures\TestUser;

/**
 * Class UserPermissionAccessCheckerTest
 * @package lujie\auth\tests\unit
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UserPermissionAccessCheckerTest extends \Codeception\Test\Unit
{


    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @throws \Throwable
     * @inheritdoc
     */
    public function testMe(): void
    {
        $accessChecker = new UserPermissionAccessChecker([
            'identityClass' => TestUser::class,
            'dataKey' => 'data.permissions',
            'permissionRules' => [
                'xxxPermission2' => 'isAuthor',
            ],
            'rules' => [
                'isAuthor' => [
                    'class' => AuthorRule::class,
                ]
            ]
        ]);
        $this->assertTrue($accessChecker->checkAccess(1, 'xxxPermission1'));
        $this->assertFalse($accessChecker->checkAccess(1, 'xxxPermission2'));
        $this->assertFalse($accessChecker->checkAccess(1, 'xxxPermission2', new TestModel(['created_by' => 2])));
        $this->assertTrue($accessChecker->checkAccess(1, 'xxxPermission2', new TestModel(['created_by' => 1])));
    }
}

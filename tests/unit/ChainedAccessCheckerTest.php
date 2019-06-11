<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\tests\unit;

use lujie\auth\rbac\ChainedAccessChecker;
use lujie\auth\rbac\StaticUserAccessChecker;

class ChainedAccessCheckerTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

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
        $accessChecker = new ChainedAccessChecker([
            'accessCheckers' => [
                [
                    'class' => StaticUserAccessChecker::class,
                    'accessUserIds' => [1, 2]
                ],
                [
                    'class' => StaticUserAccessChecker::class,
                    'accessUserIds' => [11, 22]
                ]
            ]
        ]);
        $this->assertTrue($accessChecker->checkAccess(1, 'xxx'));
        $this->assertFalse($accessChecker->checkAccess(3, 'xxx'));
        $this->assertTrue($accessChecker->checkAccess(11, 'xxx'));
        $this->assertFalse($accessChecker->checkAccess(33, 'xxx'));
    }
}

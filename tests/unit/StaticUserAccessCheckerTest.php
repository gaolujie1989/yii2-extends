<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\tests\unit;

use lujie\auth\rbac\StaticUserAccessChecker;

class StaticUserAccessCheckerTest extends \Codeception\Test\Unit
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
        $accessChecker = new StaticUserAccessChecker([
            'accessUserIds' => [1, 2]
        ]);
        $this->assertTrue($accessChecker->checkAccess(1, 'xxx'));
        $this->assertFalse($accessChecker->checkAccess(3, 'xxx'));
    }
}

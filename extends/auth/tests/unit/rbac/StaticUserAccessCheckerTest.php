<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\tests\unit\rbac;

use lujie\auth\rbac\StaticUserAccessChecker;

class StaticUserAccessCheckerTest extends \Codeception\Test\Unit
{
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

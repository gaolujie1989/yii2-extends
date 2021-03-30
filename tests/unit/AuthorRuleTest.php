<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\tests\unit;

use lujie\auth\rules\AuthorRule;
use yii\rbac\Item;

class AuthorRuleTest extends \Codeception\Test\Unit
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
        $rule = new AuthorRule();
        $this->assertTrue($rule->execute(1, new Item(), ['created_by' => 1]));
        $this->assertFalse($rule->execute(2, new Item(), ['created_by' => 1]));
        $this->assertFalse($rule->execute(2, new Item(), []));
    }
}

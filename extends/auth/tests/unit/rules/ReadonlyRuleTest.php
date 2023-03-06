<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\tests\unit\rules;

use lujie\auth\rules\ReadOnlyRule;
use Yii;
use yii\rbac\Item;
use yii\web\Request;

class ReadonlyRuleTest extends \Codeception\Test\Unit
{
    /**
     * @throws \Throwable
     * @inheritdoc
     */
    public function testMe(): void
    {
        $rule = new ReadOnlyRule();
        Yii::$app->set('request', new Request());
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertTrue($rule->execute(1, new Item(), []));
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertFalse($rule->execute(1, new Item(), []));
    }
}

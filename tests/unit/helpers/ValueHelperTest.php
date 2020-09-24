<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\test\unit\db;


use lujie\extend\helpers\ObjectHelper;
use lujie\extend\helpers\ValueHelper;
use lujie\extend\tests\unit\mocks\MockIdentity;

class ValueHelperTest extends \Codeception\Test\Unit
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
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testMe(): void
    {
        $this->assertTrue(ValueHelper::isMatch(123, ['123', '456'], false));
        $this->assertFalse(ValueHelper::isMatch(123, ['123', '456'], true));
        $this->assertFalse(ValueHelper::isMatch('targetValue', 'abc'));
        $this->assertTrue(ValueHelper::isMatch('targetValue', 'targetValue'));
        $this->assertTrue(ValueHelper::isMatch('targetValue', 'target*'));
        $this->assertFalse(ValueHelper::isMatch('targetValue', '!target*'));
    }
}

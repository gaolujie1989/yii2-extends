<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\test\unit\db;


use lujie\extend\tests\unit\mocks\MockActiveRecord;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

class IdFieldTraitTest extends \Codeception\Test\Unit
{
    /**
     * @var \lujie\extend\tests\UnitTester
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
        $mockActiveRecord = new MockActiveRecord(['mock_id' => 1]);
        $this->assertEquals(1, $mockActiveRecord->getId());
        $mockActiveRecord->setId(2);
        $this->assertEquals(2, $mockActiveRecord->getAttribute('mock_id'));
        $toArray = $mockActiveRecord->toArray();
        $this->assertEquals(2, $toArray['id']);
    }
}

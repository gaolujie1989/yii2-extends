<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\test\unit\db;

use lujie\extend\tests\unit\mocks\MockActiveRecord;
use yii\base\Event;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

class TraceableBehaviorTraitTest extends \Codeception\Test\Unit
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
        MockActiveRecord::$columns = ['mock_id', 'mock_value', 'updated_by', 'updated_at'];
        $mockActiveRecord = new MockActiveRecord();
        $this->assertEquals(0, $mockActiveRecord->getActionByDefault(new Event(['sender' => $mockActiveRecord])));

        /** @var TimestampBehavior $timestampBehavior */
        $timestampBehavior = $mockActiveRecord->getBehavior('timestamp');
        $this->assertInstanceOf(TimestampBehavior::class, $timestampBehavior);
        $this->assertEquals(false, $timestampBehavior->createdAtAttribute);
        $this->assertEquals('updated_at', $timestampBehavior->updatedAtAttribute);

        /** @var BlameableBehavior $blameableBehavior */
        $blameableBehavior = $mockActiveRecord->getBehavior('blameable');
        $this->assertInstanceOf(BlameableBehavior::class, $blameableBehavior);
        $this->assertEquals(false, $blameableBehavior->createdByAttribute);
        $this->assertEquals('updated_by', $blameableBehavior->updatedByAttribute);
    }
}

<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\test\unit\db;


use lujie\extend\tests\unit\mocks\MockActiveRecord;
use yii\db\ActiveRecord;

class TransactionTraitTest extends \Codeception\Test\Unit
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
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function testMe(): void
    {
        $mockActiveRecord = new MockActiveRecord();
        $this->assertFalse($mockActiveRecord->isTransactional(ActiveRecord::OP_INSERT));
        $this->assertFalse($mockActiveRecord->isTransactional(ActiveRecord::OP_UPDATE));
        $this->assertFalse($mockActiveRecord->isTransactional(ActiveRecord::OP_DELETE));

        $closure = static function () {
        };
        $mockActiveRecord->on(ActiveRecord::EVENT_AFTER_INSERT, $closure);
        $this->assertTrue($mockActiveRecord->isTransactional(ActiveRecord::OP_INSERT));
        $mockActiveRecord->on(ActiveRecord::EVENT_AFTER_UPDATE, $closure);
        $this->assertTrue($mockActiveRecord->isTransactional(ActiveRecord::OP_UPDATE));
        $mockActiveRecord->on(ActiveRecord::EVENT_AFTER_DELETE, $closure);
        $this->assertTrue($mockActiveRecord->isTransactional(ActiveRecord::OP_DELETE));
    }
}

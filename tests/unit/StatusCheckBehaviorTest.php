<?php

namespace lujie\state\machine\tests\unit;

use lujie\state\machine\behaviors\StatusCheckBehavior;
use lujie\state\machine\tests\unit\fixtures\TestOrder;

class StatusCheckBehaviorTest extends \Codeception\Test\Unit
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

    // tests
    public function testMe(): void
    {
        $testOrder = $this->getTestOrder();
        $testOrder->status = TestOrder::STATUS_PENDING;
        $this->assertTrue($testOrder->isPending);
        $this->assertTrue($testOrder->getIsPending());

        $testOrder->status = TestOrder::STATUS_CANCELLED;
        $this->assertFalse($testOrder->isPending);
        $this->assertFalse($testOrder->getIsPending());
        $this->assertTrue($testOrder->isFinished);
        $this->assertTrue($testOrder->getIsFinished());
    }

    /**
     * @return TestOrder
     * @inheritdoc
     */
    protected function getTestOrder(): TestOrder
    {
        $testOrder = new TestOrder([
            'as statusCheck' => [
                'class' => StatusCheckBehavior::class,
                'statusCheckProperties' => [
                    'isPending' => [TestOrder::STATUS_PENDING],
                    'isFinished' => [TestOrder::STATUS_REFUNDED, TestOrder::STATUS_CANCELLED],
                ],
            ],
        ]);
        $testOrder->attributes = [
            'order_no' => 'TEST_STATE_MACHINE'
        ];
        $testOrder->save();
        return $testOrder;
    }
}

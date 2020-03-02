<?php

namespace lujie\state\machine\tests\unit;

use lujie\state\machine\behaviors\StateMachineBehavior;
use lujie\state\machine\tests\unit\fixtures\TestOrder;
use yii\base\InvalidCallException;

class StateMachineBehaviorTest extends \Codeception\Test\Unit
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
    /**
     * @return TestOrder
     * @inheritdoc
     */
    protected function getTestOrder(): TestOrder
    {
        $testOrder = new TestOrder([
            'as stateMachine' => [
                'class' => StateMachineBehavior::class,
                'initialStatus' => TestOrder::STATUS_PENDING,
                'autoSetInitialStatus' => true,
                'statusTransitions' => [
                    TestOrder::STATUS_PENDING => [
                        TestOrder::STATUS_PAID,
                        TestOrder::STATUS_CANCELLED,
                    ],
                    TestOrder::STATUS_PAID => [
                        TestOrder::STATUS_SHIPPED,
                        TestOrder::STATUS_REFUNDED,
                    ],
                    TestOrder::STATUS_SHIPPED => [
                        TestOrder::STATUS_REFUNDED,
                    ],
                    TestOrder::STATUS_REFUNDED => [
                    ],
                ],
                'statusScenarios' => [
                    TestOrder::STATUS_PENDING => TestOrder::SCENARIO_PENDING,
                    TestOrder::STATUS_PAID => TestOrder::SCENARIO_PAID,
                    TestOrder::STATUS_SHIPPED => TestOrder::SCENARIO_SHIPPED,
                    TestOrder::STATUS_REFUNDED => TestOrder::SCENARIO_FINISHED,
                    TestOrder::STATUS_CANCELLED => TestOrder::SCENARIO_FINISHED,
                ],
                'statusMethods' => [
                    'pay' => [
                        TestOrder::STATUS_PENDING => TestOrder::STATUS_PAID,
                    ],
                    'ship' => [
                        TestOrder::STATUS_PAID => TestOrder::STATUS_SHIPPED,
                    ],
                    'refund' => [
                        TestOrder::STATUS_PAID => TestOrder::STATUS_REFUNDED,
                        TestOrder::STATUS_SHIPPED => TestOrder::STATUS_REFUNDED,
                    ],
                    'cancel' => [
                        TestOrder::STATUS_PENDING => TestOrder::STATUS_CANCELLED,
                    ]
                ]
            ],
        ]);
        $testOrder->attributes = [
            'order_no' => 'TEST_STATE_MACHINE'
        ];
        $testOrder->save();
        return $testOrder;
    }

    public function testMe(): void
    {
        $testOrder = $this->getTestOrder();
        $this->assertEquals(TestOrder::STATUS_PENDING, $testOrder->status);
        $this->assertEquals(TestOrder::SCENARIO_PENDING, $testOrder->getScenario());

        $this->assertTrue($testOrder->cancel());
        $testOrder->refresh();
        $this->assertEquals(TestOrder::STATUS_CANCELLED, $testOrder->status);
        $this->assertEquals(TestOrder::SCENARIO_FINISHED, $testOrder->getScenario());

        try {
            $testOrder->pay();
            $this->assertTrue(false, 'Pay should throw exception');
        } catch (\Exception $e) {
            $this->assertInstanceOf(InvalidCallException::class, $e);
        }

        $testOrder = $this->getTestOrder();
        $this->assertTrue($testOrder->pay());
        $testOrder->refresh();
        $this->assertEquals(TestOrder::STATUS_PAID, $testOrder->status);
        $this->assertEquals(TestOrder::SCENARIO_PAID, $testOrder->getScenario());

        $this->assertTrue($testOrder->ship(['shipping_address_id' => 123]));
        $testOrder->refresh();
        $this->assertEquals(123, $testOrder->shipping_address_id);
        $this->assertEquals(TestOrder::STATUS_SHIPPED, $testOrder->status);
        $this->assertEquals(TestOrder::SCENARIO_SHIPPED, $testOrder->getScenario());

        $this->assertTrue($testOrder->refund(['shipping_address_id' => 456]));
        $testOrder->refresh();
        $this->assertEquals(123, $testOrder->shipping_address_id);
        $this->assertEquals(TestOrder::STATUS_REFUNDED, $testOrder->status);
        $this->assertEquals(TestOrder::SCENARIO_FINISHED, $testOrder->getScenario());
    }
}

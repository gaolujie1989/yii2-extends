<?php

namespace lujie\ar\relation\behaviors\tests\unit;

use lujie\ar\relation\behaviors\RelatedCounterUpdateBehavior;
use lujie\ar\relation\behaviors\RelationDeletableBehavior;
use lujie\ar\relation\behaviors\RelationSavableBehavior;
use lujie\ar\relation\behaviors\tests\unit\fixtures\models\TestAddress;
use lujie\ar\relation\behaviors\tests\unit\fixtures\models\TestCustomer;
use lujie\ar\relation\behaviors\tests\unit\fixtures\models\TestOrder;
use lujie\ar\relation\behaviors\tests\unit\fixtures\models\TestOrderItem;
use lujie\ar\relation\behaviors\tests\unit\fixtures\models\TestOrderPayment;
use yii\base\InvalidCallException;
use yii\helpers\ArrayHelper;

class RelatedCounterUpdateBehaviorTest extends \Codeception\Test\Unit
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
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @inheritdoc
     */
    public function testRelatedCounterUpdate()
    {
        $orderData = [
            'order_no' => 'TEST_WITH_INSERT_ORDER_ITEMS',
            'order_amount' => 100,
        ];
        $paymentData = [
            'transaction_no' => 'TTT111',
            'paid_amount' => 10,
        ];
        $testOrder = new TestOrder([]);
        $testOrder->load($orderData, '');
        $this->assertTrue($testOrder->save(false));
        $this->assertEquals(0, $testOrder->paid_amount);

        $testOrderPayment = new TestOrderPayment([
            'as relatedCounterUpdate' => [
                'class' => RelatedCounterUpdateBehavior::class,
                'valueAttribute' => 'paid_amount',
                'relation' => 'order',
                'relationValueAttribute' => 'paid_amount',
            ]
        ]);
        $testOrderPayment->test_order_id = $testOrder->test_order_id;
        $testOrderPayment->setAttributes($paymentData);
        $this->assertTrue($testOrderPayment->save(false));
        $testOrder->refresh();
        $this->assertEquals(10, $testOrder->paid_amount);

        $testOrderPayment->paid_amount = 20;
        $this->assertTrue($testOrderPayment->save(false));
        $testOrder->refresh();
        $this->assertEquals(20, $testOrder->paid_amount);

        $testOrderPayment->paid_amount = 15;
        $this->assertTrue($testOrderPayment->save(false));
        $testOrder->refresh();
        $this->assertEquals(15, $testOrder->paid_amount);

        $this->assertEquals(1, $testOrderPayment->delete());
        $testOrder->refresh();
        $this->assertEquals(0, $testOrder->paid_amount);
    }
}

<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\test\unit\db;

use lujie\ar\relation\behaviors\tests\unit\fixtures\models\TestOrder;
use lujie\extend\db\ActiveRecordTracer;
use lujie\extend\tests\unit\mocks\MockActiveRecord;
use Yii;

class ActiveRecordTracerTest extends \Codeception\Test\Unit
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
        $activeRecordTracer = new ActiveRecordTracer();
        $activeRecordTracer->bootstrap(null);
        $mockActiveRecord = new MockActiveRecord();
        $mockActiveRecord->beforeSave(true);
        $expected = [
            'created_by' => 0,
            'created_at' => time(),
            'updated_by' => 0,
            'updated_at' => time(),
        ];
        $attributes = $mockActiveRecord->getAttributes(['created_by', 'created_at', 'updated_by', 'updated_at']);
        $this->assertEquals($expected, $attributes);

        $mockActiveRecord = new MockActiveRecord();
        $mockActiveRecord->beforeSave(false);
        $expected = [
            'created_by' => null,
            'created_at' => null,
            'updated_by' => 0,
            'updated_at' => time(),
        ];
        $attributes = $mockActiveRecord->getAttributes(['created_by', 'created_at', 'updated_by', 'updated_at']);
        $this->assertEquals($expected, $attributes);
    }

    /**
     * @inheritdoc
     */
    public function te1stProfile(): void
    {
        $count = 100;
        $startAt = microtime(true);
        for ($i = 0; $i < $count; $i++) {
            $testOrder = new TestOrder();
            $testOrder->order_no = 'orderNo' . $i;
            $testOrder->customer_email = 'xxx' . $i . '@xxx.com';
            $testOrder->shipping_address_id = $i;
            $testOrder->order_amount = $i;
            $testOrder->paid_amount = $i;
            $testOrder->status = 0;
            $testOrder->save(false);
        }
        $endAt = microtime(true);
        $createSpendTime = $endAt - $startAt;

        $startAt = microtime(true);
        $testOrders = TestOrder::find()->all();
        $endAt = microtime(true);
        $querySpendTime = $endAt - $startAt;

        TestOrder::getDb()->createCommand()->truncateTable(TestOrder::tableName());
        $activeRecordTracer = new ActiveRecordTracer();
        Yii::$app->set('activeRecordTracer', $activeRecordTracer);
        Yii::$app->get('activeRecordTracer');
        $activeRecordTracer->bootstrap(Yii::$app);
        $startAt = microtime(true);
        for ($i = 0; $i < $count; $i++) {
            $testOrder = new TestOrder();
            $testOrder->order_no = 'orderNo' . $i;
            $testOrder->customer_email = 'xxx' . $i . '@xxx.com';
            $testOrder->shipping_address_id = $i;
            $testOrder->order_amount = $i;
            $testOrder->paid_amount = $i;
            $testOrder->status = 0;
            $testOrder->save(false);
        }
        $endAt = microtime(true);
        $createSpendTime2 = $endAt - $startAt;

        $startAt = microtime(true);
        $testOrders = TestOrder::find()->all();
        $endAt = microtime(true);
        $querySpendTime2 = $endAt - $startAt;

        $this->assertTrue(false, "$createSpendTime2 < $createSpendTime, $querySpendTime2 < $querySpendTime");
    }
}

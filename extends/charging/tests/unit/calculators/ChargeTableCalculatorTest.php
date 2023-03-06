<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\tests\unit;

use lujie\ar\relation\behaviors\tests\unit\fixtures\models\TestOrder;
use lujie\charging\calculators\ChargeableItem;
use lujie\charging\calculators\ChargeTableCalculator;
use lujie\charging\models\ChargePrice;
use lujie\charging\tests\unit\fixtures\ChargeTableFixture;
use lujie\charging\tests\unit\mocks\MockDataLoader;

class ChargeTableCalculatorTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function _fixtures(): array
    {
        return ['chargeTable' => ChargeTableFixture::class];
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testMe(): void
    {
        $chargeableItem1 = new ChargeableItem();
        $chargeableItem1->customType = 'MOCK_CHARGE_C1';
        $chargeableItem1->limitValue = 1100;
        $chargeableItem1->chargedAt = time();
        $chargeableItem1->additional = [
            'qty' => 1,
            'owner_id' => 1,
        ];
        $chargeableItem2 = new ChargeableItem();
        $chargeableItem2->customType = 'MOCK_CHARGE_C1';
        $chargeableItem2->limitValue = 2200;
        $chargeableItem2->chargedAt = time();
        $chargeableItem2->additional = [
            'qty' => 2,
            'owner_id' => 1,
        ];
        $chargeableItem22 = new ChargeableItem();
        $chargeableItem22->customType = 'MOCK_CHARGE_C1';
        $chargeableItem22->limitValue = 5500;
        $chargeableItem22->chargedAt = time();
        $chargeableItem22->additional = [
            'qty' => 1,
            'owner_id' => 1,
        ];
        $chargeableItem3 = new ChargeableItem();
        $chargeableItem3->customType = 'MOCK_CHARGE_C1';
        $chargeableItem3->limitValue = 12500;
        $chargeableItem3->chargedAt = time();
        $chargeableItem3->additional = [
            'qty' => 1,
            'owner_id' => 1,
        ];
        $chargeableItem4 = new ChargeableItem();
        $chargeableItem4->customType = 'MOCK_CHARGE_C1';
        $chargeableItem4->limitValue = 22500;
        $chargeableItem4->chargedAt = time();
        $chargeableItem4->additional = [
            'qty' => 2,
            'owner_id' => 1,
        ];
        $calculator = new ChargeTableCalculator([
            'chargeableItemLoader' => [
                'class' => MockDataLoader::class,
                'data' => [
                    '1' => $chargeableItem1,
                    '2' => $chargeableItem2,
                    '22' => $chargeableItem22,
                    '3' => $chargeableItem3,
                    '4' => $chargeableItem4,
                ]
            ]
        ]);
        $testOrder = new TestOrder(['test_order_id' => 1]);
        $chargePrice = new ChargePrice();
        $chargePrice->model_type = 'TEST_ORDER';
        $chargePrice->model_id = $testOrder->test_order_id;
        $chargePrice->charge_type = 'MOCK_CHARGE_T1';
        $chargePrice = $calculator->calculate($testOrder, $chargePrice);
        $chargePrice->save(false);
        $this->assertEquals(1, $chargePrice->price_table_id);
        $this->assertEquals(1, $chargePrice->qty);
        $this->assertEquals(120, $chargePrice->price_cent);
        $this->assertEquals(120, $chargePrice->subtotal_cent);
        $this->assertEquals(10, $chargePrice->discount_cent);
        $this->assertEquals(110, $chargePrice->grand_total_cent);
        $this->assertEquals('EUR', $chargePrice->currency);

        $testOrder = new TestOrder(['test_order_id' => 2]);
        $chargePrice = new ChargePrice();
        $chargePrice->model_type = 'TEST_ORDER';
        $chargePrice->model_id = $testOrder->test_order_id;
        $chargePrice->charge_type = 'MOCK_CHARGE_T1';
        $chargePrice = $calculator->calculate($testOrder, $chargePrice);
        $chargePrice->save(false);
        $this->assertEquals(2, $chargePrice->price_table_id);
        $this->assertEquals(2, $chargePrice->qty);
        $this->assertEquals(240, $chargePrice->price_cent);
        $this->assertEquals(480, $chargePrice->subtotal_cent);
        $this->assertEquals(96, $chargePrice->discount_cent);
        $this->assertEquals(384, $chargePrice->grand_total_cent);
        $this->assertEquals('EUR', $chargePrice->currency);

        $testOrder = new TestOrder(['test_order_id' => 22]);
        $chargePrice = new ChargePrice();
        $chargePrice->model_type = 'TEST_ORDER';
        $chargePrice->model_id = $testOrder->test_order_id;
        $chargePrice->charge_type = 'MOCK_CHARGE_T1';
        $chargePrice = $calculator->calculate($testOrder, $chargePrice);
        $chargePrice->save(false);
        $this->assertEquals(2, $chargePrice->price_table_id);
        $this->assertEquals(1, $chargePrice->qty);
        $this->assertEquals(270, $chargePrice->price_cent);
        $this->assertEquals(270, $chargePrice->subtotal_cent);
        $this->assertEquals(54, $chargePrice->discount_cent);
        $this->assertEquals(216, $chargePrice->grand_total_cent);
        $this->assertEquals('EUR', $chargePrice->currency);

        $testOrder = new TestOrder(['test_order_id' => 3]);
        $chargePrice = new ChargePrice();
        $chargePrice->model_type = 'TEST_ORDER';
        $chargePrice->model_id = $testOrder->test_order_id;
        $chargePrice->charge_type = 'MOCK_CHARGE_T1';
        $chargePrice = $calculator->calculate($testOrder, $chargePrice);
        $chargePrice->save(false);
        $this->assertEquals(3, $chargePrice->price_table_id);
        $this->assertEquals(1, $chargePrice->qty);
        $this->assertEquals(480, $chargePrice->price_cent);
        $this->assertEquals(480, $chargePrice->subtotal_cent);
        $this->assertEquals(144, $chargePrice->discount_cent);
        $this->assertEquals(336, $chargePrice->grand_total_cent);
        $this->assertEquals('EUR', $chargePrice->currency);

        $testOrder = new TestOrder(['test_order_id' => 4]);
        $chargePrice = new ChargePrice();
        $chargePrice->model_type = 'TEST_ORDER';
        $chargePrice->model_id = $testOrder->test_order_id;
        $chargePrice->charge_type = 'MOCK_CHARGE_T1';
        $chargePrice = $calculator->calculate($testOrder, $chargePrice);
        $chargePrice->save(false);
        $this->assertEquals(4, $chargePrice->price_table_id);
        $this->assertEquals(2, $chargePrice->qty);
        $this->assertEquals(780, $chargePrice->price_cent);
        $this->assertEquals(1560, $chargePrice->subtotal_cent);
        $this->assertEquals(624, $chargePrice->discount_cent);
        $this->assertEquals(936, $chargePrice->grand_total_cent);
        $this->assertEquals('EUR', $chargePrice->currency);
    }
}

<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\tests\unit;

use lujie\ar\relation\behaviors\tests\unit\fixtures\models\TestOrder;
use lujie\charging\calculators\ChargeableItem;
use lujie\charging\calculators\ChargeTableCalculator;
use lujie\charging\models\ChargePrice;
use lujie\charging\models\ChargeTable;
use lujie\charging\tests\unit\fixtures\ChargeTableFixture;
use lujie\charging\tests\unit\mocks\MockDataLoader;

class ChargeTableCalculatorTest extends \Codeception\Test\Unit
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
        $chargeableItem1->limitValue = 11000;
        $chargeableItem1->chargedAt = time();
        $chargeableItem1->additional = [
            'qty' => 1,
            'owner_id' => 1,
        ];
        $chargeableItem2 = new ChargeableItem();
        $chargeableItem2->customType = 'MOCK_CHARGE_C1';
        $chargeableItem2->limitValue = 29000;
        $chargeableItem2->chargedAt = time();
        $chargeableItem2->additional = [
            'qty' => 2,
            'owner_id' => 1,
        ];
        $calculator = new ChargeTableCalculator([
            'chargeableItemLoader' => [
                'class' => MockDataLoader::class,
                'data' => [
                    '1' => $chargeableItem1,
                    '2' => $chargeableItem2,
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
        $this->assertEquals(12, $chargePrice->discount_cent);
        $this->assertEquals(108, $chargePrice->grand_total_cent);
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
        $this->assertEquals(390, $chargePrice->price_cent);
        $this->assertEquals(94, $chargePrice->discount_cent);
        $this->assertEquals(686, $chargePrice->grand_total_cent);
        $this->assertEquals('EUR', $chargePrice->currency);
    }
}

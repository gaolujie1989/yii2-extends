<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\tests\unit;

use lujie\ar\relation\behaviors\tests\unit\fixtures\models\TestOrder;
use lujie\charging\calculators\ShippingItem;
use lujie\charging\calculators\ShippingTableCalculator;
use lujie\charging\models\ChargePrice;
use lujie\charging\tests\unit\fixtures\ShippingTableFixture;
use lujie\charging\tests\unit\mocks\MockDataLoader;

class ShippingTableCalculatorTest extends \Codeception\Test\Unit
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
        return ['shippingTable' => ShippingTableFixture::class];
    }

    public function testMe(): void
    {
        $shippingItem1 = new ShippingItem();
        $shippingItem1->carrier = 'GLS';
        $shippingItem1->departure = 'DE';
        $shippingItem1->destination = 'DE';
        $shippingItem1->weightG = 1500;
        $shippingItem1->lengthMM = 900;
        $shippingItem1->widthMM = 500;
        $shippingItem1->heightMM = 300;
        $shippingItem1->additional = [
            'qty' => 1,
            'owner_id' => 1,
        ];
        $calculator = new ShippingTableCalculator([
            'shippingItemLoader' => [
                'class' => MockDataLoader::class,
                'data' => [
                    '1' => $shippingItem1,
                ]
            ]
        ]);
        $testOrder = new TestOrder(['test_order_id' => 1]);
        $chargePrice = new ChargePrice();
        $chargePrice->model_type = 'TEST_ORDER';
        $chargePrice->model_id = $testOrder->test_order_id;
        $chargePrice->charge_type = 'MOCK_CHARGE_SHIPPING';
        $chargePrice = $calculator->calculate($testOrder, $chargePrice);
        $this->assertEquals(454, $chargePrice->price_cent);
        $this->assertEquals('EUR', $chargePrice->currency);
        $this->assertEquals(1, $chargePrice->price_table_id);
    }
}

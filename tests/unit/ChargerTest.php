<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\tests\unit;

use lujie\ar\relation\behaviors\tests\unit\fixtures\models\TestOrder;
use lujie\charging\Charger;
use lujie\charging\models\ChargePrice;
use lujie\charging\tests\unit\mocks\MockCalculator;
use lujie\data\loader\ArrayDataLoader;
use Yii;

class ChargerTest extends \Codeception\Test\Unit
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

    public function testMe(): void
    {
        $mockCharge1 = [
            'custom_type' => 'C1',
            'qty' => 1,
            'owner_id' => 1,
            'parent_model_id' => 0,
            'price_table_id' => 1,
            'price_cent' => 123,
            'currency' => 'USD',
        ];
        $mockCharge2 = [
            'custom_type' => 'C2',
            'qty' => 2,
            'owner_id' => 1,
            'parent_model_id' => 0,
            'price_table_id' => 2,
            'price_cent' => 321,
            'currency' => 'CNY',
        ];
        MockCalculator::$mockChargePrices = [
            'MOCK_CHARGE1' => [
                $mockCharge1
            ],
            'MOCK_CHARGE2' => [
                $mockCharge2
            ]
        ];
        $charger = new Charger([
            'chargeConfig' => [
                'TEST_ORDER' => [TestOrder::class, ['MOCK_CHARGE1', 'MOCK_CHARGE2']],
            ],
            'chargeCalculatorLoader' => [
                'class' => ArrayDataLoader::class,
                'data' => [
                    'MOCK_CHARGE1' => MockCalculator::class,
                    'MOCK_CHARGE2' => MockCalculator::class
                ]
            ]
        ]);
        $charger->bootstrap(Yii::$app);
        $testOrder = new TestOrder([
            'order_no' => 'XXX-111',
            'customer_email' => 'xxx@xxx.com',
            'shipping_address_id' => 1,
            'order_amount' => 12.3,
            'paid_amount' => 12.3,
            'status' => 0,
        ]);
        $this->assertTrue($testOrder->save(false));
        $query = ChargePrice::find()
            ->modelType('TEST_ORDER')
            ->modelId($testOrder->test_order_id);
        $this->assertEquals(2, $query->count());
        $chargePrice1 = (clone $query)->chargeType('MOCK_CHARGE1')->one();
        $this->assertNotNull($chargePrice1);
        $this->assertEquals($mockCharge1, $chargePrice1->getAttributes(array_keys($mockCharge1)));
        $chargePrice2 = (clone $query)->chargeType('MOCK_CHARGE2')->one();
        $this->assertNotNull($chargePrice2);
        $this->assertEquals($mockCharge2, $chargePrice2->getAttributes(array_keys($mockCharge2)));
    }
}

<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tests\unit;

use lujie\fulfillment\models\FulfillmentItemValue;
use lujie\fulfillment\tests\unit\fixtures\FulfillmentDailyStockFixture;
use lujie\fulfillment\tests\unit\fixtures\FulfillmentDailyStockMovementFixture;
use lujie\fulfillment\tests\unit\mocks\MockFulfillmentItemValueCalculator;

class ItemValueCalculatorTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function _fixtures(): array
    {
        return [
            'dailyStock' => FulfillmentDailyStockFixture::class,
            'dailyStockMovement' => FulfillmentDailyStockMovementFixture::class
        ];
    }

    /**
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function testMe()
    {
        $itemValueCalculator = new MockFulfillmentItemValueCalculator();
        $itemValueCalculator->calculateMovementsItemValues(1, '2020-12-01', '2020-12-05');
        $query = FulfillmentItemValue::find()->itemId(1)->warehouseId(1);
        $this->assertEquals(2, $query->count());
        $expected = [
            [
                'fulfillment_daily_stock_movement_id' => 1,
                'item_id' => 1,
                'warehouse_id' => 1,
                'external_item_key' => 'ITEM-1',
                'external_warehouse_key' => 'W01',
                'old_item_value_cent' => 0,
                'old_item_qty' => 0,
                'inbound_item_value_cent' => 1000,
                'inbound_item_qty' => 10,
                'new_item_value_cent' => 1000,
                'new_item_qty' => 10,
                'currency' => 'EUR',
                'value_date' => '2020-12-01',
            ],
            [
                'fulfillment_daily_stock_movement_id' => 3,
                'item_id' => 1,
                'warehouse_id' => 1,
                'external_item_key' => 'ITEM-1',
                'external_warehouse_key' => 'W01',
                'old_item_value_cent' => 1000,
                'old_item_qty' => 5,
                'inbound_item_value_cent' => 2000,
                'inbound_item_qty' => 10,
                'new_item_value_cent' => 1667,
                'new_item_qty' => 15,
                'currency' => 'EUR',
                'value_date' => '2020-12-03',
            ],
        ];
        $this->assertEquals($expected, $query->select(array_keys(reset($expected)))->asArray()->all());
    }
}

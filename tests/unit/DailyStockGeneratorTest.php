<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tests\unit;


use lujie\fulfillment\DailyStockGenerator;
use lujie\fulfillment\models\FulfillmentDailyStock;
use lujie\fulfillment\models\FulfillmentDailyStockMovement;
use lujie\fulfillment\tests\unit\fixtures\FulfillmentWarehouseStockMovementFixture;
use yii\helpers\ArrayHelper;

class DailyStockGeneratorTest extends \Codeception\Test\Unit
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

    public function _fixtures(): array
    {
        return [
            'fulfillmentWarehouseStockMovement' => FulfillmentWarehouseStockMovementFixture::class,
        ];
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testGenerateDailyStockMovements()
    {
        $dailyStockGenerator = new DailyStockGenerator();
        $this->assertTrue($dailyStockGenerator->generateDailyStockMovements('2020-11-14', '2020-11-15'));
        $expected = [
            [
                'fulfillment_account_id' => 1,
                'item_id' => 1,
                'warehouse_id' => 1,
                'external_item_key' => 'ITEM-1',
                'external_warehouse_key' => 'W-1',
                'movement_qty' => '-4',
                'movement_count' => '1',
                'movement_type' => 'OUTBOUND',
                'movement_date' => '2020-11-14'
            ],
            [
                'fulfillment_account_id' => 1,
                'item_id' => 1,
                'warehouse_id' => 1,
                'external_item_key' => 'ITEM-1',
                'external_warehouse_key' => 'W-1',
                'movement_qty' => '50',
                'movement_count' => '2',
                'movement_type' => 'INBOUND',
                'movement_date' => '2020-11-15'
            ],
            [
                'fulfillment_account_id' => 1,
                'item_id' => 1,
                'warehouse_id' => 1,
                'external_item_key' => 'ITEM-1',
                'external_warehouse_key' => 'W-1',
                'movement_qty' => '-11',
                'movement_count' => '2',
                'movement_type' => 'OUTBOUND',
                'movement_date' => '2020-11-15'
            ],
        ];
        $dailyStockMovements = FulfillmentDailyStockMovement::find()
            ->select(array_keys($expected[0]))
            ->asArray()
            ->all();
        $indexClosure = static function ($values) {
            return $values['movement_date'] . $values['movement_type'];
        };
        $expected = ArrayHelper::index($expected, $indexClosure);
        $dailyStockMovements = ArrayHelper::index($dailyStockMovements, $indexClosure);
        $this->assertEquals($expected, $dailyStockMovements);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testGenerateDailyStocks()
    {
        $dailyStockGenerator = new DailyStockGenerator();
        $this->assertTrue($dailyStockGenerator->generateDailyStockMovements('2020-11-11', '2020-11-15'));
        $this->assertTrue($dailyStockGenerator->generateDailyStocks('2020-11-11', '2020-11-15'));
        $expected = [
            [
                'fulfillment_account_id' => '1',
                'item_id' => '1',
                'warehouse_id' => '1',
                'external_item_key' => 'ITEM-1',
                'external_warehouse_key' => 'W-1',
                'stock_qty' => '99',
                'stock_date' => '2020-11-11',
            ],
            [
                'fulfillment_account_id' => '1',
                'item_id' => '1',
                'warehouse_id' => '1',
                'external_item_key' => 'ITEM-1',
                'external_warehouse_key' => 'W-1',
                'stock_qty' => '94',
                'stock_date' => '2020-11-12',
            ],
            [
                'fulfillment_account_id' => '1',
                'item_id' => '1',
                'warehouse_id' => '1',
                'external_item_key' => 'ITEM-1',
                'external_warehouse_key' => 'W-1',
                'stock_qty' => '94',
                'stock_date' => '2020-11-13',
            ],
            [
                'fulfillment_account_id' => '1',
                'item_id' => '1',
                'warehouse_id' => '1',
                'external_item_key' => 'ITEM-1',
                'external_warehouse_key' => 'W-1',
                'stock_qty' => '90',
                'stock_date' => '2020-11-14',
            ],
            [
                'fulfillment_account_id' => '1',
                'item_id' => '1',
                'warehouse_id' => '1',
                'external_item_key' => 'ITEM-1',
                'external_warehouse_key' => 'W-1',
                'stock_qty' => '129',
                'stock_date' => '2020-11-15',
            ],
        ];
        $dailyStocks = FulfillmentDailyStock::find()
            ->select(array_keys($expected[0]))
            ->asArray()
            ->all();
        $expected = ArrayHelper::index($expected, 'stock_date');
        $dailyStockMovements = ArrayHelper::index($dailyStocks, 'stock_date');
        $this->assertEquals($expected, $dailyStockMovements);
    }
}

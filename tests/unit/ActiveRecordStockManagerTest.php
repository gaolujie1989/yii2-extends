<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\stock\tests\unit;


use lujie\stock\ActiveRecordStockManager;
use lujie\stock\models\Stock;
use lujie\stock\models\StockMovement;
use lujie\stock\StockValueBehavior;

class ActiveRecordStockManagerTest extends \Codeception\Test\Unit
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
     * @inheritdoc
     */
    public function testInbound(): void
    {
        $stockManager = new ActiveRecordStockManager([
            'stockClass' => Stock::class,
            'stockMovementClass' => StockMovement::class,
            'as stockValue' => [
                'class' => StockValueBehavior::class
            ]
        ]);
        $itemId = 1;
        $locationId = 2;
        $inboundQty = 5;
        $extraData = [
            'move_item_value' => 1.1
        ];

        $this->assertTrue($stockManager->inbound($itemId, $locationId, $inboundQty, $extraData));
        $stock = Stock::find()->itemId($itemId)->locationId($locationId)
            ->select(['stock_qty', 'stock_item_value'])
            ->asArray()
            ->one();
        $expected = ['stock_qty' => 5, 'stock_item_value' => 1.1];
        $this->assertEquals($expected, $stock);

        $extraData = [
            'move_item_value' => 1.2
        ];
        $this->assertTrue($stockManager->inbound($itemId, $locationId, $inboundQty, $extraData));
        $stock = Stock::find()->itemId($itemId)->locationId($locationId)
            ->select(['stock_qty', 'stock_item_value'])
            ->asArray()
            ->one();
        $expected = ['stock_qty' => 10, 'stock_item_value' => 1.15];
        $this->assertEquals($expected, $stock);
    }
}

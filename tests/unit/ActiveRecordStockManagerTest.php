<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\stock\tests\unit;

use lujie\stock\ActiveRecordStockManager;
use lujie\stock\BaseStockManager;
use lujie\stock\models\Stock;
use lujie\stock\models\StockMovement;
use lujie\stock\StockConst;
use lujie\stock\StockValueBehavior;
use yii\base\InvalidArgumentException;

class ActiveRecordStockManagerTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @return BaseStockManager
     * @inheritdoc
     */
    private function getStockManager(): BaseStockManager
    {
        $stockManager = new ActiveRecordStockManager([
            'stockClass' => Stock::class,
            'stockMovementClass' => StockMovement::class,
            'as stockValue' => [
                'class' => StockValueBehavior::class
            ]
        ]);
        return $stockManager;
    }

    /**
     * @throws \Throwable
     * @inheritdoc
     */
    public function testInbound(): void
    {
        $stockManager = $this->getStockManager();
        $itemId = 1;
        $locationId = 2;
        $inboundQty = 5;
        $extraData = [
            'moved_item_value' => 1.5
        ];

        $this->assertTrue($stockManager->inbound($itemId, $locationId, $inboundQty, $extraData));
        $stock = Stock::find()->itemId($itemId)->locationId($locationId)
            ->select(['stock_qty', 'stock_item_value'])
            ->asArray()
            ->one();
        $expected = ['stock_qty' => 5, 'stock_item_value' => 1.5];
        $this->assertEquals($expected, $stock);
        $movement = StockMovement::find()->itemId($itemId)->locationId($locationId)->orderByMovementId(SORT_DESC)
            ->select(['moved_qty', 'moved_item_value', 'reason'])
            ->asArray()
            ->one();
        $expected = ['moved_qty' => 5, 'moved_item_value' => 1.5, 'reason' => StockConst::MOVEMENT_REASON_INBOUND];
        $this->assertEquals($expected, $movement);

        //transfer again
        $inboundQty = 15;
        $extraData = [
            'moved_item_value' => 2.5
        ];
        $this->assertTrue($stockManager->inbound($itemId, $locationId, $inboundQty, $extraData));
        $stock = Stock::find()->itemId($itemId)->locationId($locationId)
            ->select(['stock_qty', 'stock_item_value'])
            ->asArray()
            ->one();
        $expected = ['stock_qty' => 20, 'stock_item_value' => 2.25];
        $this->assertEquals($expected, $stock);
        $movement = StockMovement::find()->itemId($itemId)->locationId($locationId)->orderByMovementId(SORT_DESC)
            ->select(['moved_qty', 'moved_item_value', 'reason'])
            ->asArray()
            ->one();
        $expected = ['moved_qty' => 15, 'moved_item_value' => 2.5, 'reason' => StockConst::MOVEMENT_REASON_INBOUND];
        $this->assertEquals($expected, $movement);
    }

    /**
     * @throws \Throwable
     * @inheritdoc
     */
    public function testOutbound(): void
    {
        (new Stock([
            'item_id' => 1,
            'location_id' => 2,
            'stock_qty' => 20,
            'stock_item_value' => 10
        ]))->save(false);

        $stockManager = $this->getStockManager();
        $itemId = 1;
        $locationId = 2;
        $outboundQty = 5;
        $extraData = [
            'moved_item_value' => 20
        ];

        $this->assertTrue($stockManager->outbound($itemId, $locationId, $outboundQty, $extraData));
        $stock = Stock::find()->itemId($itemId)->locationId($locationId)
            ->select(['stock_qty', 'stock_item_value'])
            ->asArray()
            ->one();
        $expected = ['stock_qty' => 15, 'stock_item_value' => 10];
        $this->assertEquals($expected, $stock);
        $movement = StockMovement::find()->itemId($itemId)->locationId($locationId)->orderByMovementId(SORT_DESC)
            ->select(['moved_qty', 'moved_item_value', 'reason'])
            ->asArray()
            ->one();
        $expected = ['moved_qty' => -5, 'moved_item_value' => 20, 'reason' => StockConst::MOVEMENT_REASON_OUTBOUND];
        $this->assertEquals($expected, $movement);

        $outboundQty = 16;
        try {
            $stockManager->outbound($itemId, $locationId, $outboundQty, $extraData);
            $this->assertTrue(false, 'Should throw exception');
        } catch (\Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }
    }

    /**
     * @throws \Throwable
     * @inheritdoc
     */
    public function testTransfer(): void
    {
        (new Stock([
            'item_id' => 1,
            'location_id' => 2,
            'stock_qty' => 20,
            'stock_item_value' => 20
        ]))->save(false);
        (new Stock([
            'item_id' => 1,
            'location_id' => 3,
            'stock_qty' => 10,
            'stock_item_value' => 10
        ]))->save(false);

        $stockManager = $this->getStockManager();
        $itemId = 1;
        $fromLocationId = 2;
        $toLocationId = 3;
        $transfer = 5;
        $this->assertTrue($stockManager->transfer($itemId, $fromLocationId, $toLocationId, $transfer));
        $stock = Stock::find()->itemId($itemId)->locationId($fromLocationId)
            ->select(['stock_qty', 'stock_item_value'])
            ->asArray()
            ->one();
        $expected = ['stock_qty' => 15, 'stock_item_value' => 20];
        $this->assertEquals($expected, $stock);
        $movement = StockMovement::find()->itemId($itemId)->locationId($fromLocationId)->orderByMovementId(SORT_DESC)
            ->select(['moved_qty', 'moved_item_value', 'reason'])
            ->asArray()
            ->one();
        $expected = ['moved_qty' => -5, 'moved_item_value' => 0, 'reason' => StockConst::MOVEMENT_REASON_TRANSFER_OUT];
        $this->assertEquals($expected, $movement);

        $stock = Stock::find()->itemId($itemId)->locationId($toLocationId)
            ->select(['stock_qty', 'stock_item_value'])
            ->asArray()
            ->one();
        $expected = ['stock_qty' => 15, 'stock_item_value' => 13.33];
        $this->assertEquals($expected, $stock);
        $movement = StockMovement::find()->itemId($itemId)->locationId($toLocationId)->orderByMovementId(SORT_DESC)
            ->select(['moved_qty', 'moved_item_value', 'reason'])
            ->asArray()
            ->one();
        $expected = ['moved_qty' => 5, 'moved_item_value' => 20, 'reason' => StockConst::MOVEMENT_REASON_TRANSFER_IN];
        $this->assertEquals($expected, $movement);

        //transfer again
        $this->assertTrue($stockManager->transfer($itemId, $fromLocationId, $toLocationId, $transfer));
        $stock = Stock::find()->itemId($itemId)->locationId($fromLocationId)
            ->select(['stock_qty', 'stock_item_value'])
            ->asArray()
            ->one();
        $expected = ['stock_qty' => 10, 'stock_item_value' => 20];
        $this->assertEquals($expected, $stock);
        $movement = StockMovement::find()->itemId($itemId)->locationId($fromLocationId)->orderByMovementId(SORT_DESC)
            ->select(['moved_qty', 'moved_item_value', 'reason'])
            ->asArray()
            ->one();
        $expected = ['moved_qty' => -5, 'moved_item_value' => 0, 'reason' => StockConst::MOVEMENT_REASON_TRANSFER_OUT];
        $this->assertEquals($expected, $movement);

        $stock = Stock::find()->itemId($itemId)->locationId($toLocationId)
            ->select(['stock_qty', 'stock_item_value'])
            ->asArray()
            ->one();
        $expected = ['stock_qty' => 20, 'stock_item_value' => 15];
        $this->assertEquals($expected, $stock);
        $movement = StockMovement::find()->itemId($itemId)->locationId($toLocationId)->orderByMovementId(SORT_DESC)
            ->select(['moved_qty', 'moved_item_value', 'reason'])
            ->asArray()
            ->one();
        $expected = ['moved_qty' => 5, 'moved_item_value' => 20, 'reason' => StockConst::MOVEMENT_REASON_TRANSFER_IN];
        $this->assertEquals($expected, $movement);
    }

    /**
     * @throws \Throwable
     * @inheritdoc
     */
    public function testCorrect(): void
    {
        (new Stock([
            'item_id' => 1,
            'location_id' => 2,
            'stock_qty' => 20,
            'stock_item_value' => 20
        ]))->save(false);

        $stockManager = $this->getStockManager();
        $itemId = 1;
        $locationId = 2;
        $correctQty = 5;
        $this->assertTrue($stockManager->correct($itemId, $locationId, $correctQty));
        $stock = Stock::find()->itemId($itemId)->locationId($locationId)
            ->select(['stock_qty', 'stock_item_value'])
            ->asArray()
            ->one();
        $expected = ['stock_qty' => $correctQty, 'stock_item_value' => 20];
        $this->assertEquals($expected, $stock);
        $movement = StockMovement::find()->itemId($itemId)->locationId($locationId)->orderByMovementId(SORT_DESC)
            ->select(['moved_qty', 'moved_item_value', 'reason'])
            ->asArray()
            ->one();
        $expected = ['moved_qty' => -15, 'moved_item_value' => 0, 'reason' => StockConst::MOVEMENT_REASON_CORRECT];
        $this->assertEquals($expected, $movement);
    }
}

<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\stock\tests\unit;

use lujie\stock\BaseStockManager;
use lujie\stock\DbStockManager;
use lujie\stock\models\Stock;
use lujie\stock\models\StockMovement;
use lujie\stock\StockConst;
use lujie\stock\StockValueBehavior;
use PHPUnit\Framework\Assert;
use yii\base\InvalidArgumentException;

class DbStockManagerTest extends \Codeception\Test\Unit
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
        $stockManager = new DbStockManager([
            'db' => 'db',
            'stockTable' => Stock::tableName(),
            'movementTable' => StockMovement::tableName(),
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
            'item_value_cent' => 150
        ];

        Assert::assertNotNull($stockManager->inbound($itemId, $locationId, $inboundQty, $extraData));
        $stock = Stock::find()->itemId($itemId)->locationId($locationId)
            ->select(['stock_qty', 'item_value_cent'])
            ->asArray()
            ->one();
        $expected = ['stock_qty' => 5, 'item_value_cent' => 150];
        Assert::assertEquals($expected, $stock);
        $movement = StockMovement::find()->itemId($itemId)->locationId($locationId)->orderByMovementId(SORT_DESC)
            ->select(['moved_qty', 'item_value_cent', 'reason'])
            ->asArray()
            ->one();
        $expected = ['moved_qty' => 5, 'item_value_cent' => 150, 'reason' => StockConst::MOVEMENT_REASON_INBOUND];
        Assert::assertEquals($expected, $movement);

        //transfer again
        $inboundQty = 15;
        $extraData = [
            'item_value_cent' => 250
        ];
        Assert::assertNotNull($stockManager->inbound($itemId, $locationId, $inboundQty, $extraData));
        $stock = Stock::find()->itemId($itemId)->locationId($locationId)
            ->select(['stock_qty', 'item_value_cent'])
            ->asArray()
            ->one();
        $expected = ['stock_qty' => 20, 'item_value_cent' => 225];
        Assert::assertEquals($expected, $stock);
        $movement = StockMovement::find()->itemId($itemId)->locationId($locationId)->orderByMovementId(SORT_DESC)
            ->select(['moved_qty', 'item_value_cent', 'reason'])
            ->asArray()
            ->one();
        $expected = ['moved_qty' => 15, 'item_value_cent' => 250, 'reason' => StockConst::MOVEMENT_REASON_INBOUND];
        Assert::assertEquals($expected, $movement);
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
            'item_value_cent' => 10
        ]))->save(false);

        $stockManager = $this->getStockManager();
        $itemId = 1;
        $locationId = 2;
        $outboundQty = 5;
        $extraData = [
            'item_value_cent' => 20
        ];

        Assert::assertNotNull($stockManager->outbound($itemId, $locationId, $outboundQty, $extraData));
        $stock = Stock::find()->itemId($itemId)->locationId($locationId)
            ->select(['stock_qty', 'item_value_cent'])
            ->asArray()
            ->one();
        $expected = ['stock_qty' => 15, 'item_value_cent' => 10];
        Assert::assertEquals($expected, $stock);
        $movement = StockMovement::find()->itemId($itemId)->locationId($locationId)->orderByMovementId(SORT_DESC)
            ->select(['moved_qty', 'item_value_cent', 'reason'])
            ->asArray()
            ->one();
        $expected = ['moved_qty' => -5, 'item_value_cent' => 20, 'reason' => StockConst::MOVEMENT_REASON_OUTBOUND];
        Assert::assertEquals($expected, $movement);

        $outboundQty = 16;
        try {
            $stockManager->outbound($itemId, $locationId, $outboundQty, $extraData);
            Assert::assertTrue(false, 'Should throw exception');
        } catch (\Exception $e) {
            Assert::assertInstanceOf(InvalidArgumentException::class, $e);
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
            'item_value_cent' => 20
        ]))->save(false);
        (new Stock([
            'item_id' => 1,
            'location_id' => 3,
            'stock_qty' => 10,
            'item_value_cent' => 10
        ]))->save(false);

        $stockManager = $this->getStockManager();
        $itemId = 1;
        $fromLocationId = 2;
        $toLocationId = 3;
        $transfer = 5;
        Assert::assertNotNull($stockManager->transfer($itemId, $fromLocationId, $toLocationId, $transfer));
        $stock = Stock::find()->itemId($itemId)->locationId($fromLocationId)
            ->select(['stock_qty', 'item_value_cent'])
            ->asArray()
            ->one();
        $expected = ['stock_qty' => 15, 'item_value_cent' => 20];
        Assert::assertEquals($expected, $stock);
        $movement = StockMovement::find()->itemId($itemId)->locationId($fromLocationId)->orderByMovementId(SORT_DESC)
            ->select(['moved_qty', 'item_value_cent', 'reason'])
            ->asArray()
            ->one();
        $expected = ['moved_qty' => -5, 'item_value_cent' => 0, 'reason' => StockConst::MOVEMENT_REASON_TRANSFER_OUT];
        Assert::assertEquals($expected, $movement);

        $stock = Stock::find()->itemId($itemId)->locationId($toLocationId)
            ->select(['stock_qty', 'item_value_cent'])
            ->asArray()
            ->one();
        $expected = ['stock_qty' => 15, 'item_value_cent' => 13];
        Assert::assertEquals($expected, $stock);
        $movement = StockMovement::find()->itemId($itemId)->locationId($toLocationId)->orderByMovementId(SORT_DESC)
            ->select(['moved_qty', 'item_value_cent', 'reason'])
            ->asArray()
            ->one();
        $expected = ['moved_qty' => 5, 'item_value_cent' => 20, 'reason' => StockConst::MOVEMENT_REASON_TRANSFER_IN];
        Assert::assertEquals($expected, $movement);

        //transfer again
        Assert::assertNotNull($stockManager->transfer($itemId, $fromLocationId, $toLocationId, $transfer));
        $stock = Stock::find()->itemId($itemId)->locationId($fromLocationId)
            ->select(['stock_qty', 'item_value_cent'])
            ->asArray()
            ->one();
        $expected = ['stock_qty' => 10, 'item_value_cent' => 20];
        Assert::assertEquals($expected, $stock);
        $movement = StockMovement::find()->itemId($itemId)->locationId($fromLocationId)->orderByMovementId(SORT_DESC)
            ->select(['moved_qty', 'item_value_cent', 'reason'])
            ->asArray()
            ->one();
        $expected = ['moved_qty' => -5, 'item_value_cent' => 0, 'reason' => StockConst::MOVEMENT_REASON_TRANSFER_OUT];
        Assert::assertEquals($expected, $movement);

        $stock = Stock::find()->itemId($itemId)->locationId($toLocationId)
            ->select(['stock_qty', 'item_value_cent'])
            ->asArray()
            ->one();
        $expected = ['stock_qty' => 20, 'item_value_cent' => 15];
        Assert::assertEquals($expected, $stock);
        $movement = StockMovement::find()->itemId($itemId)->locationId($toLocationId)->orderByMovementId(SORT_DESC)
            ->select(['moved_qty', 'item_value_cent', 'reason'])
            ->asArray()
            ->one();
        $expected = ['moved_qty' => 5, 'item_value_cent' => 20, 'reason' => StockConst::MOVEMENT_REASON_TRANSFER_IN];
        Assert::assertEquals($expected, $movement);
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
            'item_value_cent' => 20
        ]))->save(false);

        $stockManager = $this->getStockManager();
        $itemId = 1;
        $locationId = 2;
        $correctQty = 5;
        Assert::assertNotNull($stockManager->correct($itemId, $locationId, $correctQty));
        $stock = Stock::find()->itemId($itemId)->locationId($locationId)
            ->select(['stock_qty', 'item_value_cent'])
            ->asArray()
            ->one();
        $expected = ['stock_qty' => $correctQty, 'item_value_cent' => 20];
        Assert::assertEquals($expected, $stock);
        $movement = StockMovement::find()->itemId($itemId)->locationId($locationId)->orderByMovementId(SORT_DESC)
            ->select(['moved_qty', 'item_value_cent', 'reason'])
            ->asArray()
            ->one();
        $expected = ['moved_qty' => -15, 'item_value_cent' => 0, 'reason' => StockConst::MOVEMENT_REASON_CORRECT];
        Assert::assertEquals($expected, $movement);
    }
}

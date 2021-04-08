<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\stock\tests\unit;

use lujie\stock\ActiveRecordStockManager;
use lujie\stock\BaseStockManager;
use lujie\stock\models\Stock;
use lujie\stock\models\StockMovement;
use lujie\stock\MovementException;
use lujie\stock\StockConst;
use lujie\stock\StockValueBehavior;
use PHPUnit\Framework\Assert;

class ActiveRecordStockManagerTest extends \Codeception\Test\Unit
{
    /**
     * @return BaseStockManager
     * @inheritdoc
     */
    protected function getStockManager(): BaseStockManager
    {
        return new ActiveRecordStockManager([
            'stockClass' => Stock::class,
            'movementClass' => StockMovement::class,
            'as stockValue' => [
                'class' => StockValueBehavior::class
            ]
        ]);
    }

    /**
     * @param array $data
     * @return Stock
     * @inheritdoc
     */
    protected function createStock($data = []): Stock
    {
        $stock = new Stock();
        $stock->item_id = 0;
        $stock->location_id = 0;
        $stock->stock_qty = 0;
        $stock->item_value_cent = 0;
        $stock->setAttributes($data);
        $stock->save(false);
        return $stock;
    }

    /**
     * @param $movement
     * @inheritdoc
     */
    protected function findMovement($movement): StockMovement
    {
        return StockMovement::findOne($movement['stock_movement_id']);
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
        $additional = [
            'item_value_cent' => 150
        ];

        $movement = $stockManager->inbound($itemId, $locationId, $inboundQty, $additional);
        Assert::assertNotNull($movement);
        $movement = $this->findMovement($movement);

        $stock = Stock::find()->itemId($itemId)->locationId($locationId)->one();
        $expected = ['stock_qty' => 5, 'item_value_cent' => 150];
        Assert::assertEquals($expected, $stock->getAttributes(array_keys($expected)));

        $expected = ['moved_qty' => 5, 'item_value_cent' => 150, 'reason' => StockConst::MOVEMENT_REASON_INBOUND];
        Assert::assertEquals($expected, $movement->getAttributes(array_keys($expected)));

        //transfer again
        $inboundQty = 15;
        $additional = [
            'item_value_cent' => 250
        ];
        $movement = $stockManager->inbound($itemId, $locationId, $inboundQty, $additional);
        Assert::assertNotNull($movement);
        $movement = $this->findMovement($movement);

        $stock = Stock::find()->itemId($itemId)->locationId($locationId)->one();
        $expected = ['stock_qty' => 20, 'item_value_cent' => 225];
        Assert::assertEquals($expected, $stock->getAttributes(array_keys($expected)));

        $expected = ['moved_qty' => 15, 'item_value_cent' => 250, 'reason' => StockConst::MOVEMENT_REASON_INBOUND];
        Assert::assertEquals($expected, $movement->getAttributes(array_keys($expected)));
    }

    /**
     * @throws \Throwable
     * @inheritdoc
     */
    public function testOutbound(): void
    {
        $stockManager = $this->getStockManager();
        $itemId = 1;
        $locationId = 2;

        $stock = $this->createStock([
            'item_id' => $itemId,
            'location_id' => $locationId,
            'stock_qty' => 20,
            'item_value_cent' => 10
        ]);

        $outboundQty = 5;
        $movement = $stockManager->outbound($itemId, $locationId, $outboundQty);
        Assert::assertNotNull($movement);
        $movement = $this->findMovement($movement);

        $stock->refresh();
        $expected = ['stock_qty' => 15, 'item_value_cent' => 10];
        Assert::assertEquals($expected, $stock->getAttributes(array_keys($expected)));

        $expected = ['moved_qty' => -5, 'item_value_cent' => 10, 'reason' => StockConst::MOVEMENT_REASON_OUTBOUND];
        Assert::assertEquals($expected, $movement->getAttributes(array_keys($expected)));

        $outboundQty = 16;
        $this->expectException(MovementException::class);
        $stockManager->outbound($itemId, $locationId, $outboundQty);
    }

    /**
     * @throws \Throwable
     * @inheritdoc
     */
    public function testTransfer(): void
    {
        $stockManager = $this->getStockManager();
        $itemId = 1;
        $fromLocationId = 2;
        $toLocationId = 3;

        $fromStock = $this->createStock([
            'item_id' => $itemId,
            'location_id' => $fromLocationId,
            'stock_qty' => 20,
            'item_value_cent' => 20
        ]);
        $toStock = $this->createStock([
            'item_id' => $itemId,
            'location_id' => $toLocationId,
            'stock_qty' => 10,
            'item_value_cent' => 10
        ]);

        $transfer = 5;
        $movements = $stockManager->transfer($itemId, $fromLocationId, $toLocationId, $transfer);
        Assert::assertNotNull($movements);

        $fromStock->refresh();
        $expected = ['stock_qty' => 15, 'item_value_cent' => 20];
        Assert::assertEquals($expected, $fromStock->getAttributes(array_keys($expected)));

        $fromMovement = $this->findMovement($movements[0]);
        $expected = ['moved_qty' => -5, 'item_value_cent' => 20, 'reason' => StockConst::MOVEMENT_REASON_TRANSFER_OUT];
        Assert::assertEquals($expected, $fromMovement->getAttributes(array_keys($expected)));

        $toStock->refresh();
        $expected = ['stock_qty' => 15, 'item_value_cent' => 13];
        Assert::assertEquals($expected, $toStock->getAttributes(array_keys($expected)));

        $toMovement = $this->findMovement($movements[1]);
        $expected = ['moved_qty' => 5, 'item_value_cent' => 20, 'reason' => StockConst::MOVEMENT_REASON_TRANSFER_IN];
        Assert::assertEquals($expected, $toMovement->getAttributes(array_keys($expected)));
    }

    /**
     * @throws \Throwable
     * @inheritdoc
     */
    public function testCorrect(): void
    {
        $stockManager = $this->getStockManager();
        $itemId = 1;
        $locationId = 2;

        $stock = $this->createStock([
            'item_id' => $itemId,
            'location_id' => $locationId,
            'stock_qty' => 20,
            'item_value_cent' => 20
        ]);

        $correctQty = 5;
        $movement = $stockManager->correct($itemId, $locationId, $correctQty);
        Assert::assertNotNull($movement);
        $movement = $this->findMovement($movement);

        $stock->refresh();
        $expected = ['stock_qty' => $correctQty, 'item_value_cent' => 20];
        Assert::assertEquals($expected, $stock->getAttributes(array_keys($expected)));

        $expected = ['moved_qty' => -15, 'item_value_cent' => 20, 'reason' => StockConst::MOVEMENT_REASON_CORRECT];
        Assert::assertEquals($expected, $movement->getAttributes(array_keys($expected)));
    }
}

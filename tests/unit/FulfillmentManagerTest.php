<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tests\unit;

use lujie\data\loader\ChainedDataLoader;
use lujie\extend\constants\ExecStatusConst;
use lujie\fulfillment\constants\FulfillmentConst;
use lujie\fulfillment\forms\FulfillmentItemForm;
use lujie\fulfillment\forms\FulfillmentOrderForm;
use lujie\fulfillment\FulfillmentManager;
use lujie\fulfillment\jobs\CancelFulfillmentOrderJob;
use lujie\fulfillment\jobs\HoldFulfillmentOrderJob;
use lujie\fulfillment\jobs\PushFulfillmentItemJob;
use lujie\fulfillment\jobs\PushFulfillmentOrderJob;
use lujie\fulfillment\jobs\ShipFulfillmentOrderJob;
use lujie\fulfillment\models\FulfillmentItem;
use lujie\fulfillment\models\FulfillmentOrder;
use lujie\fulfillment\models\FulfillmentWarehouse;
use lujie\fulfillment\models\FulfillmentWarehouseStock;
use lujie\fulfillment\models\FulfillmentWarehouseStockMovement;
use lujie\fulfillment\tests\unit\mocks\MockFulfillmentService;
use lujie\fulfillment\tests\unit\mocks\MockFulfillmentServiceLoader;
use Yii;
use yii\di\Instance;
use yii\helpers\VarDumper;
use yii\queue\PushEvent;
use yii\queue\Queue;

class FulfillmentManagerTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @return FulfillmentManager
     * @inheritdoc
     */
    public function getFulfillmentManager(): FulfillmentManager
    {
        MockFulfillmentService::initData();
        return new FulfillmentManager([
            'fulfillmentServiceLoader' => [
                'class' => ChainedDataLoader::class,
                'dataLoaders' => [
                    [
                        'class' => MockFulfillmentServiceLoader::class
                    ]
                ]
            ]
        ]);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testEvents(): void
    {
        $fulfillmentManager = $this->getFulfillmentManager();
        $app = Yii::$app;
        $app->set('fulfillmentManager', $fulfillmentManager);
        $app->get('fulfillmentManager');
        $fulfillmentManager->bootstrap($app);
        /** @var Queue $queue */
        $queue = Instance::ensure('queue');
        $pushedJobs = [];
        $queue->on(Queue::EVENT_BEFORE_PUSH, static function (PushEvent $event) use (&$pushedJobs) {
            $pushedJobs[] = $event->job;
            $event->handled = true;
        });

        //test fulfillment item
        $fulfillmentItem = new FulfillmentItemForm();
        $fulfillmentItem->fulfillment_account_id = 1;
        $fulfillmentItem->item_id = 1;
        $this->assertTrue($fulfillmentItem->save(), VarDumper::dumpAsString($fulfillmentItem->getErrors()));
        $this->assertCount(1, $pushedJobs);
        $expectedJob = new PushFulfillmentItemJob([
            'fulfillmentManager' => 'fulfillmentManager',
            'fulfillmentItemId' => $fulfillmentItem->fulfillment_item_id,
        ]);
        $this->assertEquals($expectedJob, array_shift($pushedJobs));

        $fulfillmentItem->external_item_key = 1;
        $fulfillmentItem->item_pushed_at = 2;
        $fulfillmentItem->save();
        $this->assertCount(0, $pushedJobs, VarDumper::dumpAsString($pushedJobs));

        //test fulfillment order
        $fulfillmentOrder = new FulfillmentOrderForm();
        $fulfillmentOrder->fulfillment_type = FulfillmentConst::FULFILLMENT_TYPE_SHIPPING;
        $fulfillmentOrder->fulfillment_account_id = 1;
        $fulfillmentOrder->order_id = 1;
        $fulfillmentOrder->order_pushed_status = ExecStatusConst::EXEC_STATUS_PENDING;
        $this->assertTrue($fulfillmentOrder->save(), VarDumper::dumpAsString($fulfillmentOrder->getErrors()));
        $this->assertCount(1, $pushedJobs);
        $expectedJob = new PushFulfillmentOrderJob([
            'fulfillmentManager' => 'fulfillmentManager',
            'fulfillmentOrderId' => $fulfillmentOrder->fulfillment_order_id,
        ]);
        $this->assertEquals($expectedJob, array_shift($pushedJobs));

        $fulfillmentOrder->external_order_key = 1;
        $fulfillmentOrder->save();
        $this->assertCount(1, $pushedJobs);
        $expectedJob = new PushFulfillmentOrderJob([
            'fulfillmentManager' => 'fulfillmentManager',
            'fulfillmentOrderId' => $fulfillmentOrder->fulfillment_order_id,
        ]);
        $this->assertEquals($expectedJob, array_shift($pushedJobs));

        $fulfillmentOrder->fulfillment_status = FulfillmentConst::FULFILLMENT_STATUS_TO_HOLDING;
        $fulfillmentOrder->order_pushed_status = ExecStatusConst::EXEC_STATUS_PENDING;
        $fulfillmentOrder->save();
        $this->assertCount(1, $pushedJobs);
        $expectedJob = new HoldFulfillmentOrderJob([
            'fulfillmentManager' => 'fulfillmentManager',
            'fulfillmentOrderId' => $fulfillmentOrder->fulfillment_order_id,
        ]);
        $this->assertEquals($expectedJob, array_shift($pushedJobs));

        $fulfillmentOrder->fulfillment_status = FulfillmentConst::FULFILLMENT_STATUS_TO_SHIPPING;
        $fulfillmentOrder->order_pushed_status = ExecStatusConst::EXEC_STATUS_PENDING;
        $fulfillmentOrder->save();
        $this->assertCount(1, $pushedJobs);
        $expectedJob = new ShipFulfillmentOrderJob([
            'fulfillmentManager' => 'fulfillmentManager',
            'fulfillmentOrderId' => $fulfillmentOrder->fulfillment_order_id,
        ]);
        $this->assertEquals($expectedJob, array_shift($pushedJobs));

        $fulfillmentOrder->fulfillment_status = FulfillmentConst::FULFILLMENT_STATUS_TO_CANCELLING;
        $fulfillmentOrder->order_pushed_status = ExecStatusConst::EXEC_STATUS_PENDING;
        $fulfillmentOrder->save();
        $this->assertCount(1, $pushedJobs);
        $expectedJob = new CancelFulfillmentOrderJob([
            'fulfillmentManager' => 'fulfillmentManager',
            'fulfillmentOrderId' => $fulfillmentOrder->fulfillment_order_id,
        ]);
        $this->assertEquals($expectedJob, array_shift($pushedJobs));
    }

    /**
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function testPushItem(): void
    {
        $fulfillmentItem = new FulfillmentItem();
        $fulfillmentItem->fulfillment_account_id = 1;
        $fulfillmentItem->item_id = 1;
        $this->getFulfillmentManager()->pushFulfillmentItem($fulfillmentItem);
        $this->assertFalse($fulfillmentItem->getIsNewRecord());
        $this->assertEquals('ITEM_K1', $fulfillmentItem->external_item_key, VarDumper::dumpAsString($fulfillmentItem->attributes));
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @throws \Throwable
     * @inheritdoc
     */
    public function testPushOrder(): void
    {
        $fulfillmentOrder = new FulfillmentOrder();
        $fulfillmentOrder->fulfillment_type = FulfillmentConst::FULFILLMENT_TYPE_SHIPPING;
        $fulfillmentOrder->fulfillment_account_id = 1;
        $fulfillmentOrder->order_id = 1;
        $this->getFulfillmentManager()->pushFulfillmentOrder($fulfillmentOrder);
        $this->assertFalse($fulfillmentOrder->getIsNewRecord());
        $this->assertEquals('ORDER_K1', $fulfillmentOrder->external_order_key);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function testPullOrders(): void
    {
        $fulfillmentOrder = new FulfillmentOrder();
        $fulfillmentOrder->fulfillment_type = FulfillmentConst::FULFILLMENT_TYPE_SHIPPING;
        $fulfillmentOrder->fulfillment_account_id = 1;
        $fulfillmentOrder->order_id = 1;
        $fulfillmentOrder->external_order_key = 'ORDER_K1';
        $fulfillmentOrder->fulfillment_status = FulfillmentConst::FULFILLMENT_STATUS_PROCESSING;
        $this->assertTrue($fulfillmentOrder->save(false));
        $fulfillmentManager = $this->getFulfillmentManager();
        MockFulfillmentService::$EXTERNAL_ORDER_DATA = ['ORDER_K1' => [
            'id' => 'ORDER_K1',
            'status' => 'SHIPPING',
            'created_at' => time() - 86400,
            'updated_at' => time(),
        ]];
        $fulfillmentManager->pullFulfillmentOrders(1);
        $fulfillmentOrder->refresh();
        $this->assertTrue($fulfillmentOrder->order_pulled_at > 0, VarDumper::dumpAsString($fulfillmentOrder->attributes));
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testPullWarehouses(): void
    {
        $query = FulfillmentWarehouse::find();
        $this->assertEquals(0, $query->count());
        $this->getFulfillmentManager()->pullFulfillmentWarehouses(1);
        $this->assertEquals(2, $query->count());
        $this->getFulfillmentManager()->pullFulfillmentWarehouses(1);
        $this->assertEquals(2, $query->count());
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testPullStocks(): void
    {
        $this->getFulfillmentManager()->pullFulfillmentWarehouses(1);
        $fulfillmentWarehouse = FulfillmentWarehouse::find()->externalWarehouseKey('W01')->one();
        $fulfillmentWarehouse->warehouse_id = 1;
        $fulfillmentWarehouse->save(false);

        $fulfillmentItem = new FulfillmentItem();
        $fulfillmentItem->fulfillment_account_id = 1;
        $fulfillmentItem->item_id = 1;
        $fulfillmentItem->external_item_key = 'ITEM_K1';
        $fulfillmentItem->item_pushed_at = 1;
        $fulfillmentItem->save(false);
        $query = FulfillmentWarehouseStock::find();
        $this->assertEquals(0, $query->count());
        $this->getFulfillmentManager()->pullFulfillmentWarehouseStocks(1);
        $this->assertEquals(1, $query->count());
        $this->getFulfillmentManager()->pullFulfillmentWarehouseStocks(1);
        $this->assertEquals(1, $query->count());
        $fulfillmentItem->refresh();
        $this->assertTrue($fulfillmentItem->stock_pulled_at > 0);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testPullStockMovements(): void
    {
        $this->getFulfillmentManager()->pullFulfillmentWarehouses(1);
        $fulfillmentWarehouse = FulfillmentWarehouse::find()->externalWarehouseKey('W01')->one();
        $fulfillmentWarehouse->warehouse_id = 1;
        $fulfillmentWarehouse->support_movement = 1;
        $fulfillmentWarehouse->save(false);

        $fulfillmentItem = new FulfillmentItem();
        $fulfillmentItem->fulfillment_account_id = 1;
        $fulfillmentItem->item_id = 1;
        $fulfillmentItem->external_item_key = 'ITEM_K1';
        $fulfillmentItem->item_pushed_at = 1;
        $fulfillmentItem->save(false);
        $query = FulfillmentWarehouseStockMovement::find();
        $this->assertEquals(0, $query->count());
        $this->getFulfillmentManager()->pullFulfillmentWarehouseStockMovements(1);
        $this->assertEquals(1, $query->count());
        $this->getFulfillmentManager()->pullFulfillmentWarehouseStockMovements(1);
        $this->assertEquals(1, $query->count());
        $fulfillmentWarehouse->refresh();
        $this->assertTrue($fulfillmentWarehouse->external_movement_at > 0);
    }
}

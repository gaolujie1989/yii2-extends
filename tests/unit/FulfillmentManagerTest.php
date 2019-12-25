<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tests\unit;


use lujie\data\loader\ChainedDataLoader;
use lujie\fulfillment\constants\FulfillmentConst;
use lujie\fulfillment\FulfillmentManager;
use lujie\fulfillment\jobs\CancelFulfillmentOrderJob;
use lujie\fulfillment\jobs\PushFulfillmentItemJob;
use lujie\fulfillment\jobs\PushFulfillmentOrderJob;
use lujie\fulfillment\models\FulfillmentItem;
use lujie\fulfillment\models\FulfillmentOrder;
use lujie\fulfillment\models\FulfillmentWarehouse;
use lujie\fulfillment\models\FulfillmentWarehouseStock;
use lujie\fulfillment\tests\unit\mocks\MockFulfillmentServiceLoader;
use Yii;
use yii\di\Instance;
use yii\helpers\VarDumper;
use yii\queue\PushEvent;
use yii\queue\Queue;

class FulfillmentManagerTest extends \Codeception\Test\Unit
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
     * @return FulfillmentManager
     * @inheritdoc
     */
    public function getFulfillmentManager(): FulfillmentManager
    {
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
        $queue->on(Queue::EVENT_BEFORE_PUSH, static function(PushEvent $event) use (&$pushedJobs) {
            $pushedJobs[] = $event->job;
            $event->handled = true;
        });

        //test fulfillment item
        $fulfillmentItem = new FulfillmentItem();
        $fulfillmentItem->fulfillment_account_id = 1;
        $fulfillmentItem->item_id = 1;
        $this->assertTrue($fulfillmentItem->save(), VarDumper::dumpAsString($fulfillmentItem->getErrors()));
        $this->assertCount(1, $pushedJobs);
        $expectedJob = new PushFulfillmentItemJob([
            'fulfillmentManager' => 'fulfillmentManager',
            'fulfillmentItemId' => $fulfillmentItem->fulfillment_item_id,
        ]);
        $this->assertEquals($expectedJob, array_shift($pushedJobs));

        $fulfillmentItem->external_item_id = 1;
        $fulfillmentItem->item_pushed_at = 2;
        $fulfillmentItem->save();
        $this->assertCount(0, $pushedJobs, VarDumper::dumpAsString($pushedJobs));

        //test fulfillment order
        $fulfillmentOrder = new FulfillmentOrder();
        $fulfillmentOrder->fulfillment_account_id = 1;
        $fulfillmentOrder->order_id = 1;
        $this->assertTrue($fulfillmentOrder->save(), VarDumper::dumpAsString($fulfillmentOrder->getErrors()));
        $this->assertCount(1, $pushedJobs);
        $expectedJob = new PushFulfillmentOrderJob([
            'fulfillmentManager' => 'fulfillmentManager',
            'fulfillmentOrderId' => $fulfillmentOrder->fulfillment_order_id,
        ]);
        $this->assertEquals($expectedJob, array_shift($pushedJobs));

        $fulfillmentOrder->external_order_id = 1;
        $fulfillmentOrder->save();
        $this->assertCount(0, $pushedJobs, VarDumper::dumpAsString($pushedJobs));

        $fulfillmentOrder->fulfillment_status = FulfillmentConst::FULFILLMENT_STATUS_PICKING_CANCELLING;
        $fulfillmentOrder->save();
        $this->assertCount(1, $pushedJobs);
        $expectedJob = new CancelFulfillmentOrderJob([
            'fulfillmentManager' => 'fulfillmentManager',
            'fulfillmentOrderId' => $fulfillmentOrder->fulfillment_order_id,
        ]);
        $this->assertEquals($expectedJob, array_shift($pushedJobs));
    }

    public function testPushItem(): void
    {
        $fulfillmentItem = new FulfillmentItem();
        $fulfillmentItem->fulfillment_account_id = 1;
        $fulfillmentItem->item_id = 1;
        $this->getFulfillmentManager()->pushFulfillmentItem($fulfillmentItem);
        $this->assertFalse($fulfillmentItem->getIsNewRecord());
        $this->assertEquals(1, $fulfillmentItem->external_item_id);
    }

    public function testPushOrder(): void
    {
        $fulfillmentOrder = new FulfillmentOrder();
        $fulfillmentOrder->fulfillment_account_id = 1;
        $fulfillmentOrder->order_id = 1;
        $this->getFulfillmentManager()->pushFulfillmentOrder($fulfillmentOrder);
        $this->assertFalse($fulfillmentOrder->getIsNewRecord());
        $this->assertEquals(1, $fulfillmentOrder->external_order_id);
    }

    public function testPullWarehouses(): void
    {
        $query = FulfillmentWarehouse::find();
        $this->assertEquals(0, $query->count());
        $this->getFulfillmentManager()->pullFulfillmentWarehouses(1);
        $this->assertEquals(1, $query->count());
        $this->getFulfillmentManager()->pullFulfillmentWarehouses(1);
        $this->assertEquals(1, $query->count());
    }

    public function testPullStocks(): void
    {
        $fulfillmentItem = new FulfillmentItem();
        $fulfillmentItem->fulfillment_account_id = 1;
        $fulfillmentItem->item_id = 1;
        $fulfillmentItem->external_item_id = 1;
        $fulfillmentItem->mustSave(false);
        $query = FulfillmentWarehouseStock::find();
        $this->assertEquals(0, $query->count());
        $this->getFulfillmentManager()->pullFulfillmentWarehouseStocks(1);
        $this->assertEquals(1, $query->count());
        $this->getFulfillmentManager()->pullFulfillmentWarehouseStocks(1);
        $this->assertEquals(1, $query->count());
    }

    public function testPullOrders(): void
    {
        $fulfillmentOrder = new FulfillmentOrder();
        $fulfillmentOrder->fulfillment_account_id = 1;
        $fulfillmentOrder->order_id = 1;
        $fulfillmentOrder->external_order_id = 1;
        $fulfillmentOrder->fulfillment_status = FulfillmentConst::FULFILLMENT_STATUS_PUSHED;
        $fulfillmentOrder->mustSave(false);
        $this->getFulfillmentManager()->pullFulfillmentOrders(1);
        $fulfillmentOrder->refresh();
        $this->assertTrue($fulfillmentOrder->order_pulled_at > 0, VarDumper::dumpAsString($fulfillmentOrder->attributes));
    }
}

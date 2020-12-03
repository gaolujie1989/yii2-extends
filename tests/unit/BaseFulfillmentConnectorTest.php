<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tests\unit;


use lujie\ar\relation\behaviors\tests\unit\fixtures\models\TestOrder;
use lujie\ar\snapshoot\behaviors\tests\unit\fixtures\models\TestItem;
use lujie\data\loader\ArrayDataLoader;
use lujie\fulfillment\BaseFulfillmentConnector;
use lujie\fulfillment\constants\FulfillmentConst;
use lujie\fulfillment\models\FulfillmentAccount;
use lujie\fulfillment\models\FulfillmentItem;
use lujie\fulfillment\models\FulfillmentOrder;
use lujie\fulfillment\models\FulfillmentWarehouse;
use lujie\fulfillment\tests\unit\mocks\MockFulfillmentService;
use Yii;
use yii\db\AfterSaveEvent;

class BaseFulfillmentConnectorTest extends \Codeception\Test\Unit
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

    public function testItemConnect(): void
    {
        $connector = new BaseFulfillmentConnector([
            'itemClass' => TestItem::class,
            'outboundOrderClass' => TestOrder::class,
        ]);
        $connector->bootstrap(Yii::$app);

        $account = new FulfillmentAccount([
            'name' => 'TEST',
            'username' => 'TESTER',
            'status' => 10,
        ]);
        $account->save(false);
        $item = new TestItem([
            'item_no' => '',
            'updated_at' => time(),
        ]);
        $item->save(false);

        $fulfillmentItem = FulfillmentItem::find()
            ->fulfillmentAccountId($account->account_id)
            ->itemId($item->test_item_id)
            ->one();
        $this->assertNotNull($fulfillmentItem);
        $this->assertEquals($item->updated_at, $fulfillmentItem->item_updated_at);
    }

    public function testOutboundOrderConnect(): void
    {
        $connector = new BaseFulfillmentConnector([
            'itemClass' => TestItem::class,
            'outboundOrderClass' => TestOrder::class,
            'fulfillmentStatusMap' => [
                '230' => FulfillmentConst::FULFILLMENT_STATUS_TO_CANCELLING
            ]
        ]);
        $connector->bootstrap(Yii::$app);

        $account = new FulfillmentAccount([
            'name' => 'TEST',
            'username' => 'TESTER',
            'status' => 10,
        ]);
        $account->save(false);
        $fulfillmentWarehouse = new FulfillmentWarehouse([
            'fulfillment_account_id' => $account->account_id,
            'warehouse_id' => 1,
        ]);
        $fulfillmentWarehouse->save(false);
        $order = new TestOrder([
            'order_no' => '',
            'warehouse_id' => 1,
            'status' => 10,
            'updated_at' => time(),
        ]);
        $order->save(false);

        $fulfillmentOrder = FulfillmentOrder::find()
            ->fulfillmentAccountId($account->account_id)
            ->orderId($order->test_order_id)
            ->one();
        $this->assertNotNull($fulfillmentOrder);
        $this->assertEquals($order->status, $fulfillmentOrder->order_status);
        $this->assertEquals($order->updated_at, $fulfillmentOrder->order_updated_at);
        $this->assertEquals(FulfillmentConst::FULFILLMENT_STATUS_PENDING, $fulfillmentOrder->fulfillment_status);

        $order->status = 230;
        $order->save(false);
        $fulfillmentOrder->refresh();
        $this->assertEquals(FulfillmentConst::FULFILLMENT_STATUS_TO_CANCELLING, $fulfillmentOrder->fulfillment_status);
    }

    public function testOutboundOrderConnectDelete(): void
    {
        $connector = new BaseFulfillmentConnector([
            'itemClass' => TestItem::class,
            'outboundOrderClass' => TestOrder::class,
        ]);
        $connector->bootstrap(Yii::$app);

        $order = new TestOrder([
            'order_no' => '',
            'warehouse_id' => 1,
            'status' => 10,
            'updated_at' => time(),
        ]);
        $order->save(false);
        $fulfillmentOrder = new FulfillmentOrder([
            'fulfillment_account_id' => 1,
            'order_id' => $order->test_order_id,
            'external_order_key' => 'ORDER_K1',
            'fulfillment_status' => FulfillmentConst::FULFILLMENT_STATUS_PROCESSING
        ]);
        $fulfillmentOrder->save(false);

        $this->assertFalse(false, $order->delete());
        $this->assertTrue($fulfillmentOrder->refresh());

        $fulfillmentOrder->fulfillment_status = FulfillmentConst::FULFILLMENT_STATUS_PENDING;
        $fulfillmentOrder->save(false);
        $this->assertTrue(true, $order->delete());
        $this->assertFalse($fulfillmentOrder->refresh());
    }

    public function testOrderFulfillmentConnect(): void
    {
        $connector = new BaseFulfillmentConnector([
            'itemClass' => TestItem::class,
            'outboundOrderClass' => TestOrder::class,
            'orderStatusMap' => [
                FulfillmentConst::FULFILLMENT_STATUS_PROCESSING => '20'
            ]
        ]);
        $connector->bootstrap(Yii::$app);

        $order = new TestOrder([
            'order_no' => '',
            'warehouse_id' => 1,
            'status' => 10,
            'updated_at' => time(),
        ]);
        $order->save(false);
        $fulfillmentOrder = new FulfillmentOrder([
            'fulfillment_account_id' => 1,
            'order_id' => $order->test_order_id,
            'external_order_key' => 'ORDER_K1',
        ]);
        $fulfillmentOrder->save(false);

        $fulfillmentService = new MockFulfillmentService([
            'account' => new FulfillmentAccount([
                'account_id' => 1
            ]),
            'itemLoader' => new ArrayDataLoader(),
            'orderLoader' => new ArrayDataLoader(),
        ]);
        MockFulfillmentService::$EXTERNAL_ORDER_DATA = ['ORDER_K1' => [
            'id' => 'ORDER_K1',
            'status' => 'SHIPPING',
            'created_at' => time() - 10,
            'updated_at' => time(),
        ]];
        $fulfillmentService->pullFulfillmentOrders([$fulfillmentOrder]);

        $fulfillmentOrder->refresh();
        $this->assertEquals(FulfillmentConst::FULFILLMENT_STATUS_PROCESSING, $fulfillmentOrder->fulfillment_status);
        $order->refresh();
        $this->assertEquals(20, $order->status);
    }
}
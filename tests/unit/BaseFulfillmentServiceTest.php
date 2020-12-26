<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tests\unit;


use lujie\fulfillment\BaseFulfillmentService;
use lujie\fulfillment\common\Address;
use lujie\fulfillment\common\Item;
use lujie\fulfillment\common\ItemBarcode;
use lujie\fulfillment\common\Order;
use lujie\fulfillment\common\OrderItem;
use lujie\fulfillment\constants\FulfillmentConst;
use lujie\fulfillment\models\FulfillmentAccount;
use lujie\fulfillment\models\FulfillmentItem;
use lujie\fulfillment\models\FulfillmentOrder;
use lujie\fulfillment\models\FulfillmentWarehouse;
use lujie\fulfillment\models\FulfillmentWarehouseStock;
use lujie\fulfillment\models\FulfillmentWarehouseStockMovement;
use lujie\fulfillment\tests\unit\mocks\MockFulfillmentDataLoader;
use lujie\fulfillment\tests\unit\mocks\MockFulfillmentService;
use yii\helpers\ArrayHelper;

class BaseFulfillmentServiceTest extends \Codeception\Test\Unit
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
     * @return BaseFulfillmentService
     * @inheritdoc
     */
    protected function getFulfillmentService(): BaseFulfillmentService
    {
        MockFulfillmentService::initData();
        return new MockFulfillmentService([
            'account' => new FulfillmentAccount([
                'account_id' => 1
            ]),
            'itemLoader' => [
                'class' => MockFulfillmentDataLoader::class,
                'data' => [
                    1 => new Item([
                        'itemId' => 1,
                        'itemNo' => 'ITEM-1',
                        'itemName' => 'ITEM-1-NAME',
                        'weightG' => 1100,
                        'weightNetG' => 1005,
                        'lengthMM' => 90,
                        'widthMM' => 60,
                        'heightMM' => 30,
                        'imageUrls' => [
                            'https://xxx.com/item-1-1.jpg',
                            'https://xxx.com/item-1-2.jpg',
                        ],
                        'itemBarcodes' => [
                            new ItemBarcode([
                                'name' => 'EAN',
                                'code' => '4251249402551TEST',
                            ]),
                            new ItemBarcode([
                                'name' => 'OWN',
                                'code' => 'ITEM_OWN_BARCODE_1',
                            ]),
                        ],
                    ]),
                ]
            ],
            'orderLoader' => [
                'class' => MockFulfillmentDataLoader::class,
                'data' => [
                    1 => new Order([
                        'orderId' => 1,
                        'orderNo' => 'ORDER-1',
                        'address' => new Address([
                            'addressId' => 1,
                            'country' => 'IT',
                            'state' => '',
                            'city' => 'vallecrosia alta',
                            'companyName' => '',
                            'firstName' => 'tanzillo',
                            'lastName' => 'salvatore',
                            'street' => 'via dei grossi',
                            'houseNo' => '6',
                            'additional' => '',
                            'postalCode' => '18019',
                            'email' => '74684c24ca049e0c2e13@members.ebay.com',
                            'phone' => '3484035259',
                        ]),
                        'orderItems' => [
                            new OrderItem([
                                'itemId' => 1,
                                'itemNo' => 'ITEM-1',
                                'orderItemName' => 'ITEM-1-NAME',
                                'orderedQty' => 1,
                            ])
                        ],
                    ])
                ]
            ],
        ]);
    }

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @inheritdoc
     */
    public function testPushItem(): void
    {
        $fulfillmentService = $this->getFulfillmentService();

        $fulfillmentItem = new FulfillmentItem();
        $this->assertTrue($fulfillmentItem->save(false));
        $fulfillmentItem->fulfillment_account_id = 2;
        $this->assertFalse($fulfillmentService->pushItem($fulfillmentItem), 'Account not equal, should return false');

        $fulfillmentItem->fulfillment_account_id = 1;
        $fulfillmentItem->item_id = 2;
        $this->assertFalse($fulfillmentService->pushItem($fulfillmentItem), 'Item not found, should return false');

        $fulfillmentItem->item_id = 1;
        $now = time();
        $this->assertTrue($fulfillmentService->pushItem($fulfillmentItem), 'create');
        $this->assertEquals('ITEM_K1', $fulfillmentItem->external_item_key);
        $this->assertTrue($fulfillmentItem->external_created_at >= $now);
        $this->assertTrue($fulfillmentItem->external_updated_at === $fulfillmentItem->external_created_at);

        $fulfillmentItem->delete();
        $fulfillmentItem = new FulfillmentItem();
        $fulfillmentItem->save(false);
        $fulfillmentItem->fulfillment_account_id = 1;
        $fulfillmentItem->item_id = 1;
        $this->assertTrue($fulfillmentService->pushItem($fulfillmentItem), 'already exists, link and update');
        $this->assertEquals('ITEM_K1', $fulfillmentItem->external_item_key);
        $this->assertTrue($fulfillmentItem->external_created_at >= $now);
        $this->assertTrue($fulfillmentItem->external_updated_at > $fulfillmentItem->external_created_at);
    }

    /**
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     * @inheritdoc
     */
    public function testPushFulfillmentOrder(): void
    {
        $fulfillmentService = $this->getFulfillmentService();

        $fulfillmentOrder = new FulfillmentOrder();
        $fulfillmentOrder->fulfillment_type = FulfillmentConst::FULFILLMENT_TYPE_SHIPPING;
        $this->assertTrue($fulfillmentOrder->save(false));
        $fulfillmentOrder->fulfillment_account_id = 2;
        $this->assertFalse($fulfillmentService->pushFulfillmentOrder($fulfillmentOrder), 'Account not equal, should return false');

        $fulfillmentOrder->fulfillment_account_id = 1;
        $fulfillmentOrder->order_id = 2;
        $this->assertFalse($fulfillmentService->pushFulfillmentOrder($fulfillmentOrder), 'Order not found, should return false');

        $fulfillmentOrder->order_id = 1;
        $now = time();
        $this->assertTrue($fulfillmentService->pushFulfillmentOrder($fulfillmentOrder), 'create');
        $this->assertEquals('ORDER_K1', $fulfillmentOrder->external_order_key);
        $this->assertTrue($fulfillmentOrder->external_created_at >= $now);
        $this->assertTrue($fulfillmentOrder->external_updated_at === $fulfillmentOrder->external_created_at);
        $this->assertEquals(FulfillmentConst::FULFILLMENT_STATUS_PROCESSING, $fulfillmentOrder->fulfillment_status);

        $fulfillmentOrder->delete();
        $fulfillmentOrder = new FulfillmentOrder();
        $fulfillmentOrder->fulfillment_type = FulfillmentConst::FULFILLMENT_TYPE_SHIPPING;
        $this->assertTrue($fulfillmentOrder->save(false));
        $fulfillmentOrder->fulfillment_account_id = 1;
        $fulfillmentOrder->order_id = 1;
        $this->assertTrue($fulfillmentService->pushFulfillmentOrder($fulfillmentOrder), 'already exists, link and update');
        $this->assertEquals('ORDER_K1', $fulfillmentOrder->external_order_key);
        $this->assertTrue($fulfillmentOrder->external_created_at >= $now);
        $this->assertTrue($fulfillmentOrder->external_updated_at > $fulfillmentOrder->external_created_at);
        $this->assertEquals(FulfillmentConst::FULFILLMENT_STATUS_PROCESSING, $fulfillmentOrder->fulfillment_status);
    }

    /**
     * @inheritdoc
     */
    public function testHoldShipCancelFulfillmentOrder(): void
    {
        $fulfillmentService = $this->getFulfillmentService();

        $fulfillmentOrder = new FulfillmentOrder();
        $fulfillmentOrder->fulfillment_type = FulfillmentConst::FULFILLMENT_TYPE_SHIPPING;
        $fulfillmentOrder->fulfillment_account_id = 1;
        $fulfillmentOrder->order_id = 1;
        $this->assertTrue($fulfillmentOrder->save(false));
        $this->assertTrue($fulfillmentService->pushFulfillmentOrder($fulfillmentOrder));
        $this->assertEquals(FulfillmentConst::FULFILLMENT_STATUS_PROCESSING, $fulfillmentOrder->fulfillment_status);

        $fulfillmentService->holdFulfillmentOrder($fulfillmentOrder);
        $this->assertEquals(FulfillmentConst::FULFILLMENT_STATUS_HOLDING, $fulfillmentOrder->fulfillment_status);

        $fulfillmentService->shipFulfillmentOrder($fulfillmentOrder);
        $this->assertEquals(FulfillmentConst::FULFILLMENT_STATUS_PROCESSING, $fulfillmentOrder->fulfillment_status);

        $fulfillmentService->cancelFulfillmentOrder($fulfillmentOrder);
        $this->assertEquals(FulfillmentConst::FULFILLMENT_STATUS_CANCELLED, $fulfillmentOrder->fulfillment_status);

        $fulfillmentService->shipFulfillmentOrder($fulfillmentOrder);
        $this->assertEquals(FulfillmentConst::FULFILLMENT_STATUS_PROCESSING, $fulfillmentOrder->fulfillment_status);
    }

    /**
     * @inheritdoc
     */
    public function testPullFulfillmentOrders(): void
    {
        $fulfillmentService = $this->getFulfillmentService();

        $fulfillmentOrder = new FulfillmentOrder();
        $fulfillmentOrder->fulfillment_type = FulfillmentConst::FULFILLMENT_TYPE_SHIPPING;
        $fulfillmentOrder->fulfillment_account_id = 1;
        $fulfillmentOrder->order_id = 1;
        $this->assertTrue($fulfillmentOrder->save(false));
        $this->assertTrue($fulfillmentService->pushFulfillmentOrder($fulfillmentOrder));
        $this->assertEquals(FulfillmentConst::FULFILLMENT_STATUS_PROCESSING, $fulfillmentOrder->fulfillment_status);

        MockFulfillmentService::$EXTERNAL_ORDER_DATA['ORDER_K1']['status'] = 'SHIPPED';
        $fulfillmentService->pullFulfillmentOrders([$fulfillmentOrder]);
        $this->assertEquals(FulfillmentConst::FULFILLMENT_STATUS_PICKING, $fulfillmentOrder->fulfillment_status);

        MockFulfillmentService::$EXTERNAL_ORDER_DATA['ORDER_K1']['trackingNumbers'] = ['01524814864'];
        $fulfillmentService->pullFulfillmentOrders([$fulfillmentOrder]);
        $this->assertEquals(FulfillmentConst::FULFILLMENT_STATUS_SHIPPED, $fulfillmentOrder->fulfillment_status);
        $this->assertTrue($fulfillmentOrder->order_pulled_at > 0);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testPullWarehouses(): void
    {
        $fulfillmentService = $this->getFulfillmentService();
        $fulfillmentService->pullWarehouses();
        $externalWarehouseKeys = FulfillmentWarehouse::find()->select(['external_warehouse_key'])->column();
        $this->assertEquals(ArrayHelper::getColumn(MockFulfillmentService::$EXTERNAL_WAREHOUSE_DATA, 'id'), $externalWarehouseKeys);
    }

    /**
     * @inheritdoc
     */
    public function testPullWarehouseStocks(): void
    {
        $fulfillmentService = $this->getFulfillmentService();
        $fulfillmentService->pullWarehouses();

        $fulfillmentWarehouse = FulfillmentWarehouse::find()->externalWarehouseKey('W01')->one();
        $fulfillmentWarehouse->warehouse_id = 1;
        $fulfillmentWarehouse->save(false);

        $fulfillmentItem = new FulfillmentItem();
        $fulfillmentItem->fulfillment_account_id = 1;
        $fulfillmentItem->item_id = 1;
        $this->assertTrue($fulfillmentItem->save(false));
        $this->assertTrue($fulfillmentService->pushItem($fulfillmentItem));

        $fulfillmentService->pullWarehouseStocks([$fulfillmentItem]);
        $query = FulfillmentWarehouseStock::find()->itemId($fulfillmentItem->item_id);
        $this->assertEquals(1, $query->count());
        $warehouseStock = $query->one();
        $expectedStock = [
            'fulfillment_account_id' => 1,
            'item_id' => 1,
            'warehouse_id' => 1,
            'external_item_key' => 'ITEM_K1',
            'external_warehouse_key' => 'W01',
            'available_qty' => 1,
        ];
        $this->assertEquals($expectedStock, $warehouseStock->getAttributes(array_keys($expectedStock)));
        $fulfillmentItem->refresh();
        $this->assertTrue($fulfillmentItem->stock_pulled_at > 0);
    }

    public function testPullWarehouseStockMovements(): void
    {
        $fulfillmentService = $this->getFulfillmentService();
        $fulfillmentService->pullWarehouses();

        $fulfillmentWarehouse = FulfillmentWarehouse::find()->externalWarehouseKey('W01')->one();
        $fulfillmentWarehouse->warehouse_id = 1;
        $fulfillmentWarehouse->save(false);

        $fulfillmentItem = new FulfillmentItem();
        $fulfillmentItem->fulfillment_account_id = 1;
        $fulfillmentItem->item_id = 1;
        $this->assertTrue($fulfillmentItem->save(false));
        $this->assertTrue($fulfillmentService->pushItem($fulfillmentItem));

        $query = FulfillmentWarehouseStockMovement::find()->itemId($fulfillmentItem->item_id);
        $fulfillmentService->pullWarehouseStockMovements($fulfillmentWarehouse, 0, 0);
        $this->assertEquals(0, $query->count());

        $fulfillmentWarehouse->support_movement = 1;
        $fulfillmentService->pullWarehouseStockMovements($fulfillmentWarehouse, 0, 0);
        $this->assertEquals(1, $query->count());
        $stockMovement = $query->one();
        $expectedStock = [
            'fulfillment_account_id' => 1,
            'item_id' => 1,
            'warehouse_id' => 1,
            'external_item_key' => 'ITEM_K1',
            'external_warehouse_key' => 'W01',
            'external_movement_key' => 'M001',
            'movement_qty' => 1,
        ];
        $this->assertEquals($expectedStock, $stockMovement->getAttributes(array_keys($expectedStock)));
        $this->assertEquals(1577808000, $fulfillmentWarehouse->external_movement_at);

        $fulfillmentService->pullWarehouseStockMovements($fulfillmentWarehouse, 0, 0);
        $this->assertEquals(1, $query->count());
        $this->assertEquals(1577808000, $fulfillmentWarehouse->external_movement_at);
    }
}
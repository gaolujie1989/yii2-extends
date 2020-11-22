<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tests\unit\f4px;


use lujie\data\loader\ArrayDataLoader;
use lujie\fulfillment\BaseFulfillmentService;
use lujie\fulfillment\common\Address;
use lujie\fulfillment\common\Item;
use lujie\fulfillment\common\ItemBarcode;
use lujie\fulfillment\common\Order;
use lujie\fulfillment\common\OrderItem;
use lujie\fulfillment\constants\FulfillmentConst;
use lujie\fulfillment\f4px\F4pxClient;
use lujie\fulfillment\f4px\F4pxFulfillmentService;
use lujie\fulfillment\models\FulfillmentAccount;
use lujie\fulfillment\models\FulfillmentItem;
use lujie\fulfillment\models\FulfillmentOrder;
use lujie\fulfillment\models\FulfillmentWarehouse;
use lujie\fulfillment\models\FulfillmentWarehouseStock;
use lujie\fulfillment\models\FulfillmentWarehouseStockMovement;
use lujie\fulfillment\tests\unit\mocks\MockFulfillmentService;
use Yii;
use yii\helpers\ArrayHelper;

class F4pxFulfillmentServiceTest extends \Codeception\Test\Unit
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
        return new F4pxFulfillmentService([
            'client' => [
                'class' => F4pxClient::class,
                'appKey' => Yii::$app->params['f4px.appKey'],
                'appSecret' => Yii::$app->params['f4px.appSecret'],
                'sandbox' => true,
            ],
            'account' => new FulfillmentAccount([
                'account_id' => 1
            ]),
            'itemLoader' => [
                'class' => ArrayDataLoader::class,
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
                'class' => ArrayDataLoader::class,
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
        $fulfillmentItem->save(false);
        $fulfillmentItem->fulfillment_account_id = 1;
        $fulfillmentItem->item_id = 1;

        $this->assertTrue($fulfillmentService->pushItem($fulfillmentItem), 'create');
        $this->assertNotEmpty($fulfillmentItem->external_item_key);
        $this->assertEquals('ITEM-1', $fulfillmentItem->external_item_additional['sku_code']);
        $this->assertTrue($fulfillmentItem->external_created_at > 0);
        $this->assertTrue($fulfillmentItem->external_updated_at >= $fulfillmentItem->external_created_at);

        $fulfillmentItem->delete();
        $fulfillmentItem = new FulfillmentItem();
        $fulfillmentItem->save(false);
        $fulfillmentItem->fulfillment_account_id = 1;
        $fulfillmentItem->item_id = 1;
        $this->assertTrue($fulfillmentService->pushItem($fulfillmentItem), 'already exists, link and update');
        $this->assertNotEmpty($fulfillmentItem->external_item_key);
        $this->assertEquals('ITEM-1', $fulfillmentItem->external_item_additional['sku_code']);
        $this->assertTrue($fulfillmentItem->external_created_at > 0);
        $this->assertTrue($fulfillmentItem->external_updated_at > $fulfillmentItem->external_created_at);
    }

    /**
     * @inheritdoc
     */
    public function te1stPushFulfillmentOrder(): void
    {
        $fulfillmentService = $this->getFulfillmentService();

        $fulfillmentOrder = new FulfillmentOrder();
        $fulfillmentOrder->save(false);
        $fulfillmentOrder->fulfillment_account_id = 1;
        $fulfillmentOrder->order_id = 1;

        $fulfillmentItem = new FulfillmentItem();
        $fulfillmentItem->fulfillment_account_id = 1;
        $fulfillmentItem->item_id = 1;
        $fulfillmentItem->external_item_key = 'xxx';
        $fulfillmentItem->external_item_additional = ['sku_code' => 'ITEM-1'];
        $fulfillmentItem->save(false);

        $this->assertTrue($fulfillmentService->pushFulfillmentOrder($fulfillmentOrder), 'create');
        $this->assertNotEmpty($fulfillmentOrder->external_order_key);
        $this->assertEquals('O-1', $fulfillmentOrder->external_order_additional['ref_no']);
        $this->assertEquals('ORDER-1', $fulfillmentOrder->external_order_additional['sales_no']);
        $this->assertTrue($fulfillmentOrder->external_created_at > 0);
        $this->assertTrue($fulfillmentOrder->external_updated_at >= $fulfillmentOrder->external_created_at);
        $this->assertEquals(FulfillmentConst::FULFILLMENT_STATUS_PROCESSING, $fulfillmentOrder->fulfillment_status);

        sleep(1);
        $fulfillmentOrder->delete();
        $fulfillmentOrder = new FulfillmentOrder();
        $fulfillmentOrder->save(false);
        $fulfillmentOrder->fulfillment_account_id = 1;
        $fulfillmentOrder->order_id = 1;
        $this->assertTrue($fulfillmentService->pushFulfillmentOrder($fulfillmentOrder), 'already exists, link and update');
        $this->assertNotEmpty($fulfillmentOrder->external_order_key);
        $this->assertEquals('O-1', $fulfillmentOrder->external_order_additional['ref_no']);
        $this->assertEquals('ORDER-1', $fulfillmentOrder->external_order_additional['sales_no']);
        $this->assertTrue($fulfillmentOrder->external_created_at > 0);
        $this->assertTrue($fulfillmentOrder->external_updated_at > $fulfillmentOrder->external_created_at);
        $this->assertEquals(FulfillmentConst::FULFILLMENT_STATUS_PROCESSING, $fulfillmentOrder->fulfillment_status);
    }

    /**
     * @inheritdoc
     */
    public function te1stCancelFulfillmentOrder(): void
    {
        $fulfillmentService = $this->getFulfillmentService();

        $fulfillmentOrder = new FulfillmentOrder();
        $fulfillmentOrder->fulfillment_account_id = 1;
        $fulfillmentOrder->order_id = 1;
        $fulfillmentOrder->save(false);
        $this->assertTrue($fulfillmentService->pushFulfillmentOrder($fulfillmentOrder));
        $this->assertEquals(FulfillmentConst::FULFILLMENT_STATUS_PROCESSING, $fulfillmentOrder->fulfillment_status);

        $fulfillmentService->cancelFulfillmentOrder($fulfillmentOrder);
        $this->assertEquals(FulfillmentConst::FULFILLMENT_STATUS_CANCELLED, $fulfillmentOrder->fulfillment_status);
    }

    /**
     * @inheritdoc
     */
    public function te1stPullFulfillmentOrders(): void
    {

    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function te1stPullWarehouses(): void
    {
        $fulfillmentService = $this->getFulfillmentService();
        $fulfillmentService->pullWarehouses();
        $externalWarehouseKeys = FulfillmentWarehouse::find()->select(['external_warehouse_key'])->column();
        $this->assertTrue(in_array('CNHKGB', $externalWarehouseKeys));
    }

    /**
     * @inheritdoc
     */
    public function te1stPullWarehouseStocks(): void
    {
        $fulfillmentService = $this->getFulfillmentService();
        $fulfillmentService->pullWarehouses();

        $fulfillmentWarehouse = FulfillmentWarehouse::find()->externalWarehouseKey('CNHKGB')->one();
        $fulfillmentWarehouse->warehouse_id = 1;
        $fulfillmentWarehouse->save(false);

        $fulfillmentItem = new FulfillmentItem();
        $fulfillmentItem->fulfillment_account_id = 1;
        $fulfillmentItem->item_id = 1;
        $fulfillmentItem->external_item_key = 'xxx';
        $fulfillmentItem->external_item_additional = ['sku_code' => 'ITEM-1'];
        $fulfillmentItem->save(false);

        $fulfillmentService->pullWarehouseStocks([$fulfillmentItem]);
        $query = FulfillmentWarehouseStock::find()->itemId($fulfillmentItem->item_id);
        $this->assertEquals(1, $query->count());
        $warehouseStock = $query->one();
        $expectedStock = [
            'fulfillment_account_id' => 1,
            'item_id' => 1,
            'warehouse_id' => 1,
            'external_item_key' => 'xxx',
            'external_warehouse_key' => 'CNHKGB',
            'stock_qty' => 123,
        ];
        $this->assertEquals($expectedStock, $warehouseStock->getAttributes(array_keys($expectedStock)));
        $fulfillmentItem->refresh();
        $this->assertTrue($fulfillmentItem->stock_pulled_at > 0);
    }

    /**
     * @inheritdoc
     */
    public function te1stPullWarehouseStockMovements(): void
    {
        $fulfillmentService = $this->getFulfillmentService();
        $fulfillmentService->pullWarehouses();

        $fulfillmentWarehouse = FulfillmentWarehouse::find()->externalWarehouseKey('108')->one();
        $fulfillmentWarehouse->warehouse_id = 1;
        $fulfillmentWarehouse->support_movement = 1;
        $fulfillmentWarehouse->save(false);

        $fulfillmentItem = new FulfillmentItem();
        $fulfillmentItem->fulfillment_account_id = 1;
        $fulfillmentItem->item_id = 1;
        $fulfillmentItem->external_item_key = 'xxx';
        $fulfillmentItem->external_item_additional = ['sku_code' => 'ITEM-1'];
        $fulfillmentItem->save(false);

        $fulfillmentService->pullWarehouseStockMovements($fulfillmentWarehouse, strtotime('2020-05-14 16:14:00'), strtotime('2020-05-14 16:14:59'));
        $query = FulfillmentWarehouseStockMovement::find()->itemId($fulfillmentItem->item_id);
        $this->assertEquals(1, $query->count());
        $stockMovement = $query->one();
        $expectedMovement = [
            'fulfillment_account_id' => 1,
            'item_id' => 1,
            'warehouse_id' => 1,
            'external_item_key' => 'xxx',
            'external_warehouse_key' => 'CNHKGB',
            'external_movement_key' => 'xxxxxx',
            'moved_qty' => 123,
            'balance_qty' => 0,
            'reason' => 'I',
            'related_type' => 'P',
            'related_key' => 'IC90027818042620',
            'external_created_at' => 1589444083,
        ];
        $this->assertEquals($expectedMovement, $stockMovement->getAttributes(array_keys($expectedMovement)));
        $fulfillmentItem->refresh();
        $fulfillmentWarehouse->refresh();
        $this->assertEquals(1589444096, $fulfillmentWarehouse->external_movement_at);

//        $this->cleanItem = true;
    }
}
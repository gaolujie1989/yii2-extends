<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tests\unit\f4px;

use lujie\fulfillment\BaseFulfillmentService;
use lujie\fulfillment\common\Address;
use lujie\fulfillment\common\Item;
use lujie\fulfillment\common\ItemBarcode;
use lujie\fulfillment\common\Order;
use lujie\fulfillment\common\OrderItem;
use lujie\fulfillment\constants\FulfillmentConst;
use lujie\fulfillment\f4px\F4pxFulfillmentService;
use lujie\fulfillment\models\FulfillmentAccount;
use lujie\fulfillment\models\FulfillmentItem;
use lujie\fulfillment\models\FulfillmentOrder;
use lujie\fulfillment\models\FulfillmentWarehouse;
use lujie\fulfillment\models\FulfillmentWarehouseStock;
use lujie\fulfillment\models\FulfillmentWarehouseStockMovement;
use lujie\fulfillment\tests\unit\mocks\MockF4pxClient;
use lujie\fulfillment\tests\unit\mocks\MockFulfillmentDataLoader;
use Yii;

class F4pxFulfillmentServiceTest extends \Codeception\Test\Unit
{
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
                'class' => MockF4pxClient::class,
                'appKey' => Yii::$app->params['f4px.appKey'],
                'appSecret' => Yii::$app->params['f4px.appSecret'],
                'sandbox' => true,
            ],
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
        $fulfillmentItem->save(false);
        $fulfillmentItem->fulfillment_account_id = 1;
        $fulfillmentItem->item_id = 1;

        MockF4pxClient::$RESPONSE_DATA[] = [];
        MockF4pxClient::$RESPONSE_DATA[] = [
            "sku_id" => "922000000127",
            "sku_code" => "ITEM-1"
        ];
        $this->assertTrue($fulfillmentService->pushItem($fulfillmentItem), 'create');
        $externalItemKey = $fulfillmentItem->external_item_key;
        $this->assertNotEmpty($externalItemKey);
        $this->assertEquals('ITEM-1', $fulfillmentItem->external_item_additional['sku_code']);
        $this->assertTrue($fulfillmentItem->external_created_at > 0);
        $this->assertTrue($fulfillmentItem->external_updated_at >= $fulfillmentItem->external_created_at);

        $fulfillmentItem->delete();
        $fulfillmentItem = new FulfillmentItem();
        $fulfillmentItem->save(false);
        $fulfillmentItem->fulfillment_account_id = 1;
        $fulfillmentItem->item_id = 1;
        MockF4pxClient::$RESPONSE_DATA[] = [
            "sku_id" => "922000000127",
            "sku_code" => "ITEM-1"
        ];
        MockF4pxClient::$RESPONSE_DATA[] = [
            "sku_id" => "922000000127",
            "sku_code" => "ITEM-1"
        ];
        $this->assertTrue($fulfillmentService->pushItem($fulfillmentItem), 'already exists, link and update');
        $this->assertEquals($externalItemKey, $fulfillmentItem->external_item_key);
        $this->assertEquals('ITEM-1', $fulfillmentItem->external_item_additional['sku_code']);
        $this->assertTrue($fulfillmentItem->external_created_at > 0);
        $this->assertTrue($fulfillmentItem->external_updated_at >= $fulfillmentItem->external_created_at);
    }

    /**
     * @inheritdoc
     */
    public function testPushFulfillmentOrder(): void
    {
        $fulfillmentService = $this->getFulfillmentService();

        $fulfillmentOrder = new FulfillmentOrder();
        $fulfillmentOrder->fulfillment_type = FulfillmentConst::FULFILLMENT_TYPE_SHIPPING;
        $fulfillmentOrder->save(false);
        $fulfillmentOrder->fulfillment_account_id = 1;
        $fulfillmentOrder->order_id = 1;

        $fulfillmentItem = new FulfillmentItem();
        $fulfillmentItem->fulfillment_account_id = 1;
        $fulfillmentItem->item_id = 1;
        $fulfillmentItem->external_item_key = 'xxx';
        $fulfillmentItem->external_item_additional = ['sku_code' => 'ITEM-1'];
        $fulfillmentItem->save(false);

        $now = time();
        MockF4pxClient::$RESPONSE_DATA[] = [];
        MockF4pxClient::$RESPONSE_DATA[] = [
            "ref_no" => "O-1",
            "sales_no" => "ORDER-1",
            "consignment_no" => "OC9117151805020007",
            "4px_tracking_no" => "100000001864",
            'status' => 'S',
            'create_time' => $now * 1000,
            'update_time' => time() * 1000,
        ];
        $this->assertTrue($fulfillmentService->pushFulfillmentOrder($fulfillmentOrder), 'create');
        $externalOrderKey = $fulfillmentOrder->external_order_key;
        $this->assertNotEmpty($externalOrderKey);
        $this->assertEquals('O-1', $fulfillmentOrder->external_order_additional['ref_no']);
        $this->assertEquals('ORDER-1', $fulfillmentOrder->external_order_additional['sales_no']);
        $this->assertTrue($fulfillmentOrder->external_created_at > 0);
        $this->assertTrue($fulfillmentOrder->external_updated_at >= $fulfillmentOrder->external_created_at);
        $this->assertEquals(FulfillmentConst::FULFILLMENT_STATUS_PROCESSING, $fulfillmentOrder->fulfillment_status);

        sleep(1);
        $fulfillmentOrder->delete();
        $fulfillmentOrder = new FulfillmentOrder();
        $fulfillmentOrder->fulfillment_type = FulfillmentConst::FULFILLMENT_TYPE_SHIPPING;
        $fulfillmentOrder->save(false);
        $fulfillmentOrder->fulfillment_account_id = 1;
        $fulfillmentOrder->order_id = 1;
        MockF4pxClient::$RESPONSE_DATA[] = [
            "ref_no" => "O-1",
            "sales_no" => "ORDER-1",
            "consignment_no" => "OC9117151805020007",
            "4px_tracking_no" => "100000001864",
            'status' => 'S',
            'create_time' => $now * 1000,
            'update_time' => time() * 1000,
        ];
        MockF4pxClient::$RESPONSE_DATA[] = [
            "ref_no" => "O-1",
            "sales_no" => "ORDER-1",
            "consignment_no" => "OC9117151805020007",
            "4px_tracking_no" => "100000001864",
            'status' => 'S',
            'create_time' => $now * 1000,
            'update_time' => time() * 1000,
        ];
        $this->assertTrue($fulfillmentService->pushFulfillmentOrder($fulfillmentOrder), 'already exists, link and update');
        $this->assertEquals($externalOrderKey, $fulfillmentOrder->external_order_key);
        $this->assertEquals('O-1', $fulfillmentOrder->external_order_additional['ref_no']);
        $this->assertEquals('ORDER-1', $fulfillmentOrder->external_order_additional['sales_no']);
        $this->assertTrue($fulfillmentOrder->external_created_at > 0);
        $this->assertTrue($fulfillmentOrder->external_updated_at > $fulfillmentOrder->external_created_at);
        $this->assertEquals(FulfillmentConst::FULFILLMENT_STATUS_PROCESSING, $fulfillmentOrder->fulfillment_status);
    }

    /**
     * @inheritdoc
     */
    public function testCancelFulfillmentOrder(): void
    {
        $fulfillmentService = $this->getFulfillmentService();

        $fulfillmentOrder = new FulfillmentOrder();
        $fulfillmentOrder->fulfillment_type = FulfillmentConst::FULFILLMENT_TYPE_SHIPPING;
        $fulfillmentOrder->fulfillment_account_id = 1;
        $fulfillmentOrder->order_id = 1;
        $fulfillmentOrder->save(false);

        $now = time();
        MockF4pxClient::$RESPONSE_DATA[] = [];
        MockF4pxClient::$RESPONSE_DATA[] = [
            "ref_no" => "O-1",
            "sales_no" => "ORDER-1",
            "consignment_no" => "OC9117151805020007",
            "4px_tracking_no" => "100000001864",
            'status' => 'S',
            'create_time' => $now * 1000,
            'update_time' => time() * 1000,
        ];
        $this->assertTrue($fulfillmentService->pushFulfillmentOrder($fulfillmentOrder));
        $this->assertEquals(FulfillmentConst::FULFILLMENT_STATUS_PROCESSING, $fulfillmentOrder->fulfillment_status);

        MockF4pxClient::$RESPONSE_DATA[] = [
            "ref_no" => "O-1",
            "sales_no" => "ORDER-1",
            "consignment_no" => "OC9117151805020007",
            "4px_tracking_no" => "100000001864",
            'status' => 'X',
            'create_time' => $now * 1000,
            'update_time' => time() * 1000,
        ];
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
    public function testPullWarehouses(): void
    {
        MockF4pxClient::$RESPONSE_DATA[] = [
            [
                "warehouse_code" => "CNSZXB",
                "warehouse_name_cn" => "国际货站作业中心",
                "warehouse_name_en" => "International Operation Center",
                "country" => "CN",
                "service_code" => "R,F,S"
            ],
        ];
        $fulfillmentService = $this->getFulfillmentService();
        $fulfillmentService->pullWarehouses();
        $query = FulfillmentWarehouse::find()->externalWarehouseKey('CNSZXB');
        $this->assertEquals(1, $query->count());
        $fulfillmentWarehouse = $query->one();
        $expected = [
            'name_cn' => '国际货站作业中心',
            'name_en' => 'International Operation Center',
            'country' => 'CN',
            'service_code' => "R,F,S",
        ];
        $this->assertEquals($expected, $fulfillmentWarehouse->external_warehouse_additional);
    }

    /**
     * @inheritdoc
     */
    public function testPullWarehouseStocks(): void
    {
        MockF4pxClient::$RESPONSE_DATA[] = [
            [
                "warehouse_code" => "CNSZXB",
                "warehouse_name_cn" => "国际货站作业中心",
                "warehouse_name_en" => "International Operation Center",
                "country" => "CN",
                "service_code" => "R,F,S"
            ],
        ];
        $fulfillmentService = $this->getFulfillmentService();
        $fulfillmentService->pullWarehouses();

        $fulfillmentWarehouse = FulfillmentWarehouse::find()->externalWarehouseKey('CNSZXB')->one();
        $fulfillmentWarehouse->warehouse_id = 1;
        $fulfillmentWarehouse->save(false);

        $fulfillmentItem = new FulfillmentItem();
        $fulfillmentItem->fulfillment_account_id = 1;
        $fulfillmentItem->item_id = 1;
        $fulfillmentItem->external_item_key = '922000000127';
        $fulfillmentItem->external_item_additional = ['sku_code' => 'ITEM-1'];
        $fulfillmentItem->save(false);

        MockF4pxClient::$RESPONSE_DATA[] = [
            'page_size' => 50,
            'page_no' => 1,
            'total' => 1,
            'data' => [
                [
                    "sku_code" => "ITEM-1",
                    "warehouse_code" => "CNSZXB",
                    "sku_id" => "922000000127",
                    "batch_no" => "",
                    "stock_quality" => "G",
                    "available_stock" => "123",
                    "pending_stock" => "20",
                    "onway_stock" => "0"
                ],
                [
                    "sku_code" => "ITEM-2",
                    "warehouse_code" => "CNSZXB",
                    "sku_id" => "922000000128",
                    "batch_no" => "",
                    "stock_quality" => "G",
                    "available_stock" => "321",
                    "pending_stock" => "20",
                    "onway_stock" => "0"
                ],
            ]
        ];
        $fulfillmentService->pullWarehouseStocks([$fulfillmentItem]);
        $query = FulfillmentWarehouseStock::find()->itemId($fulfillmentItem->item_id);
        $this->assertEquals(1, $query->count());
        $warehouseStock = $query->one();
        $expectedStock = [
            'fulfillment_account_id' => 1,
            'item_id' => 1,
            'warehouse_id' => 1,
            'external_item_key' => '922000000127',
            'external_warehouse_key' => 'CNSZXB',
            'stock_qty' => 123,
        ];
        $this->assertEquals($expectedStock, $warehouseStock->getAttributes(array_keys($expectedStock)));
        $fulfillmentItem->refresh();
        $this->assertTrue($fulfillmentItem->stock_pulled_at > 0);
    }

    /**
     * @inheritdoc
     */
    public function testPullWarehouseStockMovements(): void
    {
        MockF4pxClient::$RESPONSE_DATA[] = [
            [
                "warehouse_code" => "CNSZXB",
                "warehouse_name_cn" => "国际货站作业中心",
                "warehouse_name_en" => "International Operation Center",
                "country" => "CN",
                "service_code" => "R,F,S"
            ],
        ];
        $fulfillmentService = $this->getFulfillmentService();
        $fulfillmentService->pullWarehouses();

        $fulfillmentWarehouse = FulfillmentWarehouse::find()->externalWarehouseKey('CNSZXB')->one();
        $fulfillmentWarehouse->warehouse_id = 1;
        $fulfillmentWarehouse->support_movement = 1;
        $fulfillmentWarehouse->save(false);

        $fulfillmentItem = new FulfillmentItem();
        $fulfillmentItem->fulfillment_account_id = 1;
        $fulfillmentItem->item_id = 1;
        $fulfillmentItem->external_item_key = '922000000127';
        $fulfillmentItem->external_item_additional = ['sku_code' => 'ITEM-1'];
        $fulfillmentItem->save(false);

        MockF4pxClient::$RESPONSE_DATA[] = [
            'page_size' => 50,
            'page_no' => 1,
            'total' => 1,
            'data' => [
                [
                    "inventory_flow_id" => "SLBA1566384313f8248b223c6e9e2dc9",
                    "sku_id" => "922000000127",
                    "sku_code" => "ITEM-1",
                    "warehouse_code" => "CNSZXB",
                    "business_type" => "P",
                    "business_ref_no" => "IC90027818042625",
                    "batch_no" => "",
                    "io_qty" => "123",
                    "balance_stock" => "3541",
                    "stock_quality" => "G",
                    "create_time" => "1589444083000",
                    "journal_type" => "I"
                ],
                [
                    "inventory_flow_id" => "SLBA1544384313f8248b223c6e9dddc9",
                    "sku_id" => "922000000128",
                    "sku_code" => "ITEM-2",
                    "warehouse_code" => "HKHKGD",
                    "business_type" => "P",
                    "business_ref_no" => "IC90027818042620",
                    "batch_no" => "",
                    "io_qty" => "100",
                    "balance_stock" => "3741",
                    "stock_quality" => "G",
                    "create_time" => "1589444083000",
                    "journal_type" => "I"
                ],
            ]
        ];
        $fulfillmentService->pullWarehouseStockMovements($fulfillmentWarehouse, strtotime('2020-05-14 16:14:00'), strtotime('2020-05-14 16:14:59'));
        $query = FulfillmentWarehouseStockMovement::find()->itemId($fulfillmentItem->item_id);
        $this->assertEquals(1, $query->count());
        $stockMovement = $query->one();
        $expectedMovement = [
            'fulfillment_account_id' => 1,
            'item_id' => 1,
            'warehouse_id' => 1,
            'external_item_key' => '922000000127',
            'external_warehouse_key' => 'CNSZXB',
            'external_movement_key' => 'SLBA1566384313f8248b223c6e9e2dc9',
            'movement_type' => FulfillmentConst::MOVEMENT_TYPE_INBOUND,
            'movement_qty' => 123,
            'related_type' => 'P',
            'related_key' => 'IC90027818042625',
            'external_created_at' => 1589444083,
        ];
        $this->assertEquals($expectedMovement, $stockMovement->getAttributes(array_keys($expectedMovement)));
        $fulfillmentItem->refresh();
        $fulfillmentWarehouse->refresh();
        $this->assertEquals(1589444083, $fulfillmentWarehouse->external_movement_at);

//        $this->cleanItem = true;
    }
}

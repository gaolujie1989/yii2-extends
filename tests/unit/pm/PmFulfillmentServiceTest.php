<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tests\unit\pm;


use lujie\data\loader\ArrayDataLoader;
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
use lujie\fulfillment\pm\PmFulfillmentService;
use lujie\plentyMarkets\PlentyMarketsRestClient;
use Yii;
use yii\httpclient\MockTransport;
use yii\httpclient\Response;

class PmFulfillmentServiceTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    /**
     * @return PmFulfillmentService
     * @inheritdoc
     */
    protected function getFulfillmentService(): PmFulfillmentService
    {
        return new PmFulfillmentService([
            'variationNoPrefix' => 'DIB-TEST-',
            'orderNoPrefix' => 'DIB-TEST-',
            'client' => [
                'class' => PlentyMarketsRestClient::class,
                'apiBaseUrl' => Yii::$app->params['pm.url'],
                'username' => Yii::$app->params['pm.username'],
                'password' => Yii::$app->params['pm.password'],
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
                                'code' => '4441249402551',
                            ]),
                            new ItemBarcode([
                                'name' => 'OWN_SKU',
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
        $mockTransport = new MockTransport();
        $fulfillmentService->client->httpClient->setTransport($mockTransport);

        $fulfillmentItem = new FulfillmentItem();
        $fulfillmentItem->save(false);
        $fulfillmentItem->fulfillment_account_id = 1;
        $fulfillmentItem->item_id = 1;
        $mockTransport->appendResponse(new Response([
            'headers' => [
                'http-code' => 200,
                'content-type' => 'application/json; charset=UTF-8',
            ],
            'content' => file_get_contents(__DIR__ . '/mockResponses/pullEmpty.json'),
        ]));
        //two barcodes will fetch twice
        $mockTransport->appendResponse(new Response([
            'headers' => [
                'http-code' => 200,
                'content-type' => 'application/json; charset=UTF-8',
            ],
            'content' => file_get_contents(__DIR__ . '/mockResponses/pullEmpty.json'),
        ]));
        $mockTransport->appendResponse(new Response([
            'headers' => [
                'http-code' => 200,
                'content-type' => 'application/json; charset=UTF-8',
            ],
            'content' => file_get_contents(__DIR__ . '/mockResponses/pushItem.json'),
        ]));
        $this->assertTrue($fulfillmentService->pushItem($fulfillmentItem), 'create');
        $this->assertEquals(3508, $fulfillmentItem->external_item_key);
        $this->assertEquals('DIB-TEST-ITEM-1', $fulfillmentItem->external_item_additional['variationNo']);
        $this->assertTrue($fulfillmentItem->external_created_at > 0);
        $this->assertTrue($fulfillmentItem->external_updated_at >= $fulfillmentItem->external_created_at);

        $fulfillmentItem->delete();
        $fulfillmentItem = new FulfillmentItem();
        $fulfillmentItem->save(false);
        $fulfillmentItem->fulfillment_account_id = 1;
        $fulfillmentItem->item_id = 1;

        $mockTransport->appendResponse(new Response([
            'headers' => [
                'http-code' => 200,
                'content-type' => 'application/json; charset=UTF-8',
            ],
            'content' => file_get_contents(__DIR__ . '/mockResponses/pullVariations.json'),
        ]));
        $mockTransport->appendResponse(new Response([
            'headers' => [
                'http-code' => 200,
                'content-type' => 'application/json; charset=UTF-8',
            ],
            'content' => file_get_contents(__DIR__ . '/mockResponses/pushVariation.json'),
        ]));
        $this->assertTrue($fulfillmentService->pushItem($fulfillmentItem), 'already exists, link and update');
        $this->assertEquals(3508, $fulfillmentItem->external_item_key);
        $this->assertEquals('DIB-TEST-ITEM-1', $fulfillmentItem->external_item_additional['variationNo']);
        $this->assertTrue($fulfillmentItem->external_created_at > 0);
        $this->assertTrue($fulfillmentItem->external_updated_at > $fulfillmentItem->external_created_at);
    }

    /**
     * @throws \Throwable
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     * @inheritdoc
     */
    public function testPushFulfillmentOrder(): void
    {
        $fulfillmentService = $this->getFulfillmentService();
        $mockTransport = new MockTransport();
        $fulfillmentService->client->httpClient->setTransport($mockTransport);

        $fulfillmentOrder = new FulfillmentOrder();
        $fulfillmentOrder->save(false);
        $fulfillmentOrder->fulfillment_account_id = 1;
        $fulfillmentOrder->order_id = 1;

        $fulfillmentItem = new FulfillmentItem();
        $fulfillmentItem->fulfillment_account_id = 1;
        $fulfillmentItem->item_id = 1;
        $fulfillmentItem->external_item_key = 3508;
        $fulfillmentItem->external_item_additional = ['itemId' => 10475, 'variationNo' => 'TEST_ABC'];
        $fulfillmentItem->save(false);

        $mockTransport->appendResponse(new Response([
            'headers' => [
                'http-code' => 200,
                'content-type' => 'application/json; charset=UTF-8',
            ],
            'content' => file_get_contents(__DIR__ . '/mockResponses/pullEmpty.json'),
        ]));
        //customer will fetch twice by email/phone
        $mockTransport->appendResponse(new Response([
            'headers' => [
                'http-code' => 200,
                'content-type' => 'application/json; charset=UTF-8',
            ],
            'content' => file_get_contents(__DIR__ . '/mockResponses/pullEmpty.json'),
        ]));
        $mockTransport->appendResponse(new Response([
            'headers' => [
                'http-code' => 200,
                'content-type' => 'application/json; charset=UTF-8',
            ],
            'content' => file_get_contents(__DIR__ . '/mockResponses/pullEmpty.json'),
        ]));
        $mockTransport->appendResponse(new Response([
            'headers' => [
                'http-code' => 200,
                'content-type' => 'application/json; charset=UTF-8',
            ],
            'content' => file_get_contents(__DIR__ . '/mockResponses/pushCustomer.json'),
        ]));
        $mockTransport->appendResponse(new Response([
            'headers' => [
                'http-code' => 200,
                'content-type' => 'application/json; charset=UTF-8',
            ],
            'content' => file_get_contents(__DIR__ . '/mockResponses/pushAddress.json'),
        ]));
        $mockTransport->appendResponse(new Response([
            'headers' => [
                'http-code' => 200,
                'content-type' => 'application/json; charset=UTF-8',
            ],
            'content' => file_get_contents(__DIR__ . '/mockResponses/pushOrder.json'),
        ]));
        $this->assertTrue($fulfillmentService->pushFulfillmentOrder($fulfillmentOrder), 'create');
        $this->assertEquals(1375033, $fulfillmentOrder->external_order_key);
        $this->assertEquals('DIB-TEST-ORDER-1', $fulfillmentOrder->external_order_additional['externalOrderNo']);
        $this->assertTrue($fulfillmentOrder->external_created_at > 0);
        $this->assertTrue($fulfillmentOrder->external_updated_at >= $fulfillmentOrder->external_created_at);
        $this->assertEquals(FulfillmentConst::FULFILLMENT_STATUS_PROCESSING, $fulfillmentOrder->fulfillment_status);

        $fulfillmentOrder->delete();
        $fulfillmentOrder = new FulfillmentOrder();
        $fulfillmentOrder->save(false);
        $fulfillmentOrder->fulfillment_account_id = 1;
        $fulfillmentOrder->order_id = 1;

        $mockTransport->appendResponse(new Response([
            'headers' => [
                'http-code' => 200,
                'content-type' => 'application/json; charset=UTF-8',
            ],
            'content' => file_get_contents(__DIR__ . '/mockResponses/pullOrders.json'),
        ]));
        //customer will fetch twice by email/phone
        $mockTransport->appendResponse(new Response([
            'headers' => [
                'http-code' => 200,
                'content-type' => 'application/json; charset=UTF-8',
            ],
            'content' => file_get_contents(__DIR__ . '/mockResponses/pullCustomers.json'),
        ]));
        $mockTransport->appendResponse(new Response([
            'headers' => [
                'http-code' => 200,
                'content-type' => 'application/json; charset=UTF-8',
            ],
            'content' => file_get_contents(__DIR__ . '/mockResponses/pushAddress.json'),
        ]));
        $mockTransport->appendResponse(new Response([
            'headers' => [
                'http-code' => 200,
                'content-type' => 'application/json; charset=UTF-8',
            ],
            'content' => file_get_contents(__DIR__ . '/mockResponses/pushOrder.json'),
        ]));
        $mockTransport->appendResponse(new Response([
            'headers' => [
                'http-code' => 200,
                'content-type' => 'application/json; charset=UTF-8',
            ],
            'content' => file_get_contents(__DIR__ . '/mockResponses/pushOrder.json'),
        ]));
        $this->assertTrue($fulfillmentService->pushFulfillmentOrder($fulfillmentOrder), 'already exists, link and update');
        $this->assertEquals(1375033, $fulfillmentOrder->external_order_key);
        $this->assertEquals('DIB-TEST-ORDER-1', $fulfillmentOrder->external_order_additional['externalOrderNo']);
        $this->assertTrue($fulfillmentOrder->external_created_at > 0);
        $this->assertTrue($fulfillmentOrder->external_updated_at > $fulfillmentOrder->external_created_at);
        $this->assertEquals(FulfillmentConst::FULFILLMENT_STATUS_PROCESSING, $fulfillmentOrder->fulfillment_status);
    }

    /**
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function testHoldShipCancelFulfillmentOrder(): void
    {
        $fulfillmentService = $this->getFulfillmentService();
        $mockTransport = new MockTransport();
        $fulfillmentService->client->httpClient->setTransport($mockTransport);

        $fulfillmentOrder = new FulfillmentOrder();
        $fulfillmentOrder->fulfillment_account_id = 1;
        $fulfillmentOrder->order_id = 1;
        $fulfillmentOrder->external_order_key = 1375033;
        $fulfillmentOrder->save(false);

        $mockTransport->appendResponse(new Response([
            'headers' => [
                'http-code' => 200,
                'content-type' => 'application/json; charset=UTF-8',
            ],
            'content' => file_get_contents(__DIR__ . '/mockResponses/holdingOrder.json'),
        ]));
        $fulfillmentService->holdFulfillmentOrder($fulfillmentOrder);
        $this->assertEquals(FulfillmentConst::FULFILLMENT_STATUS_HOLDING, $fulfillmentOrder->fulfillment_status);

        $mockTransport->appendResponse(new Response([
            'headers' => [
                'http-code' => 200,
                'content-type' => 'application/json; charset=UTF-8',
            ],
            'content' => file_get_contents(__DIR__ . '/mockResponses/pushOrder.json'),
        ]));
        $fulfillmentService->shipFulfillmentOrder($fulfillmentOrder);
        $this->assertEquals(FulfillmentConst::FULFILLMENT_STATUS_PROCESSING, $fulfillmentOrder->fulfillment_status);

        $mockTransport->appendResponse(new Response([
            'headers' => [
                'http-code' => 200,
                'content-type' => 'application/json; charset=UTF-8',
            ],
            'content' => file_get_contents(__DIR__ . '/mockResponses/cancelledOrder.json'),
        ]));
        $fulfillmentService->cancelFulfillmentOrder($fulfillmentOrder);
        $this->assertEquals(FulfillmentConst::FULFILLMENT_STATUS_CANCELLED, $fulfillmentOrder->fulfillment_status);
    }

    /**
     * @inheritdoc
     */
    public function testPullFulfillmentOrders(): void
    {
        $fulfillmentService = $this->getFulfillmentService();
        $mockTransport = new MockTransport();
        $fulfillmentService->client->httpClient->setTransport($mockTransport);

        $fulfillmentOrder = new FulfillmentOrder();
        $fulfillmentOrder->fulfillment_account_id = 1;
        $fulfillmentOrder->order_id = 1;
        $fulfillmentOrder->external_order_key = 1375033;
        $fulfillmentOrder->save(false);

        $mockTransport->appendResponse(new Response([
            'headers' => [
                'http-code' => 200,
                'content-type' => 'application/json; charset=UTF-8',
            ],
            'content' => file_get_contents(__DIR__ . '/mockResponses/pullShippedOrders.json'),
        ]));
        $mockTransport->appendResponse(new Response([
            'headers' => [
                'http-code' => 200,
                'content-type' => 'application/json; charset=UTF-8',
            ],
            'content' => file_get_contents(__DIR__ . '/mockResponses/pullShippedOrderPackageNumbers.json'),
        ]));
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
        $mockTransport = new MockTransport();
        $fulfillmentService->client->httpClient->setTransport($mockTransport);

        $mockTransport->appendResponse(new Response([
            'headers' => [
                'http-code' => 200,
                'content-type' => 'application/json; charset=UTF-8',
            ],
            'content' => file_get_contents(__DIR__ . '/mockResponses/pullWarehouses.json'),
        ]));
        $fulfillmentService->pullWarehouses();
        $externalWarehouseKeys = FulfillmentWarehouse::find()->select(['external_warehouse_key'])->column();
        $this->assertTrue(in_array(108, $externalWarehouseKeys));
    }

    /**
     * @inheritdoc
     */
    public function te1stPullWarehouseStocks(): void
    {
        $fulfillmentService = $this->getFulfillmentService();
        $mockTransport = new MockTransport();
        $fulfillmentService->client->httpClient->setTransport($mockTransport);

        $fulfillmentWarehouse = new FulfillmentWarehouse();
        $fulfillmentWarehouse->fulfillment_account_id = 1;
        $fulfillmentWarehouse->warehouse_id = 1;
        $fulfillmentWarehouse->external_warehouse_key = 108;
        $fulfillmentWarehouse->save(false);

        $fulfillmentItem = new FulfillmentItem();
        $fulfillmentItem->fulfillment_account_id = 1;
        $fulfillmentItem->item_id = 1;
        $fulfillmentItem->external_item_key = 3508;
        $fulfillmentItem->external_item_additional = ['itemId' => 10475, 'variationNo' => 'TEST_ABC'];
        $fulfillmentItem->save(false);

        $mockTransport->appendResponse(new Response([
            'headers' => [
                'http-code' => 200,
                'content-type' => 'application/json; charset=UTF-8',
            ],
            'content' => file_get_contents(__DIR__ . '/mockResponses/pullStocks.json'),
        ]));
        $fulfillmentService->pullWarehouseStocks([$fulfillmentItem]);
        $query = FulfillmentWarehouseStock::find()->itemId($fulfillmentItem->item_id);
        $this->assertEquals(1, $query->count());
        $warehouseStock = $query->one();
        $expectedStock = [
            'fulfillment_account_id' => 1,
            'item_id' => 1,
            'warehouse_id' => 1,
            'external_item_key' => '3508',
            'external_warehouse_key' => '108',
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
        $fulfillmentService = $this->getFulfillmentService();
        $mockTransport = new MockTransport();
        $fulfillmentService->client->httpClient->setTransport($mockTransport);

        $fulfillmentWarehouse = new FulfillmentWarehouse();
        $fulfillmentWarehouse->fulfillment_account_id = 1;
        $fulfillmentWarehouse->warehouse_id = 1;
        $fulfillmentWarehouse->external_warehouse_key = 108;
        $fulfillmentWarehouse->support_movement = 1;
        $fulfillmentWarehouse->save(false);

        $fulfillmentItem = new FulfillmentItem();
        $fulfillmentItem->fulfillment_account_id = 1;
        $fulfillmentItem->item_id = 1;
        $fulfillmentItem->external_item_key = 3508;
        $fulfillmentItem->external_item_additional = ['itemId' => 10475, 'variationNo' => 'TEST_ABC'];
        $fulfillmentItem->save(false);

        $mockTransport->appendResponse(new Response([
            'headers' => [
                'http-code' => 200,
                'content-type' => 'application/json; charset=UTF-8',
            ],
            'content' => file_get_contents(__DIR__ . '/mockResponses/pullStockMovements.json'),
        ]));
        $fulfillmentService->pullWarehouseStockMovements($fulfillmentWarehouse, 0, time());
        $query = FulfillmentWarehouseStockMovement::find()->itemId($fulfillmentItem->item_id);
        $this->assertEquals(4, $query->count());
        $stockMovement = $query->externalMovementKey('1576093')->one();
        $expectedMovement = [
            'fulfillment_account_id' => 1,
            'item_id' => 1,
            'warehouse_id' => 1,
            'external_item_key' => '3508',
            'external_warehouse_key' => '108',
            'external_movement_key' => '1576093',
            'moved_qty' => 1,
            'balance_qty' => 0,
            'reason' => '113',
            'related_type' => '2',
            'related_key' => '1375033',
            'external_created_at' => 1605688392,
        ];
        $this->assertEquals($expectedMovement, $stockMovement->getAttributes(array_keys($expectedMovement)));
        $fulfillmentItem->refresh();
        $fulfillmentWarehouse->refresh();
        $this->assertEquals(1605688392, $fulfillmentWarehouse->external_movement_at);

//        $this->cleanItem = true;
    }
}
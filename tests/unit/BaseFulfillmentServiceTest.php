<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tests\unit;


use lujie\data\loader\ArrayDataLoader;
use lujie\fulfillment\BaseFulfillmentService;
use lujie\fulfillment\common\Address;
use lujie\fulfillment\common\Item;
use lujie\fulfillment\common\ItemBarcode;
use lujie\fulfillment\common\Order;
use lujie\fulfillment\common\OrderItem;
use lujie\fulfillment\models\FulfillmentAccount;
use lujie\fulfillment\models\FulfillmentItem;
use lujie\fulfillment\models\FulfillmentOrder;
use lujie\fulfillment\tests\unit\mocks\MockFulfillmentService;

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
        return new MockFulfillmentService([
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
                                'code' => '4251249402551',
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
                        'orderNo' => 'ORDER-2',
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
        $fulfillmentItem->fulfillment_account_id = 2;
        $this->assertFalse($fulfillmentService->pushItem($fulfillmentItem), 'Account not equal, should return false');

        $fulfillmentItem->fulfillment_account_id = 1;
        $fulfillmentItem->item_id = 2;
        $this->assertFalse($fulfillmentService->pushItem($fulfillmentItem), 'Item not found, should return false');

        MockFulfillmentService::$GENERATE_ITEM_KEYS = [11, 22];
        $fulfillmentItem->item_id = 1;
        $this->assertTrue($fulfillmentService->pushItem($fulfillmentItem), 'create');
        $this->assertEquals('11', $fulfillmentItem->external_item_key);
        $this->assertTrue($fulfillmentItem->external_created_at >= time());
        $this->assertTrue($fulfillmentItem->external_updated_at === $fulfillmentItem->external_created_at);

        $fulfillmentItem->delete();
        $fulfillmentItem = new FulfillmentItem();
        $fulfillmentItem->save(false);
        $fulfillmentItem->fulfillment_account_id = 1;
        $fulfillmentItem->item_id = 1;
        $this->assertTrue($fulfillmentService->pushItem($fulfillmentItem), 'already exists, link and update');
        $this->assertEquals('11', $fulfillmentItem->external_item_key);
        $this->assertTrue($fulfillmentItem->external_created_at >= time());
        $this->assertTrue($fulfillmentItem->external_updated_at > $fulfillmentItem->external_created_at);
    }

    /**
     * @inheritdoc
     */
    public function testPushFulfillmentOrder(): void
    {
        $fulfillmentService = $this->getFulfillmentService();

        $fulfillmentOrder = new FulfillmentOrder();
        $fulfillmentOrder->save(false);
        $fulfillmentOrder->fulfillment_account_id = 2;
        $this->assertFalse($fulfillmentService->pushFulfillmentOrder($fulfillmentOrder), 'Account not equal, should return false');

        $fulfillmentOrder->fulfillment_account_id = 1;
        $fulfillmentOrder->order_id = 2;
        $this->assertFalse($fulfillmentService->pushFulfillmentOrder($fulfillmentOrder), 'Order not found, should return false');

        MockFulfillmentService::$GENERATE_ORDER_KEYS = [11, 22];
        $fulfillmentOrder->order_id = 1;
        $this->assertTrue($fulfillmentService->pushFulfillmentOrder($fulfillmentOrder), 'create');
        $this->assertEquals('11', $fulfillmentOrder->external_order_key);
        $this->assertTrue($fulfillmentOrder->external_created_at >= time());
        $this->assertTrue($fulfillmentOrder->external_updated_at === $fulfillmentOrder->external_created_at);

        $fulfillmentOrder->delete();
        $fulfillmentOrder = new FulfillmentOrder();
        $fulfillmentOrder->save(false);
        $fulfillmentOrder->fulfillment_account_id = 1;
        $fulfillmentOrder->order_id = 1;
        $this->assertTrue($fulfillmentService->pushFulfillmentOrder($fulfillmentOrder), 'already exists, link and update');
        $this->assertEquals('11', $fulfillmentOrder->external_order_key);
        $this->assertTrue($fulfillmentOrder->external_created_at >= time());
        $this->assertTrue($fulfillmentOrder->external_updated_at > $fulfillmentOrder->external_created_at);
    }

    /**
     * @inheritdoc
     */
    public function testHoldFulfillmentOrder(): void
    {

    }

    /**
     * @inheritdoc
     */
    public function testShipFulfillmentOrder(): void
    {

    }

    /**
     * @inheritdoc
     */
    public function testCancelFulfillmentOrder(): void
    {

    }

    /**
     * @inheritdoc
     */
    public function testPullFulfillmentOrders(): void
    {

    }

    /**
     * @inheritdoc
     */
    public function testPullWarehouseStocks(): void
    {

    }
}
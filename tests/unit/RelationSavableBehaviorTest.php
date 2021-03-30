<?php

namespace lujie\ar\relation\behaviors\tests\unit;

use lujie\ar\relation\behaviors\RelationSavableBehavior;
use lujie\ar\relation\behaviors\tests\unit\fixtures\models\TestAddress;
use lujie\ar\relation\behaviors\tests\unit\fixtures\models\TestCustomer;
use lujie\ar\relation\behaviors\tests\unit\fixtures\models\TestOrder;
use lujie\ar\relation\behaviors\tests\unit\fixtures\models\TestOrderItem;
use lujie\ar\relation\behaviors\tests\unit\fixtures\models\TestOrderPayment;
use yii\base\InvalidCallException;
use yii\helpers\ArrayHelper;

class RelationSavableBehaviorTest extends \Codeception\Test\Unit
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
     * @inheritdoc
     */
    public function testSaveWithOrderItems(): void
    {
        $saveModes = [
            RelationSavableBehavior::SAVE_MODE_MODEL,
            RelationSavableBehavior::SAVE_MODE_LINK,
        ];
        $deleteModes = [
            RelationSavableBehavior::DELETE_MODE_MODEL,
            RelationSavableBehavior::DELETE_MODE_SQL,
            RelationSavableBehavior::DELETE_MODE_UNLINK,
        ];
        foreach ($saveModes as $saveMode) {
            foreach ($deleteModes as $deleteMode) {
                $this->saveWithOrderItems($saveMode, $deleteMode, true);
                $this->saveWithOrderItems($saveMode, $deleteMode, false);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function testSaveWithCustomer(): void
    {
        $saveModes = [
            RelationSavableBehavior::SAVE_MODE_MODEL,
            RelationSavableBehavior::SAVE_MODE_LINK,
        ];
        $deleteModes = [
            RelationSavableBehavior::DELETE_MODE_MODEL,
            RelationSavableBehavior::DELETE_MODE_SQL,
            RelationSavableBehavior::DELETE_MODE_UNLINK,
        ];
        foreach ($saveModes as $saveMode) {
            foreach ($deleteModes as $deleteMode) {
                $this->saveWithCustomer($saveMode, $deleteMode);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function testSaveWithAddress(): void
    {
        $saveModes = [
            RelationSavableBehavior::SAVE_MODE_MODEL,
            RelationSavableBehavior::SAVE_MODE_LINK,
        ];
        $deleteModes = [
            RelationSavableBehavior::DELETE_MODE_MODEL,
            RelationSavableBehavior::DELETE_MODE_SQL,
            RelationSavableBehavior::DELETE_MODE_UNLINK,
        ];
        foreach ($saveModes as $saveMode) {
            foreach ($deleteModes as $deleteMode) {
                $this->saveWithAddress($saveMode, $deleteMode);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function testSaveWithPayments(): void
    {
        $saveModes = [
            RelationSavableBehavior::SAVE_MODE_MODEL,
//            RelationSavableBehavior::SAVE_MODE_LINK,
        ];
        $deleteModes = [
            RelationSavableBehavior::DELETE_MODE_MODEL,
//            RelationSavableBehavior::DELETE_MODE_SQL,
//            RelationSavableBehavior::DELETE_MODE_UNLINK,
        ];
        foreach ($saveModes as $saveMode) {
            foreach ($deleteModes as $deleteMode) {
                $this->saveWithExistPaymentsUseExistsTemp($saveMode, $deleteMode);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function saveWithOrderItems(
        $saveMode = RelationSavableBehavior::SAVE_MODE_MODEL,
        $deleteMode = RelationSavableBehavior::DELETE_MODE_MODEL,
        $useItemNoAsIndexKey = false
    ): void
    {
        $orderData = [
            'order_no' => 'TEST_WITH_INSERT_ORDER_ITEMS',
        ];
        $orderItemData = [
            [
                'item_no' => 'item111',
                'ordered_qty' => 1
            ],
            [
                'item_no' => 'item222',
                'ordered_qty' => 2
            ],
        ];

        $testOrder = new TestOrder([
            'as relationSave' => [
                'class' => RelationSavableBehavior::class,
                'relations' => ['orderItems'],
                'indexKeys' => $useItemNoAsIndexKey ? ['orderItems' => 'item_no'] : [],
                'saveModes' => ['orderItems' => $saveMode],
                'deleteModes' => ['orderItems' => $deleteMode]
            ]
        ]);
        $testOrder->load($orderData, '');
        $testOrder->orderItems = $orderItemData;
        $this->assertTrue($testOrder->save());
        $testOrder->refresh();
        $savedOrderItems = ArrayHelper::toArray(
            $testOrder->orderItems,
            [TestOrderItem::class => ['item_no', 'ordered_qty']]
        );
        $this->assertEquals($orderItemData, array_values($savedOrderItems));

        //test update order items
        $updateOrderItems = $testOrder->orderItems;
        unset($updateOrderItems[0]);
        $updateOrderItems[1] = $updateOrderItems[1]->attributes;
        $updateOrderItems[] = [
            'item_no' => 'item333',
            'ordered_qty' => 3
        ];
        $orderItemData = [
            [
                'item_no' => 'item222',
                'ordered_qty' => 2
            ],
            [
                'item_no' => 'item333',
                'ordered_qty' => 3
            ],
        ];
        $testOrder->orderItems = $updateOrderItems;
        $testOrder->order_no = 'TEST_WITH_UPDATE_ORDER_ITEMS';
        $this->assertTrue($testOrder->save());
        $testOrder->refresh();
        $savedOrderItems = ArrayHelper::toArray(
            $testOrder->orderItems,
            [TestOrderItem::class => ['item_no', 'ordered_qty']]
        );
        $this->assertEquals($orderItemData, array_values($savedOrderItems));
    }

    public function saveWithCustomer(
        $saveMode = RelationSavableBehavior::SAVE_MODE_MODEL,
        $deleteMode = RelationSavableBehavior::DELETE_MODE_MODEL
    ): void
    {
        $orderData = [
            'order_no' => 'TEST_WITH_INSERT_CUSTOMER',
            'customer_email' => 'xxx111@test.dev',
        ];
        $customerData = [
            'customer_email' => 'xxx111@test.dev',
            'username' => 'customer111',
        ];

        $testOrder = new TestOrder([
            'as relationSave' => [
                'class' => RelationSavableBehavior::class,
                'relations' => ['customer'],
                'saveModes' => ['customer' => $saveMode],
                'deleteModes' => ['customer' => $deleteMode]
            ]
        ]);
        $testOrder->load($orderData, '');
        $testOrder->customer = $customerData;
        if ($saveMode === RelationSavableBehavior::SAVE_MODE_LINK) {
            try {
                $testOrder->save();
            } catch (\Exception $exception) {
                $this->assertInstanceOf(InvalidCallException::class, $exception);
                return;
            }
            $this->assertTrue(false, 'SAVE_MODE_LINK should not supported');
        }

        $this->assertTrue($testOrder->save());
        $testOrder->refresh();
        $savedCustomer = $testOrder->customer->toArray(array_keys($customerData));
        $this->assertEquals($customerData, $savedCustomer);

        //test update customer
        $customerData = [
            'customer_email' => 'xxx111@test.dev',
            'username' => 'customer12345',
        ];
        $testOrder->customer_email = $customerData['customer_email'];
        $testOrder->order_no = 'TEST_WITH_UPDATE_CUSTOMER';
        $testOrder->customer = $customerData;
        $this->assertTrue($testOrder->save());
        $testOrder->refresh();
        $savedCustomer = $testOrder->customer->toArray(array_keys($customerData));
        $this->assertEquals($customerData, $savedCustomer);

        //test change to another customer,
        $customerData = [
            'customer_email' => 'xxx222@test.dev',
            'username' => 'customer222',
        ];
        $testOrder->order_no = 'TEST_WITH_CHANGE_CUSTOMER';
        $testOrder->customer_email = $customerData['customer_email'];
        $testOrder->customer = $customerData;
        $this->assertTrue($testOrder->save());
        $testOrder->refresh();
        $savedCustomer = $testOrder->customer->toArray(array_keys($customerData));
        $this->assertEquals($customerData, $savedCustomer);

        //origin one should be not deleted
        $query = TestCustomer::find()->andWhere(['customer_email' => 'xxx111@test.dev']);
        $this->assertTrue($query->exists());
    }

    public function saveWithAddress(
        $saveMode = RelationSavableBehavior::SAVE_MODE_MODEL,
        $deleteMode = RelationSavableBehavior::DELETE_MODE_MODEL
    ): void
    {
        $orderData = [
            'order_no' => 'TEST_WITH_INSERT_ADDRESS',
            'customer_email' => 'xxx111@test.dev',
        ];
        $addressData = [
            'street' => 'street 111',
        ];

        $testOrder = new TestOrder([
            'as relationSave' => [
                'class' => RelationSavableBehavior::class,
                'relations' => ['shippingAddress'],
                'saveModes' => ['shippingAddress' => $saveMode],
                'deleteModes' => ['shippingAddress' => $deleteMode]
            ]
        ]);
        $testOrder->load($orderData, '');
        $testOrder->shippingAddress = $addressData;
        if ($saveMode === RelationSavableBehavior::SAVE_MODE_LINK) {
            try {
                $testOrder->save();
            } catch (\Exception $exception) {
                $this->assertInstanceOf(InvalidCallException::class, $exception);
                return;
            }
            $this->assertTrue(false, 'SAVE_MODE_LINK should not supported');
        }

        $this->assertTrue($testOrder->save());
        $testOrder->refresh();
        $savedAddress = $testOrder->shippingAddress->toArray(array_keys($addressData));
        $this->assertEquals($addressData, $savedAddress);

        //test update address
        $addressData = [
            'street' => 'street 222',
        ];
        $testOrder->order_no = 'TEST_WITH_UPDATE_ADDRESS';
        $testOrder->shippingAddress = $addressData;
        $this->assertTrue($testOrder->save());
        $testOrder->refresh();
        $savedAddress = $testOrder->shippingAddress->toArray(array_keys($addressData));
        $this->assertEquals($addressData, $savedAddress);

        //test change to another address,
        $addressData = [
            'street' => 'street 333',
        ];
        $testOrder->order_no = 'TEST_WITH_CHANGE_ADDRESS';
        $testOrder->shipping_address_id = 123;
        $testOrder->shippingAddress = $addressData;
        $this->assertTrue($testOrder->save());
        $testOrder->refresh();
        $savedAddress = $testOrder->shippingAddress->toArray(array_keys($addressData));
        $this->assertEquals($addressData, $savedAddress);

        //origin one should be not deleted
        $query = TestAddress::find()->andWhere(['street' => 'street 222']);
        $this->assertTrue($query->exists());
    }

    public function saveWithExistPaymentsUseExistsTemp(
        $saveMode = RelationSavableBehavior::SAVE_MODE_MODEL,
        $deleteMode = RelationSavableBehavior::DELETE_MODE_MODEL
    ): void
    {
        $orderData = [
            'order_no' => 'TEST_WITH_EXIST_PAYMENTS',
            'customer_email' => 'xxx111@test.dev',
        ];
        $paymentData = [
            [
                'transaction_no' => 'TTT111'
            ],
            [
                'transaction_no' => 'TTT222',
            ],
        ];
        $existPayments = [];
        foreach ($paymentData as $paymentValues) {
            $payment = new TestOrderPayment();
            $payment->setAttributes($paymentValues);
            $payment->save(false);
            $existPayments[] = $payment;
        }
        $testOrder = new TestOrder([
            'as relationSave' => [
                'class' => RelationSavableBehavior::class,
                'relations' => ['orderPayments'],
                'indexKeys' => ['orderPayments' => 'transaction_no'],
                'linkUnlinkRelations' => ['orderPayments'],
                'saveModes' => ['orderPayments' => $saveMode],
                'deleteModes' => ['orderPayments' => $deleteMode]
            ]
        ]);
        $testOrder->load($orderData, '');
        $testOrder->orderPayments = $paymentData;

        $this->assertTrue($testOrder->save());
        $testOrder->refresh();
        $savedPayments = ArrayHelper::toArray(
            $testOrder->orderPayments,
            [TestOrderPayment::class => ['test_order_payment_id', 'transaction_no']]
        );
        $existPayments = ArrayHelper::toArray(
            $existPayments,
            [TestOrderPayment::class => ['test_order_payment_id', 'transaction_no']]
        );
        $this->assertEquals($existPayments, $savedPayments);
    }
}

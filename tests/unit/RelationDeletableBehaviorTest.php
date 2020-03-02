<?php

namespace lujie\ar\relation\behaviors\tests\unit;

use lujie\ar\relation\behaviors\RelationDeletableBehavior;
use lujie\ar\relation\behaviors\RelationSavableBehavior;
use lujie\ar\relation\behaviors\tests\unit\fixtures\models\TestAddress;
use lujie\ar\relation\behaviors\tests\unit\fixtures\models\TestCustomer;
use lujie\ar\relation\behaviors\tests\unit\fixtures\models\TestOrder;
use lujie\ar\relation\behaviors\tests\unit\fixtures\models\TestOrderItem;

class RelationDeletableBehaviorTest extends \Codeception\Test\Unit
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
    public function testDeleteWithOrderItems(): void
    {
        $deleteModes = [
            RelationDeletableBehavior::DELETE_MODE_MODEL,
            RelationDeletableBehavior::DELETE_MODE_SQL,
            RelationDeletableBehavior::DELETE_MODE_UNLINK,
        ];
        foreach ($deleteModes as $deleteMode) {
            $this->initOrderRelationData();
            $this->deleteWithOrderItems($deleteMode);
        }
    }

    /**
     * @inheritdoc
     */
    public function testDeleteWithCustomer(): void
    {
        $deleteModes = [
            RelationDeletableBehavior::DELETE_MODE_MODEL,
            RelationDeletableBehavior::DELETE_MODE_SQL,
            RelationDeletableBehavior::DELETE_MODE_UNLINK,
        ];
        foreach ($deleteModes as $deleteMode) {
            $this->initOrderRelationData();
            $this->deleteWithCustomer($deleteMode);
        }
    }

    /**
     * @inheritdoc
     */
    public function testDeleteWithAddress(): void
    {
        $deleteModes = [
            RelationDeletableBehavior::DELETE_MODE_MODEL,
            RelationDeletableBehavior::DELETE_MODE_SQL,
            RelationDeletableBehavior::DELETE_MODE_UNLINK,
        ];
        foreach ($deleteModes as $deleteMode) {
            $this->initOrderRelationData();
            $this->deleteWithAddress($deleteMode);
        }
    }

    public function initOrderRelationData()
    {
        $orderData = [
            'order_no' => 'TEST_WITH_DELETE_ORDER',
            'customer_email' => 'xxx111@test.dev',
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
        $customerData = [
            'customer_email' => 'xxx111@test.dev',
            'username' => 'customer111',
        ];
        $addressData = [
            'street' => 'street 111',
        ];

        $testOrder = new TestOrder([
            'as relationSave' => [
                'class' => RelationSavableBehavior::class,
                'relations' => ['orderItems', 'customer', 'shippingAddress'],
            ],
        ]);
        $testOrder->load($orderData, '');
        $testOrder->orderItems = $orderItemData;
        $testOrder->customer = $customerData;
        $testOrder->shippingAddress = $addressData;
        $this->assertTrue($testOrder->save());
    }

    /**
     * @param string $saveMode
     * @param string $deleteMode
     * @param bool $useItemNoAsIndexKey
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @inheritdoc
     */
    public function deleteWithOrderItems(
        $deleteMode = RelationDeletableBehavior::DELETE_MODE_MODEL
    ): void
    {
        $testOrder = TestOrder::findOne(['order_no' => 'TEST_WITH_DELETE_ORDER']);
        $testOrder->attachBehavior('relationDelete', [
            'class' => RelationDeletableBehavior::class,
            'relations' => ['orderItems'],
            'deleteModes' => ['orderItems' => $deleteMode]
        ]);
        $query = TestOrderItem::find()->andWhere(['test_order_id' => $testOrder->test_order_id]);
        $this->assertEquals(1, $testOrder->delete());
        $this->assertEquals(0, $query->count());
    }

    public function deleteWithCustomer(
        $deleteMode = RelationDeletableBehavior::DELETE_MODE_MODEL
    ): void
    {
        $testOrder = TestOrder::findOne(['order_no' => 'TEST_WITH_DELETE_ORDER']);
        $testOrder->attachBehavior('relationDelete', [
            'class' => RelationDeletableBehavior::class,
            'relations' => ['customer'],
            'deleteModes' => ['customer' => $deleteMode]
        ]);
        $query = TestCustomer::find()->andWhere(['customer_email' => $testOrder->customer_email]);
        $this->assertEquals(1, $testOrder->delete());
        $this->assertEquals(0, $query->count());
    }

    public function deleteWithAddress(
        $deleteMode = RelationDeletableBehavior::DELETE_MODE_MODEL
    ): void
    {
        $testOrder = TestOrder::findOne(['order_no' => 'TEST_WITH_DELETE_ORDER']);
        $testOrder->attachBehavior('relationDelete', [
            'class' => RelationDeletableBehavior::class,
            'relations' => ['shippingAddress'],
            'deleteModes' => ['shippingAddress' => $deleteMode]
        ]);
        $query = TestAddress::find()->andWhere(['test_address_id' => $testOrder->shipping_address_id]);
        $this->assertEquals(1, $testOrder->delete());
        $this->assertEquals(0, $query->count());
    }
}

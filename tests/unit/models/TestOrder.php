<?php

namespace lujie\relation\behaviors\tests\unit\models;

use yii\db\ActiveQuery;

/**
 * This is the model class for table "test_order".
 *
 * @property string $test_order_id
 * @property string $order_no
 * @property string $customer_email
 * @property string $shipping_address_id
 *
 * @property TestOrderItem[] $orderItems
 * @property TestOrderPayment[] $orderPayments
 * @property TestCustomer $customer
 * @property TestAddress $shippingAddress
 */
class TestOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'test_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shipping_address_id'], 'integer'],
            [['order_no', 'customer_email'], 'string', 'max' => 255],
            [['customer_email'], 'email'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'test_order_id' => 'Test Order ID',
            'order_no' => 'Order No',
            'customer_email' => 'Customer Email',
            'shipping_address_id' => 'Shipping Address ID',
        ];
    }

    /**
     * @inheritdoc
     */
    public function getOrderItems(): ActiveQuery
    {
        return $this->hasMany(TestOrderItem::class, ['test_order_id' => 'test_order_id']);
    }

    public function getOrderPayments(): ActiveQuery
    {
        return $this->hasMany(TestOrderPayment::class, ['test_order_id' => 'test_order_id']);
    }

    public function getCustomer(): ActiveQuery
    {
        return $this->hasOne(TestCustomer::class, ['customer_email' => 'customer_email']);
    }

    public function getShippingAddress(): ActiveQuery
    {
        return $this->hasOne(TestAddress::class, ['test_address_id' => 'shipping_address_id']);
    }
}

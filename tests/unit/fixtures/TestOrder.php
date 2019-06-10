<?php

namespace lujie\state\machine\tests\unit\fixtures;

/**
 * This is the model class for table "test_order".
 *
 * @property string $test_order_id
 * @property string $order_no
 * @property string $customer_email
 * @property string $shipping_address_id
 * @property string $status
 */
class TestOrder extends \yii\db\ActiveRecord
{
    public const STATUS_PENDING = 0;
    public const STATUS_PAID = 10;
    public const STATUS_SHIPPED = 20;
    public const STATUS_CANCELLED = 110;
    public const STATUS_REFUNDED = 120;

    public const SCENARIO_PENDING = 'PENDING';
    public const SCENARIO_PAID = 'PAID';
    public const SCENARIO_SHIPPED = 'SHIPPED';
    public const SCENARIO_FINISHED = 'FINISHED';

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
            [['shipping_address_id', 'status'], 'integer'],
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
            'status' => 'Status',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_PENDING] = ['status', 'shipping_address_id', 'customer_email'];
        $scenarios[self::SCENARIO_PAID] = ['status', 'shipping_address_id'];
        $scenarios[self::SCENARIO_SHIPPED] = ['status'];
        $scenarios[self::SCENARIO_FINISHED] = [];
        return $scenarios;
    }
}

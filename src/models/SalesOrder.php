<?php

namespace lujie\sales\order\center\models;

use lujie\common\address\models\Address;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use lujie\sales\order\center\Module;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%sales_order}}".
 *
 * @property int $sales_order_id
 * @property int $sales_account_id
 * @property string $sales_account_name
 * @property int $customer_id
 * @property string $customer_email
 * @property string $customer_phone
 * @property int $shipping_address_id
 * @property int $billing_address_id
 * @property string $external_order_id
 * @property string $external_order_no
 * @property string $platform
 * @property string $country
 * @property string $shipping_country
 * @property string $currency
 * @property string $payment_method
 * @property int $payment_status
 * @property string $transaction_no
 * @property string $shipping_method
 * @property int $shipping_status
 * @property array $shipping_numbers
 * @property int $ordered_at
 * @property int $paid_at
 * @property int $shipped_at
 * @property int $completed_at
 * @property int $closed_at
 * @property int $refund_at
 * @property int $cancelled_at
 * @property string $cancel_reason
 * @property string $note
 * @property int $status
 *
 * @property Customer $customer
 * @property Address $shippingAddress
 * @property Address $billingAddress
 * @property SalesOrderItem[] $orderItems
 * @property SalesOrderAmount $orderAmount
 * @property SalesOrderAmount $systemCurrencyOrderAmount
 */
class SalesOrder extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%sales_order}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['sales_account_id', 'sales_account_name', 'customer_id',
                'platform', 'country', 'shipping_country', 'currency'], 'required'],
            [['sales_account_id', 'customer_id', 'shipping_address_id', 'billing_address_id', 'external_order_id',
                'payment_status', 'shipping_status',
                'ordered_at', 'paid_at', 'shipped_at', 'completed_at', 'closed_at', 'refund_at', 'cancelled_at',
                'status'], 'integer'],
            [['shipping_numbers'], 'safe'],
            [['sales_account_name', 'customer_phone', 'external_order_no',
                'payment_method', 'transaction_no', 'shipping_method'], 'string', 'max' => 50],
            [['customer_email'], 'string', 'max' => 100],
            [['platform'], 'string', 'max' => 20],
            [['country', 'shipping_country'], 'string', 'max' => 2],
            [['currency'], 'string', 'max' => 3],
            [['cancel_reason', 'note'], 'string', 'max' => 255],
        ];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'sales_order_id' => Yii::t('sales/order', 'Sales Order ID'),
            'sales_account_id' => Yii::t('sales/order', 'Sales Account ID'),
            'sales_account_name' => Yii::t('sales/order', 'Sales Account Name'),
            'customer_id' => Yii::t('sales/order', 'Customer ID'),
            'customer_email' => Yii::t('sales/order', 'Customer Email'),
            'customer_phone' => Yii::t('sales/order', 'Customer Phone'),
            'shipping_address_id' => Yii::t('sales/order', 'Shipping Address ID'),
            'billing_address_id' => Yii::t('sales/order', 'Billing Address ID'),
            'external_order_id' => Yii::t('sales/order', 'External Order ID'),
            'external_order_no' => Yii::t('sales/order', 'External Order No'),
            'platform' => Yii::t('sales/order', 'Platform'),
            'country' => Yii::t('sales/order', 'Country'),
            'shipping_country' => Yii::t('sales/order', 'Shipping Country'),
            'currency' => Yii::t('sales/order', 'Currency'),
            'payment_method' => Yii::t('sales/order', 'Payment Method'),
            'payment_status' => Yii::t('sales/order', 'Payment Status'),
            'transaction_no' => Yii::t('sales/order', 'Transaction No'),
            'shipping_method' => Yii::t('sales/order', 'Shipping Method'),
            'shipping_status' => Yii::t('sales/order', 'Shipping Status'),
            'shipping_numbers' => Yii::t('sales/order', 'Shipping Numbers'),
            'ordered_at' => Yii::t('sales/order', 'Ordered At'),
            'paid_at' => Yii::t('sales/order', 'Paid At'),
            'shipped_at' => Yii::t('sales/order', 'Shipped At'),
            'completed_at' => Yii::t('sales/order', 'Completed At'),
            'closed_at' => Yii::t('sales/order', 'Closed At'),
            'refund_at' => Yii::t('sales/order', 'Refund At'),
            'cancelled_at' => Yii::t('sales/order', 'Cancelled At'),
            'cancel_reason' => Yii::t('sales/order', 'Cancel Reason'),
            'note' => Yii::t('sales/order', 'Note'),
            'status' => Yii::t('sales/order', 'Status'),
        ];
    }

    /**
     * @return SalesOrderQuery
     * @inheritdoc
     */
    public static function find(): SalesOrderQuery
    {
        return new SalesOrderQuery(static::class);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getCustomer(): ActiveQuery
    {
        return $this->hasOne(Customer::class, ['customer_id' => 'customer_id']);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getShippingAddress(): ActiveQuery
    {
        return $this->hasOne(Address::class, ['address_id' => 'shipping_address_id']);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getBillingAddress(): ActiveQuery
    {
        return $this->hasOne(Customer::class, ['address_id' => 'billing_address_id']);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getOrderItems(): ActiveQuery
    {
        return $this->hasMany(SalesOrderItem::class, ['sales_order_id' => 'sales_order_id']);
    }

    /**
     * @inheritdoc
     */
    public function getOrderAmount(): SalesOrderAmountQuery
    {
        /** @var SalesOrderAmountQuery $query */
        $query = $this->hasOne(SalesOrderAmount::class, ['sales_order_id' => 'sales_order_id', 'currency' => 'currency']);
        return $query->orderAmount();
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getSystemCurrencyOrderAmount(): ActiveQuery
    {
        /** @var SalesOrderAmountQuery $query */
        $query = $this->hasOne(SalesOrderAmount::class, ['sales_order_id' => 'sales_order_id']);
        return $query->orderAmount()->currency(Module::getSystemCurrency());
    }
}

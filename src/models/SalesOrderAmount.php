<?php

namespace lujie\sales\order\center\models;

use lujie\alias\behaviors\MoneyAliasBehavior;
use lujie\extend\db\TraceableBehaviorTrait;
use Yii;

/**
 * This is the model class for table "{{%sales_order_amount}}".
 *
 * @property string $sales_order_amount_id
 * @property string $sales_order_id
 * @property string $sales_order_item_id
 * @property string $currency
 * @property string $exchange_rate
 * @property int $item_total_cent
 * @property int $discount_total_cent
 * @property int $subtotal_cent subtotal = item_total - discount_total
 * @property int $shipping_total_cent
 * @property int $tax_total_cent
 * @property int $tax_included
 * @property int $grand_total_cent grand_total = subtotal + shipping_total + tax_total(if tax_included = false)
 *
 * @property float $item_total
 * @property float $discount_total
 * @property float $subtotal
 * @property float $shipping_total
 * @property float $tax_total
 * @property float $grand_total
 */
class SalesOrderAmount extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%sales_order_amount}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['sales_order_id', 'currency', 'item_total_cent', 'subtotal_cent', 'grand_total_cent'], 'required'],
            [['sales_order_id', 'sales_order_item_id',
                'item_total_cent', 'discount_total_cent', 'subtotal_cent',
                'shipping_total_cent', 'tax_total_cent', 'tax_included', 'grand_total_cent'], 'integer'],
            [['exchange_rate'], 'number'],
            [['currency'], 'string', 'max' => 3],
            [['item_total', 'discount_total', 'subtotal',
                'shipping_total', 'tax_total', 'grand_total'], 'integer'],
        ];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            $this->traceableBehaviors(),
            [
                'money' => [
                    'class' => MoneyAliasBehavior::class,
                    'aliasProperties' => [
                        'item_total' => 'item_total_cent',
                        'discount_total' => 'discount_total_cent',
                        'subtotal' => 'subtotal_cent',
                        'shipping_total' => 'shipping_total_cent',
                        'tax_total' => 'tax_total_cent',
                        'grand_total' => 'grand_total_cent',
                    ],
                ]
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'sales_order_amount_id' => Yii::t('sales/order', 'Sales Order Amount ID'),
            'sales_order_id' => Yii::t('sales/order', 'Sales Order ID'),
            'sales_order_item_id' => Yii::t('sales/order', 'Sales Order Item ID'),
            'currency' => Yii::t('sales/order', 'Currency'),
            'exchange_rate' => Yii::t('sales/order', 'Exchange Rate'),
            'item_total_cent' => Yii::t('sales/order', 'Item Total Cent'),
            'discount_total_cent' => Yii::t('sales/order', 'Discount Total Cent'),
            'subtotal_cent' => Yii::t('sales/order', 'Subtotal Cent'),
            'shipping_total_cent' => Yii::t('sales/order', 'Shipping Total Cent'),
            'tax_total_cent' => Yii::t('sales/order', 'Tax Total Cent'),
            'tax_included' => Yii::t('sales/order', 'Tax Included'),
            'grand_total_cent' => Yii::t('sales/order', 'Grand Total Cent'),
        ];
    }

    /**
     * @return SalesOrderAmountQuery
     * @inheritdoc
     */
    public static function find(): SalesOrderAmountQuery
    {
        return new SalesOrderAmountQuery(static::class);
    }
}

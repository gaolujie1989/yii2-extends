<?php

namespace lujie\sales\order\center\models;

use lujie\alias\behaviors\MoneyAliasBehavior;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\sales\order\center\Module;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%sales_order_item}}".
 *
 * @property string $sales_order_item_id
 * @property string $sales_order_id
 * @property string $external_order_item_id
 * @property string $item_id
 * @property string $item_no
 * @property string $external_item_id
 * @property string $external_item_no
 * @property string $order_item_name
 * @property int $price_cent
 * @property string $currency
 * @property int $qty
 * @property array $discounts
 *
 * @property float $price
 *
 * @property SalesOrder $order
 * @property SalesOrderAmount $itemAmount
 * @property SalesOrderAmount $systemCurrencyItemAmount
 */
class SalesOrderItem extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%sales_order_item}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['sales_order_id', 'item_id', 'item_no', 'external_item_no', 'price_cent', 'currency', 'qty'], 'required'],
            [['sales_order_id', 'external_order_item_id', 'item_id', 'external_item_id', 'price_cent', 'qty'], 'integer'],
            [['discounts'], 'safe'],
            [['item_no', 'external_item_no'], 'string', 'max' => 50],
            [['order_item_name'], 'string', 'max' => 500],
            [['currency'], 'string', 'max' => 3],
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
                        'price' => 'price_cent',
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
            'sales_order_item_id' => Yii::t('sales/order', 'Sales Order Item ID'),
            'sales_order_id' => Yii::t('sales/order', 'Sales Order ID'),
            'external_order_item_id' => Yii::t('sales/order', 'External Order Item ID'),
            'item_id' => Yii::t('sales/order', 'Item ID'),
            'item_no' => Yii::t('sales/order', 'Item No'),
            'external_item_id' => Yii::t('sales/order', 'External Item ID'),
            'external_item_no' => Yii::t('sales/order', 'External Item No'),
            'order_item_name' => Yii::t('sales/order', 'Order Item Name'),
            'price_cent' => Yii::t('sales/order', 'Price Cent'),
            'currency' => Yii::t('sales/order', 'Currency'),
            'qty' => Yii::t('sales/order', 'Qty'),
            'discounts' => Yii::t('sales/order', 'Discounts'),
        ];
    }

    /**
     * @return SalesOrderItemQuery
     * @inheritdoc
     */
    public static function find(): SalesOrderItemQuery
    {
        return new SalesOrderItemQuery(static::class);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getOrder(): ActiveQuery
    {
        return $this->hasOne(SalesOrder::class, ['sales_order_id' => 'sales_order_id']);
    }

    /**
     * @return SalesOrderAmountQuery|ActiveQuery
     * @inheritdoc
     */
    public function getItemAmount(): SalesOrderAmountQuery
    {
        return $this->hasOne(SalesOrderAmount::class, [
            'sales_order_id' => 'sales_order_id',
            'sales_order_item_id' => 'sales_order_item_id',
            'currency' => 'currency',
        ]);
    }

    /**
     * @return SalesOrderAmountQuery
     * @inheritdoc
     */
    public function getSystemCurrencyOrderItemAmount(): SalesOrderAmountQuery
    {
        /** @var SalesOrderAmountQuery $query */
        $query = $this->hasOne(SalesOrderAmount::class, [
            'sales_order_id' => 'sales_order_id',
            'sales_order_item_id' => 'sales_order_item_id',
        ]);
        return $query->currency(Module::getSystemCurrency());
    }
}

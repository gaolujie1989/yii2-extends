<?php

namespace lujie\fulfillment\models;

use Yii;

/**
 * This is the model class for table "{{%fulfillment_daily_stock}}".
 *
 * @property int $fulfillment_daily_stock_id
 * @property int $fulfillment_account_id
 * @property int $item_id
 * @property int $warehouse_id
 * @property string $external_item_key
 * @property string $external_warehouse_key
 * @property int $stock_qty
 * @property int $reserved_qty
 * @property string $stock_date
 */
class FulfillmentDailyStock extends \lujie\fulfillment\base\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%fulfillment_daily_stock}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['fulfillment_account_id', 'item_id', 'warehouse_id', 'stock_qty', 'reserved_qty'], 'default', 'value' => 0],
            [['external_item_key', 'external_warehouse_key'], 'default', 'value' => ''],
            [['fulfillment_account_id', 'item_id', 'warehouse_id', 'stock_qty', 'reserved_qty'], 'integer'],
            [['stock_date'], 'required'],
            [['stock_date'], 'safe'],
            [['external_item_key', 'external_warehouse_key'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'fulfillment_daily_stock_id' => Yii::t('lujie/fulfillment', 'Fulfillment Daily Stock ID'),
            'fulfillment_account_id' => Yii::t('lujie/fulfillment', 'Fulfillment Account ID'),
            'item_id' => Yii::t('lujie/fulfillment', 'Item ID'),
            'warehouse_id' => Yii::t('lujie/fulfillment', 'Warehouse ID'),
            'external_item_key' => Yii::t('lujie/fulfillment', 'External Item Key'),
            'external_warehouse_key' => Yii::t('lujie/fulfillment', 'External Warehouse Key'),
            'stock_qty' => Yii::t('lujie/fulfillment', 'Stock Qty'),
            'reserved_qty' => Yii::t('lujie/fulfillment', 'Reserved Qty'),
            'stock_date' => Yii::t('lujie/fulfillment', 'Stock Date'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return FulfillmentDailyStockQuery the active query used by this AR class.
     */
    public static function find(): FulfillmentDailyStockQuery
    {
        return new FulfillmentDailyStockQuery(static::class);
    }
}

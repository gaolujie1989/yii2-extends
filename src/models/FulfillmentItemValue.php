<?php

namespace lujie\fulfillment\models;

use Yii;

/**
 * This is the model class for table "{{%fulfillment_item_value}}".
 *
 * @property int $fulfillment_item_value_id
 * @property int $fulfillment_daily_stock_movement_id
 * @property int $item_id
 * @property int $warehouse_id
 * @property string $external_item_key
 * @property string $external_warehouse_key
 * @property int $old_item_value_cent
 * @property int $old_item_qty
 * @property int $inbound_item_value_cent
 * @property int $inbound_item_qty
 * @property int $new_item_value_cent
 * @property int $new_item_qty
 * @property string $currency
 * @property string $value_date
 * @property int $latest
 */
class FulfillmentItemValue extends \lujie\fulfillment\base\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%fulfillment_item_value}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['fulfillment_daily_stock_movement_id', 'item_id', 'warehouse_id',
                'old_item_value_cent', 'old_item_qty', 'inbound_item_value_cent', 'inbound_item_qty',
                'new_item_value_cent', 'new_item_qty', 'latest'], 'default', 'value' => 0],
            [['external_item_key', 'external_warehouse_key', 'currency'], 'default', 'value' => ''],
            [['fulfillment_daily_stock_movement_id', 'item_id', 'warehouse_id',
                'old_item_value_cent', 'old_item_qty', 'inbound_item_value_cent', 'inbound_item_qty',
                'new_item_value_cent', 'new_item_qty', 'latest'], 'integer'],
            [['value_date'], 'required'],
            [['value_date'], 'safe'],
            [['external_item_key', 'external_warehouse_key'], 'string', 'max' => 50],
            [['currency'], 'string', 'max' => 3],
            [['fulfillment_daily_stock_movement_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'fulfillment_item_value_id' => Yii::t('lujie/fulfillment', 'Fulfillment Item Value ID'),
            'fulfillment_daily_stock_movement_id' => Yii::t('lujie/fulfillment', 'Fulfillment Daily Stock Movement ID'),
            'item_id' => Yii::t('lujie/fulfillment', 'Item ID'),
            'warehouse_id' => Yii::t('lujie/fulfillment', 'Warehouse ID'),
            'external_item_key' => Yii::t('lujie/fulfillment', 'External Item Key'),
            'external_warehouse_key' => Yii::t('lujie/fulfillment', 'External Warehouse Key'),
            'old_item_value_cent' => Yii::t('lujie/fulfillment', 'Old Item Value Cent'),
            'old_item_qty' => Yii::t('lujie/fulfillment', 'Old Item Qty'),
            'inbound_item_value_cent' => Yii::t('lujie/fulfillment', 'Inbound Item Value Cent'),
            'inbound_item_qty' => Yii::t('lujie/fulfillment', 'Inbound Item Qty'),
            'new_item_value_cent' => Yii::t('lujie/fulfillment', 'New Item Value Cent'),
            'new_item_qty' => Yii::t('lujie/fulfillment', 'New Item Qty'),
            'currency' => Yii::t('lujie/fulfillment', 'Currency'),
            'value_date' => Yii::t('lujie/fulfillment', 'Value Date'),
            'latest' => Yii::t('lujie/fulfillment', 'Latest'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return FulfillmentItemValueQuery the active query used by this AR class.
     */
    public static function find(): FulfillmentItemValueQuery
    {
        return new FulfillmentItemValueQuery(static::class);
    }
}

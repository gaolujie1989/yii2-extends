<?php

namespace lujie\fulfillment\models;

use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;

/**
 * This is the model class for table "{{%fulfillment_item_value}}".
 *
 * @property int $fulfillment_item_value_id
 * @property int $fulfillment_daily_stock_movement_id
 * @property int $item_id
 * @property int $warehouse_id
 * @property int $old_item_value_cent
 * @property int $old_item_qty
 * @property int $inbound_item_value_cent
 * @property int $inbound_item_qty
 * @property int $new_item_value_cent
 * @property int $new_item_qty
 * @property string $currency
 * @property string $value_date
 */
class FulfillmentItemValue extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

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
            [['fulfillment_daily_stock_movement_id', 'item_id', 'warehouse_id', 'old_item_value_cent', 'old_item_qty', 'inbound_item_value_cent', 'inbound_item_qty', 'new_item_value_cent', 'new_item_qty'], 'default', 'value' => 0],
            [['currency'], 'default', 'value' => ''],
            [['fulfillment_daily_stock_movement_id', 'item_id', 'warehouse_id', 'old_item_value_cent', 'old_item_qty', 'inbound_item_value_cent', 'inbound_item_qty', 'new_item_value_cent', 'new_item_qty'], 'integer'],
            [['value_date'], 'required'],
            [['value_date'], 'safe'],
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
            'old_item_value_cent' => Yii::t('lujie/fulfillment', 'Old Item Value Cent'),
            'old_item_qty' => Yii::t('lujie/fulfillment', 'Old Item Qty'),
            'inbound_item_value_cent' => Yii::t('lujie/fulfillment', 'Inbound Item Value Cent'),
            'inbound_item_qty' => Yii::t('lujie/fulfillment', 'Inbound Item Qty'),
            'new_item_value_cent' => Yii::t('lujie/fulfillment', 'New Item Value Cent'),
            'new_item_qty' => Yii::t('lujie/fulfillment', 'New Item Qty'),
            'currency' => Yii::t('lujie/fulfillment', 'Currency'),
            'value_date' => Yii::t('lujie/fulfillment', 'Value Date'),
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

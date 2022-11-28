<?php

namespace lujie\fulfillment\models;

use Yii;

/**
 * This is the model class for table "{{%fulfillment_daily_stock_movement}}".
 *
 * @property int $fulfillment_daily_stock_movement_id
 * @property int $fulfillment_account_id
 * @property string $external_item_key
 * @property string $external_warehouse_key
 * @property string $movement_type
 * @property int $movement_qty
 * @property int $movement_count
 * @property string $movement_date
 */
class FulfillmentDailyStockMovement extends \lujie\fulfillment\base\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%fulfillment_daily_stock_movement}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['fulfillment_account_id', 'movement_qty', 'movement_count'], 'default', 'value' => 0],
            [['external_item_key', 'external_warehouse_key', 'movement_type'], 'default', 'value' => ''],
            [['fulfillment_account_id', 'movement_qty', 'movement_count'], 'integer'],
            [['movement_date'], 'required'],
            [['movement_date'], 'safe'],
            [['external_item_key', 'external_warehouse_key'], 'string', 'max' => 50],
            [['movement_type'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'fulfillment_daily_stock_movement_id' => Yii::t('lujie/fulfillment', 'Fulfillment Daily Stock Movement ID'),
            'fulfillment_account_id' => Yii::t('lujie/fulfillment', 'Fulfillment Account ID'),
            'external_item_key' => Yii::t('lujie/fulfillment', 'External Item Key'),
            'external_warehouse_key' => Yii::t('lujie/fulfillment', 'External Warehouse Key'),
            'movement_type' => Yii::t('lujie/fulfillment', 'Movement Type'),
            'movement_qty' => Yii::t('lujie/fulfillment', 'Movement Qty'),
            'movement_count' => Yii::t('lujie/fulfillment', 'Movement Count'),
            'movement_date' => Yii::t('lujie/fulfillment', 'Movement Date'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return FulfillmentDailyStockMovementQuery the active query used by this AR class.
     */
    public static function find(): FulfillmentDailyStockMovementQuery
    {
        return new FulfillmentDailyStockMovementQuery(static::class);
    }
}

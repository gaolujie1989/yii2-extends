<?php

namespace lujie\fulfillment\models;

use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;

/**
 * This is the model class for table "{{%fulfillment_daily_stock_movement}}".
 *
 * @property int $fulfillment_daily_stock_movement_id
 * @property int $fulfillment_account_id
 * @property int $item_id
 * @property int $warehouse_id
 * @property string $external_item_key
 * @property string $external_warehouse_key
 * @property int $movement_qty
 * @property int $movement_count
 * @property string $movement_date
 * @property int $balance_qty
 * @property string $reason
 */
class FulfillmentDailyStockMovement extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

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
            [['fulfillment_account_id', 'item_id', 'warehouse_id', 'movement_qty', 'movement_count', 'balance_qty'], 'default', 'value' => 0],
            [['external_item_key', 'external_warehouse_key', 'reason'], 'default', 'value' => ''],
            [['fulfillment_account_id', 'item_id', 'warehouse_id', 'movement_qty', 'movement_count', 'balance_qty'], 'integer'],
            [['movement_date'], 'required'],
            [['movement_date'], 'safe'],
            [['external_item_key', 'external_warehouse_key'], 'string', 'max' => 50],
            [['reason'], 'string', 'max' => 20],
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
            'item_id' => Yii::t('lujie/fulfillment', 'Item ID'),
            'warehouse_id' => Yii::t('lujie/fulfillment', 'Warehouse ID'),
            'external_item_key' => Yii::t('lujie/fulfillment', 'External Item Key'),
            'external_warehouse_key' => Yii::t('lujie/fulfillment', 'External Warehouse Key'),
            'movement_qty' => Yii::t('lujie/fulfillment', 'Movement Qty'),
            'movement_count' => Yii::t('lujie/fulfillment', 'Movement Count'),
            'movement_date' => Yii::t('lujie/fulfillment', 'Movement Date'),
            'balance_qty' => Yii::t('lujie/fulfillment', 'Balance Qty'),
            'reason' => Yii::t('lujie/fulfillment', 'Reason'),
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

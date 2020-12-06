<?php

namespace lujie\fulfillment\models;

use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;

/**
 * This is the model class for table "{{%fulfillment_warehouse_stock_movement}}".
 *
 * @property int $fulfillment_warehouse_stock_movement_id
 * @property int $fulfillment_account_id
 * @property int $item_id
 * @property int $warehouse_id
 * @property string $external_item_key
 * @property string $external_warehouse_key
 * @property string $external_movement_key
 * @property int $moved_qty
 * @property int $balance_qty
 * @property string $reason
 * @property string $related_type
 * @property string $related_key
 * @property array|null $movement_additional
 * @property int $external_created_at
 * @property array|null $additional
 */
class FulfillmentWarehouseStockMovement extends \lujie\fulfillment\base\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%fulfillment_warehouse_stock_movement}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['fulfillment_account_id', 'item_id', 'warehouse_id', 'moved_qty', 'balance_qty', 'external_created_at'], 'default', 'value' => 0],
            [['external_item_key', 'external_warehouse_key', 'external_movement_key', 'reason', 'related_type', 'related_key'], 'default', 'value' => ''],
            [['movement_additional', 'additional'], 'default', 'value' => []],
            [['fulfillment_account_id', 'item_id', 'warehouse_id', 'moved_qty', 'balance_qty', 'external_created_at'], 'integer'],
            [['movement_additional', 'additional'], 'safe'],
            [['external_item_key', 'external_warehouse_key', 'external_movement_key', 'related_key'], 'string', 'max' => 50],
            [['reason', 'related_type'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'fulfillment_warehouse_stock_movement_id' => Yii::t('lujie/fulfillment', 'Fulfillment Warehouse Stock Movement ID'),
            'fulfillment_account_id' => Yii::t('lujie/fulfillment', 'Fulfillment Account ID'),
            'item_id' => Yii::t('lujie/fulfillment', 'Item ID'),
            'warehouse_id' => Yii::t('lujie/fulfillment', 'Warehouse ID'),
            'external_item_key' => Yii::t('lujie/fulfillment', 'External Item Key'),
            'external_warehouse_key' => Yii::t('lujie/fulfillment', 'External Warehouse Key'),
            'external_movement_key' => Yii::t('lujie/fulfillment', 'External Movement Key'),
            'moved_qty' => Yii::t('lujie/fulfillment', 'Moved Qty'),
            'balance_qty' => Yii::t('lujie/fulfillment', 'Balance Qty'),
            'reason' => Yii::t('lujie/fulfillment', 'Reason'),
            'related_type' => Yii::t('lujie/fulfillment', 'Related Type'),
            'related_key' => Yii::t('lujie/fulfillment', 'Related Key'),
            'movement_additional' => Yii::t('lujie/fulfillment', 'Movement Additional'),
            'external_created_at' => Yii::t('lujie/fulfillment', 'External Created At'),
            'additional' => Yii::t('lujie/fulfillment', 'Additional'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return FulfillmentWarehouseStockMovementQuery the active query used by this AR class.
     */
    public static function find(): FulfillmentWarehouseStockMovementQuery
    {
        return new FulfillmentWarehouseStockMovementQuery(static::class);
    }
}

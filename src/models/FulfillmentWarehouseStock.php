<?php

namespace lujie\fulfillment\models;

use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;

/**
 * This is the model class for table "{{%fulfillment_warehouse_stock}}".
 *
 * @property int $fulfillment_warehouse_stock_id
 * @property int $fulfillment_account_id
 * @property int $fulfillment_item_id
 * @property int $fulfillment_warehouse_id
 * @property int $item_id
 * @property int $warehouse_id
 * @property int $external_item_key
 * @property int $external_warehouse_key
 * @property int $stock_qty
 * @property int $reserved_qty
 * @property array|null $stock_additional
 * @property int $external_updated_at
 * @property int $stock_pulled_at
 * @property array|null $additional
 */
class FulfillmentWarehouseStock extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%fulfillment_warehouse_stock}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['fulfillment_account_id', 'fulfillment_item_id', 'fulfillment_warehouse_id', 'item_id', 'warehouse_id', 'external_item_key', 'external_warehouse_key', 'stock_qty', 'reserved_qty', 'external_updated_at', 'stock_pulled_at'], 'default', 'value' => 0],
            [['stock_additional', 'additional'], 'default', 'value' => []],
            [['fulfillment_account_id', 'fulfillment_item_id', 'fulfillment_warehouse_id', 'item_id', 'warehouse_id', 'external_item_key', 'external_warehouse_key', 'stock_qty', 'reserved_qty', 'external_updated_at', 'stock_pulled_at'], 'integer'],
            [['stock_additional', 'additional'], 'safe'],
            [['item_id', 'warehouse_id', 'fulfillment_account_id'], 'unique', 'targetAttribute' => ['item_id', 'warehouse_id', 'fulfillment_account_id']],
            [['external_item_key', 'external_warehouse_key', 'fulfillment_account_id'], 'unique', 'targetAttribute' => ['external_item_key', 'external_warehouse_key', 'fulfillment_account_id']],
            [['fulfillment_item_id', 'fulfillment_warehouse_id', 'fulfillment_account_id'], 'unique', 'targetAttribute' => ['fulfillment_item_id', 'fulfillment_warehouse_id', 'fulfillment_account_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'fulfillment_warehouse_stock_id' => Yii::t('lujie/common', 'Fulfillment Warehouse Stock ID'),
            'fulfillment_account_id' => Yii::t('lujie/common', 'Fulfillment Account ID'),
            'fulfillment_item_id' => Yii::t('lujie/common', 'Fulfillment Item ID'),
            'fulfillment_warehouse_id' => Yii::t('lujie/common', 'Fulfillment Warehouse ID'),
            'item_id' => Yii::t('lujie/common', 'Item ID'),
            'warehouse_id' => Yii::t('lujie/common', 'Warehouse ID'),
            'external_item_key' => Yii::t('lujie/common', 'External Item Key'),
            'external_warehouse_key' => Yii::t('lujie/common', 'External Warehouse Key'),
            'stock_qty' => Yii::t('lujie/common', 'Stock Qty'),
            'reserved_qty' => Yii::t('lujie/common', 'Reserved Qty'),
            'stock_additional' => Yii::t('lujie/common', 'Stock Additional'),
            'external_updated_at' => Yii::t('lujie/common', 'External Updated At'),
            'stock_pulled_at' => Yii::t('lujie/common', 'Stock Pulled At'),
            'additional' => Yii::t('lujie/common', 'Additional'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return FulfillmentWarehouseStockQuery the active query used by this AR class.
     */
    public static function find(): FulfillmentWarehouseStockQuery
    {
        return new FulfillmentWarehouseStockQuery(static::class);
    }
}

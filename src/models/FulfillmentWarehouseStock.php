<?php

namespace lujie\fulfillment\models;

use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;

/**
 * This is the model class for table "{{%fulfillment_warehouse_stock}}".
 *
 * @property string $fulfillment_warehouse_stock_id
 * @property string $fulfillment_account_id
 * @property string $warehouse_id
 * @property string $external_warehouse_id
 * @property string $item_id
 * @property string $external_item_id
 * @property int $stock_qty
 * @property int $reserved_qty
 * @property array $additional
 * @property int $external_updated_at
 * @property int $stock_pulled_at
 */
class FulfillmentWarehouseStock extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait;

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
            [['fulfillment_account_id', 'warehouse_id', 'external_warehouse_id', 'item_id', 'external_item_id',
                'stock_qty', 'reserved_qty', 'external_updated_at', 'stock_pulled_at'], 'integer'],
            [['fulfillment_account_id', 'warehouse_id', 'item_id'], 'unique',
                'targetAttribute' => ['fulfillment_account_id', 'warehouse_id', 'item_id']],
            [['fulfillment_account_id', 'external_warehouse_id', 'external_item_id'], 'unique',
                'targetAttribute' => ['fulfillment_account_id', 'external_warehouse_id', 'external_item_id']],
            [['additional'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'fulfillment_warehouse_stock_id' => Yii::t('lujie/fulfillment', 'Fulfillment Warehouse Stock ID'),
            'fulfillment_account_id' => Yii::t('lujie/fulfillment', 'Fulfillment Account ID'),
            'warehouse_id' => Yii::t('lujie/fulfillment', 'Warehouse ID'),
            'external_warehouse_id' => Yii::t('lujie/fulfillment', 'External Warehouse ID'),
            'item_id' => Yii::t('lujie/fulfillment', 'Item ID'),
            'external_item_id' => Yii::t('lujie/fulfillment', 'External Item ID'),
            'stock_qty' => Yii::t('lujie/fulfillment', 'Stock Qty'),
            'reserved_qty' => Yii::t('lujie/fulfillment', 'Reserved Qty'),
            'additional' => Yii::t('lujie/fulfillment', 'Additional'),
            'external_updated_at' => Yii::t('lujie/fulfillment', 'External Updated At'),
            'stock_pulled_at' => Yii::t('lujie/fulfillment', 'Stock Pulled At'),
        ];
    }

    /**
     * @return FulfillmentWarehouseStockQuery
     * @inheritdoc
     */
    public static function find(): FulfillmentWarehouseStockQuery
    {
        return new FulfillmentWarehouseStockQuery(static::class);
    }
}

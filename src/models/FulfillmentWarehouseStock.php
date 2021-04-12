<?php

namespace lujie\fulfillment\models;

use Yii;
use yii\db\ActiveRecord as DbActiveRecord;
use yii\db\BaseActiveRecord;
use yii\mongodb\ActiveRecord as MongodbActiveRecord;
use yii\redis\ActiveRecord as RedisActiveRecord;

/**
 * This is the model class for table "{{%fulfillment_warehouse_stock}}".
 *
 * @property int $fulfillment_warehouse_stock_id
 * @property int $fulfillment_account_id
 * @property int $item_id
 * @property int $warehouse_id
 * @property string $external_item_key
 * @property string $external_warehouse_key
 * @property int $stock_qty
 * @property int $reserved_qty
 * @property array|null $stock_additional
 * @property int $external_updated_at
 * @property int $stock_pulled_at
 * @property array|null $additional
 */
class FulfillmentWarehouseStock extends \lujie\fulfillment\base\db\ActiveRecord
{

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
            [['fulfillment_account_id', 'item_id', 'warehouse_id', 'stock_qty', 'reserved_qty',
                'external_updated_at', 'stock_pulled_at'], 'default', 'value' => 0],
            [['external_item_key', 'external_warehouse_key'], 'default', 'value' => ''],
            [['stock_additional', 'additional'], 'default', 'value' => []],
            [['fulfillment_account_id', 'item_id', 'warehouse_id', 'stock_qty', 'reserved_qty',
                'external_updated_at', 'stock_pulled_at'], 'integer'],
            [['stock_additional', 'additional'], 'safe'],
            [['external_item_key', 'external_warehouse_key'], 'string', 'max' => 50],
            [['item_id', 'warehouse_id', 'fulfillment_account_id'], 'unique',
                'targetAttribute' => ['item_id', 'warehouse_id', 'fulfillment_account_id']],
            [['external_item_key', 'external_warehouse_key', 'fulfillment_account_id'], 'unique',
                'targetAttribute' => ['external_item_key', 'external_warehouse_key', 'fulfillment_account_id']],
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
            'item_id' => Yii::t('lujie/fulfillment', 'Item ID'),
            'warehouse_id' => Yii::t('lujie/fulfillment', 'Warehouse ID'),
            'external_item_key' => Yii::t('lujie/fulfillment', 'External Item Key'),
            'external_warehouse_key' => Yii::t('lujie/fulfillment', 'External Warehouse Key'),
            'stock_qty' => Yii::t('lujie/fulfillment', 'Stock Qty'),
            'reserved_qty' => Yii::t('lujie/fulfillment', 'Reserved Qty'),
            'stock_additional' => Yii::t('lujie/fulfillment', 'Stock Additional'),
            'external_updated_at' => Yii::t('lujie/fulfillment', 'External Updated At'),
            'stock_pulled_at' => Yii::t('lujie/fulfillment', 'Stock Pulled At'),
            'additional' => Yii::t('lujie/fulfillment', 'Additional'),
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

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        return array_merge(parent::fields(), [
            'stock_qty' => 'stock_qty'  //for compatible
        ]);
    }

    /**
     * @param BaseActiveRecord $record
     * @return string
     * @inheritdoc
     */
    protected function getTableName(BaseActiveRecord $record): string
    {
        if ($record instanceof DbActiveRecord) {
            return $record::tableName();
        }
        if ($record instanceof MongodbActiveRecord) {
            return $record::collectionName();
        }
        if ($record instanceof RedisActiveRecord) {
            return $record::keyPrefix();
        }
        return '';
    }
}

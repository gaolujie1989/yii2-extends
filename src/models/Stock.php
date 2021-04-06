<?php

namespace lujie\stock\models;

use Yii;

/**
 * This is the model class for table "{{%stock}}".
 *
 * @property int $stock_id
 * @property int $item_id
 * @property int $location_id
 * @property int $stock_qty
 * @property int $stock_item_value
 */
class Stock extends \lujie\stock\base\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%stock}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['item_id', 'location_id'], 'required'],
            [['item_id', 'location_id', 'stock_qty'], 'integer'],
            [['stock_item_value'], 'number'],
            [['item_id', 'location_id'], 'unique', 'targetAttribute' => ['item_id', 'location_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'stock_id' => Yii::t('lujie/stock', 'Stock ID'),
            'item_id' => Yii::t('lujie/stock', 'Item ID'),
            'location_id' => Yii::t('lujie/stock', 'Location ID'),
            'stock_qty' => Yii::t('lujie/stock', 'Stock Qty'),
            'stock_item_value' => Yii::t('lujie/stock', 'Stock Item Value'),
        ];
    }

    /**
     * @return StockQuery
     * @inheritdoc
     */
    public static function find(): StockQuery
    {
        return new StockQuery(static::class);
    }
}

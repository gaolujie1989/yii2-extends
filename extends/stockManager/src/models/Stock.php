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
 * @property int $item_value_cent
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
            [['stock_qty', 'item_value_cent'], 'default', 'value' => 0],
            [['item_id', 'location_id'], 'required'],
            [['item_id', 'location_id', 'stock_qty', 'item_value_cent'], 'integer'],
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
            'item_value_cent' => Yii::t('lujie/stock', 'Item Value Cent'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return StockQuery the active query used by this AR class.
     */
    public static function find(): StockQuery
    {
        return new StockQuery(static::class);
    }
}

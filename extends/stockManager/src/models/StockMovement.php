<?php

namespace lujie\stock\models;

use Yii;

/**
 * This is the model class for table "{{%stock_movement}}".
 *
 * @property int $stock_movement_id
 * @property int $item_id
 * @property int $location_id
 * @property int $moved_qty
 * @property int $stock_qty
 * @property string $reason
 * @property int $item_value_cent
 */
class StockMovement extends \lujie\stock\base\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%stock_movement}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['moved_qty', 'stock_qty', 'item_value_cent'], 'default', 'value' => 0],
            [['reason'], 'default', 'value' => ''],
            [['item_id', 'location_id'], 'required'],
            [['item_id', 'location_id', 'moved_qty', 'stock_qty', 'item_value_cent'], 'integer'],
            [['reason'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'stock_movement_id' => Yii::t('lujie/stock', 'Stock Movement ID'),
            'item_id' => Yii::t('lujie/stock', 'Item ID'),
            'location_id' => Yii::t('lujie/stock', 'Location ID'),
            'moved_qty' => Yii::t('lujie/stock', 'Moved Qty'),
            'stock_qty' => Yii::t('lujie/stock', 'Stock Qty'),
            'reason' => Yii::t('lujie/stock', 'Reason'),
            'item_value_cent' => Yii::t('lujie/stock', 'Item Value Cent'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return StockMovementQuery the active query used by this AR class.
     */
    public static function find(): StockMovementQuery
    {
        return new StockMovementQuery(static::class);
    }
}

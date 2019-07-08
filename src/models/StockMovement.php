<?php

namespace lujie\stock\models;

use Yii;

/**
 * This is the model class for table "{{%stock_movement}}".
 *
 * @property string $stock_movement_id
 * @property string $item_id
 * @property string $location_id
 * @property int $move_qty
 * @property int $move_item_value
 * @property string $reason
 */
class StockMovement extends \yii\db\ActiveRecord
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
            [['item_id', 'location_id'], 'required'],
            [['item_id', 'location_id', 'move_qty'], 'integer'],
            [['move_item_value'], 'number'],
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
            'move_qty' => Yii::t('lujie/stock', 'Move Qty'),
            'move_item_value' => Yii::t('lujie/stock', 'Move Item Value'),
            'reason' => Yii::t('lujie/stock', 'Reason'),
        ];
    }

    /**
     * @return StockMovementQuery
     * @inheritdoc
     */
    public static function find(): StockMovementQuery
    {
        return new StockMovementQuery(static::class);
    }
}

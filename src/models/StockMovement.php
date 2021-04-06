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
 * @property int $moved_item_value
 * @property string $reason
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
            [['item_id', 'location_id'], 'required'],
            [['item_id', 'location_id', 'moved_qty'], 'integer'],
            [['moved_item_value'], 'number'],
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
            'moved_qty' => Yii::t('lujie/stock', 'Move Qty'),
            'moved_item_value' => Yii::t('lujie/stock', 'Move Item Value'),
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

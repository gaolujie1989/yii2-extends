<?php

namespace lujie\ar\relation\behaviors\tests\unit\fixtures\models;

/**
 * This is the model class for table "test_order_item".
 *
 * @property string $test_order_item_id
 * @property string $test_order_id
 * @property string $item_no
 * @property int $ordered_qty
 */
class TestOrderItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'test_order_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['test_order_id', 'ordered_qty'], 'integer'],
            [['item_no'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'test_order_item_id' => 'Test Order Item ID',
            'test_order_id' => 'Test Order ID',
            'item_no' => 'Item No',
            'ordered_qty' => 'Ordered Qty',
        ];
    }
}

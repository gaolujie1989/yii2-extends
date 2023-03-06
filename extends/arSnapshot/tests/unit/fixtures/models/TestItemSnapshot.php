<?php

namespace lujie\ar\snapshot\behaviors\tests\unit\fixtures\models;

/**
 * This is the model class for table "{{%test_item_snapshot}}".
 *
 * @property string $test_item_snapshot_id
 * @property string $test_item_id
 * @property string $item_no
 * @property string $item_name
 * @property int $status
 * @property int $updated_at
 */
class TestItemSnapshot extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%test_item_snapshot}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['test_item_id'], 'required'],
            [['test_item_id', 'status'], 'integer'],
            [['item_no', 'item_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'test_item_snapshot_id' => 'Test Item Snapshot ID',
            'test_item_id' => 'Test Item ID',
            'item_no' => 'Item No',
            'item_name' => 'Item Name',
            'status' => 'Status',
        ];
    }
}

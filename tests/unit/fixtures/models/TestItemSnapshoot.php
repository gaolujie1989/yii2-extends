<?php

namespace lujie\ar\snapshoot\behaviors\tests\unit\fixtures\models;

use Yii;

/**
 * This is the model class for table "{{%test_item_snapshoot}}".
 *
 * @property string $test_item_snapshoot_id
 * @property string $test_item_id
 * @property string $item_no
 * @property string $item_name
 * @property int $status
 * @property int $updated_at
 */
class TestItemSnapshoot extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%test_item_snapshoot}}';
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
            'test_item_snapshoot_id' => 'Test Item Snapshoot ID',
            'test_item_id' => 'Test Item ID',
            'item_no' => 'Item No',
            'item_name' => 'Item Name',
            'status' => 'Status',
        ];
    }
}

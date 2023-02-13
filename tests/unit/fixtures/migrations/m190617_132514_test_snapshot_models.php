<?php

use yii\db\Migration;

class m190617_132514_test_snapshot_models extends Migration
{
    public function safeUp()
    {
        $this->createTable('test_item', [
            'test_item_id' => $this->bigPrimaryKey(),
            'item_no' => $this->string()->notNull()->defaultValue(''),
            'item_name' => $this->string()->notNull()->defaultValue(''),
            'status' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
        ]);

        $this->createTable('test_item_snapshot', [
            'test_item_snapshot_id' => $this->bigPrimaryKey(),
            'test_item_id' => $this->bigInteger()->notNull(),
            'item_no' => $this->string()->notNull()->defaultValue(''),
            'item_name' => $this->string()->notNull()->defaultValue(''),
            'status' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
        ]);
        $this->addColumn('test_item', 'snapshot_id', $this->bigInteger()->notNull()->defaultValue(0)->after('test_item_id'));
    }
}

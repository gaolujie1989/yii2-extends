<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m201208_162734_fulfillment_item_value extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%fulfillment_item_value}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'fulfillment_item_value_id' => $this->bigPrimaryKey(),
            'fulfillment_daily_stock_movement_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'item_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'warehouse_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'external_item_key' => $this->string(50)->notNull()->defaultValue(''),
            'external_warehouse_key' => $this->string(50)->notNull()->defaultValue(''),

            'old_item_value_cent' => $this->integer()->notNull()->defaultValue(0),
            'old_item_qty' => $this->integer()->notNull()->defaultValue(0),
            'inbound_item_value_cent' => $this->integer()->notNull()->defaultValue(0),
            'inbound_item_qty' => $this->integer()->notNull()->defaultValue(0),
            'new_item_value_cent' => $this->integer()->notNull()->defaultValue(0),
            'new_item_qty' => $this->integer()->notNull()->defaultValue(0),
            'currency' =>  $this->char(3)->notNull()->defaultValue(''),
            'value_date' => $this->date()->notNull(),
        ]);

        $this->createIndex('uk_fulfillment_daily_stock_movement_id', $this->tableName, ['fulfillment_daily_stock_movement_id'], true);
        $this->createIndex('idx_item_warehouse_date', $this->tableName, ['item_id', 'warehouse_id', 'value_date']);
        $this->createIndex('idx_external_item_warehouse_date', $this->tableName, ['external_item_key', 'external_warehouse_key', 'value_date']);
    }
}

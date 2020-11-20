<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m201120_151239_fulfillment_daily_stock_movement extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%fulfillment_daily_stock_movement}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'fulfillment_daily_stock_movement_id' => $this->bigPrimaryKey(),
            'fulfillment_account_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'item_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'warehouse_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'external_item_key' => $this->string(50)->notNull()->defaultValue(''),
            'external_warehouse_key' => $this->string(50)->notNull()->defaultValue(''),

            'moved_qty' => $this->integer()->notNull()->defaultValue(0),
            'moved_count' => $this->integer()->notNull()->defaultValue(0),
            'moved_date' => $this->date()->notNull(),
            'balance_qty' => $this->integer()->notNull()->defaultValue(0),
            'reason' => $this->string(20)->notNull()->defaultValue(''),
        ]);

        $this->createIndex('idx_item_moved_date_warehouse_account', $this->tableName,
            ['item_id', 'moved_date', 'warehouse_id', 'fulfillment_account_id']);
        $this->createIndex('idx_external_item_moved_date_warehouse_account', $this->tableName,
            ['external_item_key', 'moved_date', 'external_warehouse_key', 'fulfillment_account_id']);
    }
}

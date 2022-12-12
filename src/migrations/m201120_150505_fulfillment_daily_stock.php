<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m201120_150505_fulfillment_daily_stock extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%fulfillment_daily_stock}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'fulfillment_daily_stock_id' => $this->bigPrimaryKey(),
            'fulfillment_account_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'external_item_key' => $this->string(50)->notNull()->defaultValue(''),
            'external_warehouse_key' => $this->string(50)->notNull()->defaultValue(''),

            'stock_qty' => $this->integer()->notNull()->defaultValue(0),
            'reserved_qty' => $this->integer()->notNull()->defaultValue(0),
            'stock_date' => $this->date()->notNull(),
        ]);

        $this->createIndex(
            'uk_external_item_stock_date_warehouse_account',
            $this->tableName,
            ['external_item_key', 'stock_date', 'external_warehouse_key', 'fulfillment_account_id'],
            true
        );
        $this->createIndex('idx_stock_date', $this->tableName, ['stock_date']);
    }
}

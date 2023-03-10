<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m201120_151239_fulfillment_daily_stock_movement extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $traceBy = false;

    public $tableName = '{{%fulfillment_daily_stock_movement}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'fulfillment_daily_stock_movement_id' => $this->bigPrimaryKey(),
            'fulfillment_account_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'external_item_key' => $this->string(50)->notNull()->defaultValue(''),
            'external_warehouse_key' => $this->string(50)->notNull()->defaultValue(''),

            'movement_type' => $this->string(20)->notNull()->defaultValue(''),
            'movement_qty' => $this->integer()->notNull()->defaultValue(0),
            'movement_count' => $this->integer()->notNull()->defaultValue(0),
            'movement_date' => $this->date()->notNull(),
        ]);

        $this->createIndex(
            'uk_external_item_movement_date_warehouse_account_type',
            $this->tableName,
            ['external_item_key', 'movement_date', 'external_warehouse_key', 'fulfillment_account_id', 'movement_type'],
            true
        );
        $this->createIndex('idx_movement_date', $this->tableName, ['movement_date']);
    }
}

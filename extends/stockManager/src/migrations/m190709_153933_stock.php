<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m190709_153933_stock extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%stock}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'stock_id' => $this->bigPrimaryKey(),
            'item_id' => $this->bigInteger()->notNull(),
            'location_id' => $this->bigInteger()->notNull(),
            'stock_qty' => $this->integer()->notNull()->defaultValue(0),
            'item_value_cent' => $this->integer()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('uk_item_id_location_id', $this->tableName, ['item_id', 'location_id'], true);
        $this->createIndex('idx_location_id', $this->tableName, ['location_id']);
    }
}

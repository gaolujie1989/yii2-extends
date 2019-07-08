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
            'stock_id' => $this->bigPrimaryKey()->unsigned(),
            'item_id' => $this->bigInteger()->unsigned()->notNull(),
            'location_id' => $this->bigInteger()->unsigned()->notNull(),
            'stock_qty' => $this->integer()->notNull()->defaultValue(0),
            'stock_item_value' => $this->decimal(10, 2)->notNull()->defaultValue(0),
        ]);

        $this->createIndex('idx_item_id_location_id', $this->tableName, ['item_id', 'location_id']);
        $this->createIndex('idx_location_id', $this->tableName, ['location_id']);
    }
}

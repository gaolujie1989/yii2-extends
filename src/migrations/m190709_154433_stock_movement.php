<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m190709_154433_stock_movement extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%stock_movement}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'stock_movement_id' => $this->bigPrimaryKey(),
            'item_id' => $this->bigInteger()->notNull(),
            'location_id' => $this->bigInteger()->notNull(),
            'moved_qty' => $this->integer()->notNull()->defaultValue(0),
            'reason' => $this->string(20)->notNull()->defaultValue(''),
            'moved_item_value' => $this->decimal(10, 2)->notNull()->defaultValue(0),
        ]);

        $this->createIndex('idx_item_id_location_id', $this->tableName, ['item_id', 'location_id']);
        $this->createIndex('idx_location_id', $this->tableName, ['location_id']);
    }
}

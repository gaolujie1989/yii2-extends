<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m180823_153320_sales_order_item extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%sales_order_item}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'sales_order_item_id' => $this->bigPrimaryKey()->unsigned(),
            'sales_order_id' => $this->bigInteger()->unsigned()->notNull(),
            'external_order_item_id' => $this->bigInteger()->unsigned()->notNull()->defaultValue(0),

            'item_id' => $this->bigInteger()->unsigned()->notNull(),
            'item_no' => $this->string(50)->notNull(),
            'external_item_no' => $this->string(50)->notNull(),

            'order_item_name' => $this->string(500)->notNull()->defaultValue(''),
            'price_cent' => $this->integer()->unsigned()->notNull(),
            'currency' => $this->char(3)->notNull(),
            'qty' => $this->integer()->unsigned()->notNull(),
            'discounts' => $this->json(),
        ]);

        $this->createIndex('idx_sales_order_id', $this->tableName, ['sales_order_id']);
        $this->createIndex('idx_external_order_item_id', $this->tableName, ['external_order_item_id']);
        $this->createIndex('idx_item_id', $this->tableName, ['item_id']);
        $this->createIndex('idx_item_no', $this->tableName, ['item_no']);
    }
}

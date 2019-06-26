<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m180823_154927_sales_order_amount extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%sales_order_amount}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'sales_order_amount_id' => $this->bigPrimaryKey()->unsigned(),
            'sales_order_id' => $this->bigInteger()->unsigned()->notNull(),
            'sales_order_item_id' => $this->bigInteger()->unsigned()->notNull()->defaultValue(0),

            'currency' => $this->char(3)->notNull(),
            'exchange_rate' => $this->decimal(10, 4)->notNull()->defaultValue(1),
            'item_total_cent' => $this->integer()->unsigned()->notNull(),
            'discount_total_cent' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'subtotal_cent' => $this->integer()->unsigned()->notNull()->comment('subtotal = item_total - discount_total'),
            'shipping_total_cent' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'tax_total_cent' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'tax_included' => $this->boolean()->notNull()->defaultValue(1),
            'grand_total_cent' => $this->integer()->unsigned()->notNull()->comment('grand_total = subtotal + shipping_total + tax_total(if tax_included = false)'),
        ]);

        $this->createIndex('idx_sales_order_id_order_item_id', $this->tableName, [
            'sales_order_id', 'sales_order_item_id'
        ]);
    }
}

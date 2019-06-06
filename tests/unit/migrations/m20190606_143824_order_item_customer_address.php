<?php

use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m20190606_143824_order_item_customer_address extends Migration
{
    use TraceableColumnTrait;

    public function safeUp()
    {
        $this->createTable('test_order', [
            'order_id' => $this->bigPrimaryKey()->unsigned(),
            'order_no' => $this->string()->notNull()->defaultValue(''),
            'customer_email' => $this->string()->notNull()->defaultValue(''),
            'shipping_address_id' => $this->bigInteger()->unsigned()->notNull()->defaultValue(0),
        ]);

        $this->createTable('test_order_item', [
            'order_item_id' => $this->bigPrimaryKey()->unsigned(),
            'order_id' => $this->bigInteger()->unsigned()->notNull()->defaultValue(0),
            'item_no' => $this->string()->notNull()->defaultValue(''),
            'ordered_qty' => $this->integer()->notNull()->defaultValue(0),
        ]);

        $this->createTable('test_customer', [
            'customer_id' => $this->bigPrimaryKey()->unsigned(),
            'customer_email' => $this->string()->notNull()->defaultValue(''),
        ]);

        $this->createTable('test_address', [
            'address_id' => $this->bigPrimaryKey()->unsigned(),
            'street' => $this->string()->notNull()->defaultValue(''),
        ]);
    }
}

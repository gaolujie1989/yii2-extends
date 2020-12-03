<?php

use yii\db\Migration;

class m190606_143824_test_relation_models extends Migration
{
    public function safeUp()
    {
        $this->createTable('test_order', [
            'test_order_id' => $this->bigPrimaryKey(),
            'order_no' => $this->string()->notNull()->defaultValue(''),
            'customer_email' => $this->string()->notNull()->defaultValue(''),
            'shipping_address_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'warehouse_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'order_amount' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'paid_amount' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'status' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
        ]);

        $this->createTable('test_order_item', [
            'test_order_item_id' => $this->bigPrimaryKey(),
            'test_order_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'item_no' => $this->string()->notNull()->defaultValue(''),
            'ordered_qty' => $this->integer()->notNull()->defaultValue(0),
        ]);

        $this->createTable('test_customer', [
            'test_customer_id' => $this->bigPrimaryKey(),
            'customer_email' => $this->string()->notNull()->defaultValue(''),
            'username' => $this->string()->notNull()->defaultValue(''),
        ]);

        $this->createTable('test_address', [
            'test_address_id' => $this->bigPrimaryKey(),
            'street' => $this->string()->notNull()->defaultValue(''),
        ]);

        $this->createTable('test_order_payment', [
            'test_order_payment_id' => $this->bigPrimaryKey(),
            'test_order_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'transaction_no' => $this->string()->notNull()->defaultValue(''),
            'paid_amount' => $this->integer()->unsigned()->notNull()->defaultValue(0),
        ]);
    }
}

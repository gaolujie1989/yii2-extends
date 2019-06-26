<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m180822_202020_sales_order extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%sales_order}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'sales_order_id' => $this->bigPrimaryKey()->unsigned(),
            'sales_account_id' => $this->bigInteger()->unsigned()->notNull(),
            'sales_account_name' => $this->string(50)->notNull(),

            'customer_id' => $this->bigInteger()->unsigned()->notNull(),
            'customer_email' => $this->string(100)->notNull()->defaultValue(''),
            'customer_phone' => $this->string(50)->notNull()->defaultValue(''),
            'shipping_address_id' => $this->bigInteger()->unsigned()->notNull()->defaultValue(0),
            'billing_address_id' => $this->bigInteger()->unsigned()->notNull()->defaultValue(0),

            'external_order_id' => $this->bigInteger()->unsigned()->notNull()->defaultValue(0),
            'external_order_no' => $this->string(50)->notNull()->defaultValue(''),
            'platform' => $this->string(20)->notNull(),
            'country' => $this->char(2)->notNull(),
            'shipping_country' => $this->char(2)->notNull(),
            'currency' => $this->char(3)->notNull(),

            'payment_method' => $this->string(50)->notNull()->defaultValue(''),
            'payment_status' => $this->tinyInteger()->notNull()->defaultValue(0),
            'transaction_no' => $this->string(50)->notNull()->defaultValue(''),

            'shipping_method' => $this->string(50)->notNull()->defaultValue(''),
            'shipping_status' => $this->tinyInteger()->notNull()->defaultValue(0),
            'shipping_numbers' => $this->json(),

            'ordered_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'paid_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'shipped_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'completed_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'closed_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'refund_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'cancelled_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),

            'cancel_reason' => $this->string()->notNull()->defaultValue(0),
            'note' => $this->string()->notNull()->defaultValue(''),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('idx_customer_id', $this->tableName, ['customer_id']);
        $this->createIndex('idx_customer_email', $this->tableName, ['customer_email']);
        $this->createIndex('idx_customer_phone', $this->tableName, ['customer_phone']);

        $this->createIndex('idx_external_order_id', $this->tableName, ['external_order_id']);
        $this->createIndex('idx_external_order_no', $this->tableName, ['external_order_no']);

        $this->createIndex('idx_ordered_at', $this->tableName, ['ordered_at']);
        $this->createIndex('idx_paid_at', $this->tableName, ['paid_at']);
    }
}

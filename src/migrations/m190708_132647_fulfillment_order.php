<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m190708_132647_fulfillment_order extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%fulfillment_order}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'fulfillment_order_id' => $this->bigPrimaryKey(),
            'fulfillment_account_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'fulfillment_status' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0),
            'order_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'order_status' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0),
            'external_order_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'external_order_no' => $this->string(50)->notNull()->defaultValue(''),
            'external_order_status' => $this->string(20)->notNull()->defaultValue(''),
            'external_order_additional' => $this->json(),
            'external_created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'external_updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'order_pushed_options' => $this->json(),
            'order_pushed_errors' => $this->json(),
            'order_pushed_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'order_pulled_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('uk_fulfillment_account_id_order_id', $this->tableName,
            ['fulfillment_account_id', 'order_id'], true);
        $this->createIndex('uk_fulfillment_account_id_external_order_id', $this->tableName,
            ['fulfillment_account_id', 'external_order_id'], true);
        $this->createIndex('idx_fulfillment_account_id_fulfillment_status', $this->tableName,
            ['fulfillment_account_id', 'fulfillment_status']);
    }
}

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
            'fulfillment_order_id' => $this->bigPrimaryKey()->unsigned(),
            'fulfillment_account_id' => $this->bigInteger()->unsigned()->notNull()->defaultValue(0),
            'order_id' => $this->bigInteger()->unsigned()->notNull(),
            'order_status' => $this->tinyInteger()->unsigned()->notNull(),
            'external_order_id' => $this->bigInteger()->unsigned(),
            'external_order_no' => $this->bigInteger()->unsigned(),
            'external_order_status' => $this->string(20)->notNull()->defaultValue(''),
            'external_order_additional' => $this->json(),
            'external_created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'external_updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'order_options' => $this->json(),
            'order_errors' => $this->json(),
        ]);

        $this->createIndex('uk_fulfillment_account_id_order_id', $this->tableName,
            ['fulfillment_account_id', 'order_id'], true);
        $this->createIndex('uk_fulfillment_account_id_external_order_id', $this->tableName,
            ['fulfillment_account_id', 'external_order_id'], true);
    }
}

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
            'order_updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'external_order_key' => $this->string(50)->notNull()->defaultValue(''),
            'external_order_status' => $this->string(20)->notNull()->defaultValue(''),
            'external_order_additional' => $this->json(),
            'external_created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'external_updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'order_pushed_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'order_pushed_status' => $this->tinyInteger()->notNull()->defaultValue(0),
            'order_pushed_result' => $this->json(),
            'order_pulled_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('uk_order_account', $this->tableName, ['order_id', 'fulfillment_account_id'], true);
        $this->createIndex('uk_external_order_account', $this->tableName, ['external_order_key', 'fulfillment_account_id'], true);
        $this->createIndex('idx_status_account', $this->tableName, ['fulfillment_status', 'fulfillment_account_id']);
    }
}

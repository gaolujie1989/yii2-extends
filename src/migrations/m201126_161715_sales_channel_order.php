<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m201126_161715_sales_channel_order extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%sales_channel_order}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'sales_channel_order_id' => $this->bigPrimaryKey(),
            'sales_channel_account_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'sales_channel_status' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0),
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
            'additional' => $this->json(),
        ]);

        $this->createIndex('uk_external_order_account', $this->tableName, ['external_order_key', 'sales_channel_account_id'], true);
        $this->createIndex('idx_order_id', $this->tableName, ['order_id']);
        $this->createIndex('idx_status_account', $this->tableName, ['sales_channel_status', 'sales_channel_account_id']);
    }
}

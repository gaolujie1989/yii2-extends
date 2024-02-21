<?php

use lujie\extend\db\Migration;

class m230523_192736_sales_channel_order_data extends Migration
{
    public $tableName = '{{%sales_channel_order_data}}';

    public $traceBy = false;

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'sales_channel_order_data_id' => $this->bigPrimaryKey(),
            'sales_channel_account_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'external_order_key' => $this->string(50)->notNull()->defaultValue(''),
            'external_order_no' => $this->string(50)->notNull()->defaultValue(''),
            'external_created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'external_updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'order_data' => $this->binary(),
        ]);

        $this->createIndex('uk_order_key_account_id', $this->tableName, ['external_order_key', 'sales_channel_account_id'], true);
        $this->createIndex('idx_external_created_at', $this->tableName, ['external_created_at']);
        $this->createIndex('idx_external_updated_at', $this->tableName, ['external_updated_at']);
    }
}

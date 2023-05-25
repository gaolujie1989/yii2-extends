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
            'sales_channel_order_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'order_data' => $this->binary(),
        ]);

        $this->createIndex('uk_sales_channel_order_id', $this->tableName, ['sales_channel_order_id'], true);
    }
}

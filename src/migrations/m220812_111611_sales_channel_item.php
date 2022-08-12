<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m220812_111611_sales_channel_item extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%sales_channel_item}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'sales_channel_item_id' => $this->bigPrimaryKey(),
            'sales_channel_account_id' => $this->bigInteger()->notNull(),
            'item_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'item_updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'external_item_key' => $this->string(50)->notNull()->defaultValue(''),
            'external_item_status' => $this->string(20)->notNull()->defaultValue(''),
            'external_item_additional' => $this->json(),
            'external_created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'external_updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'stock_pushed_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'additional' => $this->json(),
        ]);

        $this->createIndex('uk_item_account', $this->tableName, ['item_id', 'sales_channel_account_id'], true);
        $this->createIndex('idx_external_item_account', $this->tableName, ['external_item_key', 'sales_channel_account_id']);
        $this->createIndex('idx_stock_pushed_at', $this->tableName, ['stock_pushed_at']);
    }
}

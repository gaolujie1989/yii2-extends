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
            'item_type' => $this->string(20)->notNull()->defaultValue(0),
            'item_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'item_updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'external_item_key' => $this->string(50)->notNull()->defaultValue(''),
            'external_item_additional' => $this->json(),
            'external_created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'external_updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'item_pushed_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'item_pushed_status' => $this->tinyInteger()->notNull()->defaultValue(0),
            'item_pushed_result' => $this->json(),
            'stock_pushed_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'additional' => $this->json(),
        ]);

        $this->createIndex('idx_item_account', $this->tableName, ['item_id', 'item_type', 'sales_channel_account_id']);
        $this->createIndex('idx_external_item_account', $this->tableName, ['external_item_key', 'sales_channel_account_id']);
        $this->createIndex('idx_stock_pushed_at', $this->tableName, ['stock_pushed_at']);
    }
}

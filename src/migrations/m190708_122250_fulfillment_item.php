<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m190708_122250_fulfillment_item extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%fulfillment_item}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'fulfillment_item_id' => $this->bigPrimaryKey(),
            'fulfillment_account_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'item_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'item_updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'external_item_key' => $this->string(50)->notNull()->defaultValue(''),
            'external_item_additional' => $this->json(),
            'external_created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'external_updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'item_pushed_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'item_pushed_status' => $this->tinyInteger()->notNull()->defaultValue(0),
            'item_pushed_result' => $this->json(),
            'stock_pulled_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('uk_item_account', $this->tableName, ['item_id', 'fulfillment_account_id'], true);
        $this->createIndex('idx_external_item_account', $this->tableName, ['external_item_key', 'fulfillment_account_id']);
    }
}

<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m190726_095731_fulfillment_warehouse_stock extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%fulfillment_warehouse_stock}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'fulfillment_warehouse_stock_id' => $this->bigPrimaryKey(),
            'fulfillment_account_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'item_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'warehouse_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'external_item_key' => $this->string(50)->notNull()->defaultValue(''),
            'external_warehouse_key' => $this->string(50)->notNull()->defaultValue(''),

            'stock_qty' => $this->integer()->notNull()->defaultValue(0),
            'reserved_qty' => $this->integer()->notNull()->defaultValue(0),
            'stock_additional' => $this->json(),
            'external_updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'stock_pulled_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'additional' => $this->json(),
        ]);

        $this->createIndex(
            'uk_item_warehouse_account',
            $this->tableName,
            ['item_id', 'warehouse_id', 'fulfillment_account_id'],
            true
        );
        $this->createIndex(
            'uk_external_item_warehouse_account',
            $this->tableName,
            ['external_item_key', 'external_warehouse_key', 'fulfillment_account_id'],
            true
        );
        $this->createIndex(
            'idx_warehouse_item_account',
            $this->tableName,
            ['warehouse_id', 'item_id', 'fulfillment_account_id'],
            true
        );
    }
}

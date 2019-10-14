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
            'warehouse_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'external_warehouse_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'item_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'external_item_id' => $this->bigInteger()->defaultValue(0),
            'stock_qty' => $this->integer()->defaultValue(0),
            'reserved_qty' => $this->integer()->defaultValue(0),
            'additional' => $this->json(),
            'external_updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'stock_pulled_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('uk_fulfillment_account_id_warehouse_id_item_id', $this->tableName,
            ['fulfillment_account_id', 'warehouse_id', 'item_id'], true);
        $this->createIndex('uk_fulfillment_account_id_external_warehouse_id_external_item_id', $this->tableName,
            ['fulfillment_account_id', 'external_warehouse_id', 'external_item_id'], true);
    }
}

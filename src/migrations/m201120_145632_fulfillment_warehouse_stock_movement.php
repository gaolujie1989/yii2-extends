<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m201120_145632_fulfillment_warehouse_stock_movement extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%fulfillment_warehouse_stock_movement}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'fulfillment_warehouse_stock_movement_id' => $this->bigPrimaryKey(),
            'fulfillment_account_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'item_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'warehouse_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'external_item_key' => $this->string(50)->notNull()->defaultValue(''),
            'external_warehouse_key' => $this->string(50)->notNull()->defaultValue(''),
            'external_movement_key' => $this->string(50)->notNull()->defaultValue(''),

            'movement_type' => $this->string(20)->notNull()->defaultValue(''),
            'movement_qty' => $this->integer()->notNull()->defaultValue(0),
            'related_type' => $this->string(20)->notNull()->defaultValue(''),
            'related_key' => $this->string(50)->notNull()->defaultValue(''),
            'movement_additional' => $this->json(),
            'external_created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'additional' => $this->json(),
        ]);

        $this->createIndex(
            'uk_external_movement_account',
            $this->tableName,
            ['external_movement_key', 'fulfillment_account_id']
        );
        $this->createIndex(
            'idx_item_warehouse_account',
            $this->tableName,
            ['item_id', 'warehouse_id', 'fulfillment_account_id']
        );
        $this->createIndex(
            'idx_external_item_warehouse_account',
            $this->tableName,
            ['external_item_key', 'external_warehouse_key', 'fulfillment_account_id']
        );
        $this->createIndex('idx_external_created_at', $this->tableName, ['external_created_at']);
    }
}

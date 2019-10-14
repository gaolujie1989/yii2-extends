<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m190708_113921_fulfillment_warehouse extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%fulfillment_warehouse}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'fulfillment_warehouse_id' => $this->bigPrimaryKey(),
            'fulfillment_account_id' =>  $this->bigInteger()->notNull()->defaultValue(0),
            'warehouse_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'external_warehouse_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'external_warehouse_name' => $this->string(100)->notNull()->defaultValue(''),
            'additional' => $this->json(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('idx_fulfillment_account_id_warehouse_id', $this->tableName,
            ['fulfillment_account_id', 'warehouse_id']);
        $this->createIndex('uk_fulfillment_account_id_external_warehouse_id', $this->tableName,
            ['fulfillment_account_id', 'external_warehouse_id'], true);
    }
}

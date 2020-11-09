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
            'external_warehouse_key' => $this->string(50)->notNull()->defaultValue(''),
            'additional' => $this->json(),
        ]);

        $this->createIndex('uk_warehouse_id', $this->tableName, ['warehouse_id']);
        $this->createIndex('uk_external_warehouse_account', $this->tableName, ['external_warehouse_key', 'fulfillment_account_id'], true);
    }
}

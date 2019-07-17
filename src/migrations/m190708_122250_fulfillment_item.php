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
            'fulfillment_item_id' => $this->bigPrimaryKey()->unsigned(),
            'fulfillment_account_id' => $this->bigInteger()->unsigned()->notNull()->defaultValue(0),
            'item_id' => $this->bigInteger()->unsigned()->notNull(),
            'external_item_id' => $this->bigInteger()->unsigned()->defaultValue(0),
            'external_item_no' => $this->string(50)->defaultValue(''),
            'external_item_parent_id' => $this->bigInteger()->unsigned()->notNull()->defaultValue(0)
                ->comment('for some system, support variation must link item'),
            'external_created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'external_updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'item_options' => $this->json(),
            'item_errors' => $this->json(),
        ]);

        $this->createIndex('idx_fulfillment_account_id_item_id', $this->tableName,
            ['fulfillment_account_id', 'item_id']);
        $this->createIndex('idx_fulfillment_account_id_external_item_id', $this->tableName,
            ['fulfillment_account_id', 'external_item_id']);
    }
}

<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m201202_160524_item_barcode extends Migration
{
    use TraceableColumnTrait, DropTableTrait;

    public $tableName = '{{%item_barcode}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'item_barcode_id' => $this->bigPrimaryKey(),
            'item_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'code_name' => $this->string(20)->notNull()->defaultValue(''),
            'code_type' => $this->string(20)->notNull()->defaultValue(''),
            'code_text' => $this->string(50)->notNull(),
        ]);

        $this->createIndex('idx_item_id_code_name', $this->tableName, ['item_id', 'code_name']);
        $this->createIndex('uk_code_text', $this->tableName, ['code_text'], true);
    }
}


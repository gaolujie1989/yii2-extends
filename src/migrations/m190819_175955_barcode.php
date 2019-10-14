<?php

use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m190819_175955_barcode extends Migration
{
    use TraceableColumnTrait;

    public $tableName = '{{%barcode}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'barcode_id' => $this->bigPrimaryKey(),
            'code_type' => $this->char(3)->notNull()->defaultValue('')->comment('EAN or UPC'),
            'code_text' => $this->string(13)->notNull()->defaultValue(''),
            'assigned_id' => $this->bigInteger()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('uk_code_text', $this->tableName, ['code_text'], true);
        $this->createIndex('idx_assigned_id', $this->tableName, ['assigned_id']);
    }
}

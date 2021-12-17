<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m211217_131221_document_file extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%document_file}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'document_file_id' => $this->bigPrimaryKey(),
            'document_type' => $this->string(50)->notNull()->defaultValue(''),
            'reference_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'reference_no' => $this->string(50)->notNull()->defaultValue(''),
            'document_no' => $this->string(50)->notNull()->defaultValue(''),
            'document_file' => $this->string()->notNull()->defaultValue(''),
            'document_data' => $this->json(),
            'additional' => $this->json(),
        ]);

        $this->createIndex('idx_type_reference_id', $this->tableName, ['document_type', 'reference_id']);
        $this->createIndex('idx_type_reference_no', $this->tableName, ['document_type', 'reference_no']);
        $this->createIndex('idx_type_document_no', $this->tableName, ['document_type', 'document_no']);
    }
}

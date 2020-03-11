<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m190823_133422_document_template extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%document_template}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'document_template_id' => $this->bigPrimaryKey(),
            'document_type' => $this->string(50)->notNull()->defaultValue(''),
            'reference_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'position' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0),
            'name' => $this->string(250)->notNull()->defaultValue(''),
            'content' => $this->text()->notNull(),
            'additional' => $this->json(),
            'status' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('idx_type_reference_id', $this->tableName, ['document_type', 'reference_id']);
    }
}

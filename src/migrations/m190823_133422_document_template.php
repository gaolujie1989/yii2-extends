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
            'document_reference_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'document_type' => $this->string(50)->notNull()->defaultValue(''),
            'position' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0),
            'title' => $this->string(250)->notNull()->defaultValue(''),
            'subtitle' => $this->string(250)->notNull()->defaultValue(''),
            'content' => $this->text()->notNull(),
            'status' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('idx_type_reference_id', $this->tableName, ['document_type', 'document_reference_id']);
    }
}

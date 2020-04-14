<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m190624_113602_executable_exec extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%executable_exec}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'executable_exec_id' => $this->bigPrimaryKey(),
            'executable_exec_uid' => $this->string(32)->notNull()->defaultValue(''),
            'executable_id' => $this->string(50)->notNull()->defaultValue(''),
            'executor' => $this->string(50)->notNull()->defaultValue(''),
            'queued_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'started_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'finished_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'skipped_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'memory_usage' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'executable' => $this->text(),
            'error' => $this->text(),
            'additional' => $this->json(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('idx_executable_exec_uid', $this->tableName, ['executable_exec_uid']);
        $this->createIndex('idx_executable_id', $this->tableName, ['executable_id']);
        $this->createIndex('idx_started_at', $this->tableName, ['started_at']);
    }
}

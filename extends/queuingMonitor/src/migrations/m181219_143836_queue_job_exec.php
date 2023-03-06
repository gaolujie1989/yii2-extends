<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m181219_143836_queue_job_exec extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%queue_job_exec}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'job_exec_id' => $this->bigPrimaryKey(),
            'queue' => $this->string(50)->notNull()->defaultValue(''),
            'job_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'worker_pid' => $this->integer()->unsigned()->notNull()->defaultValue(0),

            'started_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'finished_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'memory_usage' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'attempt' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0),
            'error' => $this->text(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('idx_job_id_queue', $this->tableName, ['job_id', 'queue']);
        $this->createIndex('idx_start_at_status_queue', $this->tableName, ['started_at', 'status', 'queue']);
    }
}

<?php

class m181219_143836_queue_job_exec extends \lujie\core\db\Migration
{
    public $tableName = '{{%queue_job_exec}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'job_exec_id' => $this->primaryKey(),
            'queue' => $this->string(50)->notNull()->defaultValue(''),
            'job_id' => $this->integer()->notNull()->defaultValue(0),
            'worker_pid' => $this->integer()->notNull()->defaultValue(0),

            'started_at' => $this->integer()->notNull()->defaultValue(0),
            'finished_at' => $this->integer()->notNull()->defaultValue(0),
            'memory_usage' => $this->integer()->notNull()->defaultValue(0),
            'attempt' => $this->tinyInteger()->notNull()->defaultValue(0),
            'error' => $this->text(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),

            'KEY `job_id` (`job_id`)',
        ]);
    }
}

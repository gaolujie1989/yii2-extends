<?php

class m181219_143822_queue_job extends \lujie\core\db\Migration
{
    public $tableName = '{{%queue_job}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'queue_job_id' => $this->primaryKey(),
            'queue' => $this->string(50)->notNull()->defaultValue(''),
            'job_id' => $this->integer()->notNull()->defaultValue(0),
            'job' => $this->text(),
            'ttr' => $this->integer()->notNull()->defaultValue(0),
            'delay' => $this->integer()->notNull()->defaultValue(0),
            'pushed_at' => $this->integer()->notNull()->defaultValue(0),
            'last_exec_id' => $this->integer()->notNull()->defaultValue(0),
            'last_exec_at' => $this->integer()->notNull()->defaultValue(0),
            'last_exec_status' => $this->tinyInteger()->notNull()->defaultValue(0),

            'KEY `job_id` (`job_id`)',
        ]);
    }
}

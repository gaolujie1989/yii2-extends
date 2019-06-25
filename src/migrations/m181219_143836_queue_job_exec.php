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
            'job_exec_id' => $this->bigPrimaryKey()->unsigned(),
            'queue' => $this->string(50)->notNull()->defaultValue(''),
            'job_id' => $this->bigInteger()->unsigned()->notNull()->defaultValue(0),
            'worker_pid' => $this->integer()->unsigned()->notNull()->defaultValue(0),

            'started_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'finished_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'memory_usage' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'attempt' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0),
            'error' => $this->text(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),

            'KEY `job_id` (`job_id`)',
        ]);
    }
}

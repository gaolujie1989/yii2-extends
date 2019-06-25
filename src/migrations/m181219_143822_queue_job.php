<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m181219_143822_queue_job extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%queue_job}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'queue_job_id' => $this->bigPrimaryKey()->unsigned(),
            'queue' => $this->string(50)->notNull()->defaultValue(''),
            'job_id' => $this->bigInteger()->unsigned()->notNull()->defaultValue(0),
            'job' => $this->text(),
            'ttr' => $this->integer()->notNull()->defaultValue(0),
            'delay' => $this->integer()->notNull()->defaultValue(0),
            'pushed_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'last_exec_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'last_exec_status' => $this->tinyInteger()->notNull()->defaultValue(0),

            'KEY `job_id` (`job_id`)',
        ]);
    }
}

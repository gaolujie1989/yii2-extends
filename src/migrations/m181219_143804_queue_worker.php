<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m181219_143804_queue_worker extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%queue_worker}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'queue_worker_id' => $this->bigPrimaryKey(),
            'queue' => $this->string(50)->notNull()->defaultValue(''),
            'pid' => $this->integer()->unsigned()->notNull()->defaultValue(0),

            'started_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'finished_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'pinged_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'success_count' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'failed_count' => $this->integer()->unsigned()->notNull()->defaultValue(0),

            'KEY `pid` (`pid`)',
        ]);
    }
}

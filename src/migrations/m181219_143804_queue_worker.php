<?php

class m181219_143804_queue_worker extends \lujie\core\db\Migration
{
    public $tableName = '{{%queue_worker}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'queue_worker_id' => $this->primaryKey(),
            'queue' => $this->string(50)->notNull()->defaultValue(''),
            'pid' => $this->integer()->notNull()->defaultValue(0),

            'started_at' => $this->integer()->notNull()->defaultValue(0),
            'finished_at' => $this->integer()->notNull()->defaultValue(0),
            'pinged_at' => $this->integer()->notNull()->defaultValue(0),
            'success_count' => $this->integer()->notNull()->defaultValue(0),
            'failed_count' => $this->integer()->notNull()->defaultValue(0),

            'KEY `pid` (`pid`)',
        ]);
    }
}

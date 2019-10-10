<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m191010_094534_task_group extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%task_group}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'task_group_id' => $this->bigPrimaryKey()->unique(),
            'project_id' => $this->bigInteger()->unsigned()->notNull()->defaultValue(0),
            'position' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0),
            'name' => $this->string(250)->notNull()->defaultValue(''),
            'description' => $this->string(1000)->notNull()->defaultValue(''),
        ]);

        $this->createIndex('idx_project_id', $this->tableName, ['project_id']);
    }
}

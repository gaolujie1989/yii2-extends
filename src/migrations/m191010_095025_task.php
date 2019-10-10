<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m191010_095025_task extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%task}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'task_id' => $this->bigPrimaryKey()->unsigned(),
            'project_id' => $this->bigInteger()->unsigned()->notNull()->defaultValue(0),
            'task_group_id' => $this->bigInteger()->unsigned()->notNull()->defaultValue(0),
            'parent_task_id' => $this->bigInteger()->unsigned()->notNull()->defaultValue(0),
            'position' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'name' => $this->string(250)->notNull()->defaultValue(''),
            'description' => $this->string(1000)->notNull()->defaultValue(''),
            'additional' => $this->json(),
            'priority' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),
            'owner_id' => $this->bigInteger()->unsigned()->notNull()->defaultValue(0),
            'executor_id' => $this->bigInteger()->unsigned()->notNull()->defaultValue(0),
            'due_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'started_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'finished_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'archived_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'archived_by' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'deleted_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'deleted_by' => $this->integer()->unsigned()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('idx_task_group_id', $this->tableName, ['task_group_id']);
        $this->createIndex('idx_parent_task_id', $this->tableName, ['parent_task_id']);
        $this->createIndex('idx_project_id_name', $this->tableName, ['project_id', 'name']);
        $this->createIndex('idx_project_id_owner_id', $this->tableName, ['project_id', 'owner_id']);
        $this->createIndex('idx_project_id_executor_id', $this->tableName, ['project_id', 'executor_id']);
        $this->createIndex('idx_project_id_due_at', $this->tableName, ['project_id', 'due_at']);
        $this->createIndex('idx_project_id_started_at', $this->tableName, ['project_id', 'started_at']);
        $this->createIndex('idx_project_id_finished_at', $this->tableName, ['project_id', 'finished_at']);
    }
}

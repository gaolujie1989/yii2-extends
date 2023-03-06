<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m191010_093411_project extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%project}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'project_id' => $this->bigPrimaryKey(),
            'name' => $this->string(250)->notNull()->defaultValue(''),
            'description' => $this->string(1000)->notNull()->defaultValue(''),
            'visibility' => $this->string(10)->notNull()->defaultValue(''),
            'owner_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'archived_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'archived_by' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'deleted_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'deleted_by' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'options' => $this->json(),
        ]);

        $this->createIndex('idx_name', $this->tableName, ['name']);
    }
}

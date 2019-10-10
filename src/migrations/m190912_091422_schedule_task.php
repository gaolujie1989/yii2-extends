<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m190912_091422_schedule_task extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%schedule_task}}';

    /**
     * @return bool|void
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'schedule_task_id' => $this->bigPrimaryKey()->unsigned(),
            'position' => $this->smallInteger()->notNull()->defaultValue(0),
            'task_code' => $this->string(50)->notNull(),
            'task_group' => $this->string(50)->notNull()->defaultValue(''),
            'task_desc' => $this->string()->notNull()->defaultValue(''),

            'executable' => $this->json(),
            'expression' => $this->string(50)->notNull()->defaultValue(''),
            'timezone' => $this->string(50)->notNull()->defaultValue(''),

            'should_locked' => $this->tinyInteger()->notNull()->defaultValue(0),
            'mutex' => $this->string(50)->notNull()->defaultValue(''),
            'timeout' => $this->integer()->unsigned()->notNull()->defaultValue(0),

            'should_queued' => $this->tinyInteger()->notNull()->defaultValue(0),
            'queue' => $this->string(50)->notNull()->defaultValue(''),
            'ttr' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'attempts' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0),

            'status' => $this->tinyInteger()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('uk_task_code', $this->tableName, ['task_code'], true);
        $this->createIndex('idx_task_group', $this->tableName, ['task_group'], true);
    }
}

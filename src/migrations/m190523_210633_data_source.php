<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m190523_210633_data_source extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%data_source}}';

    /**
     * @return bool|void
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'data_source_id' => $this->bigPrimaryKey(),
            'data_account_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'name' => $this->string(100)->notNull()->defaultValue(''),
            'type' => $this->string(50)->notNull()->defaultValue(''),
            'condition' => $this->json(),
            'additional' => $this->json(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),
            'last_exec_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'last_exec_status' => $this->tinyInteger()->notNull()->defaultValue(0),
            'last_exec_result' => $this->json(),
        ]);

        $this->createIndex('idx_data_account_id', $this->tableName, ['data_account_id']);
    }
}

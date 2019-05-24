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
            'data_source_id' => $this->primaryKey(),
            'data_account_id' => $this->integer()->notNull()->defaultValue(0),
            'name' => $this->string(100)->notNull()->defaultValue(''),
            'type' => $this->string(50)->notNull()->defaultValue(''),
            'options' => $this->json(),
            'additional_info' => $this->json(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('idx_data_account_id', $this->tableName, ['data_account_id']);
    }
}

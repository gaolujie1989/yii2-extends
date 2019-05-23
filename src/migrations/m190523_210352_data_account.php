<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m190523_210352_data_account extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%data_account}}';

    /**
     * @return bool|void
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'data_account_id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull()->unique(),
            'type' => $this->string(50)->notNull()->defaultValue(''),
            'url' => $this->string()->notNull()->defaultValue(''),
            'username' => $this->string()->notNull()->defaultValue(''),
            'password' => $this->string()->notNull()->defaultValue(''),
            'options' => $this->json(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),
            'owner_id' => $this->integer()->notNull()->defaultValue(0),
        ]);
    }
}

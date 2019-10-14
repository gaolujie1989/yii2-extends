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
            'data_account_id' => $this->bigPrimaryKey(),
            'name' => $this->string(100)->notNull()->defaultValue(''),
            'type' => $this->string(50)->notNull()->defaultValue(''),
            'url' => $this->string()->notNull()->defaultValue(''),
            'username' => $this->string(200)->notNull()->defaultValue(''),
            'password' => $this->string(200)->notNull()->defaultValue(''),
            'options' => $this->json(),
            'additional' => $this->json(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),
        ]);
    }
}

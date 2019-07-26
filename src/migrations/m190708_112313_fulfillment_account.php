<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m190708_112313_fulfillment_account extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%fulfillment_account}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'fulfillment_account_id' => $this->bigPrimaryKey()->unsigned(),
            'name' => $this->string(100)->notNull()->defaultValue(''),
            'type' => $this->string(50)->notNull()->defaultValue(''),
            'url' => $this->string()->notNull()->defaultValue(''),
            'username' => $this->string()->notNull()->defaultValue(''),
            'password' => $this->string()->notNull()->defaultValue(''),
            'options' => $this->json(),
            'additional' => $this->json(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),
        ]);
    }
}

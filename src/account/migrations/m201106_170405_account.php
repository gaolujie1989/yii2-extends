<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m201106_170405_account extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%account}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'account_id' => $this->bigPrimaryKey(),
            'model_type' => $this->string(50)->notNull()->defaultValue(''),
            'name' => $this->string(100)->notNull()->defaultValue(''),
            'type' => $this->string(50)->notNull()->defaultValue(''),
            'url' => $this->string()->notNull()->defaultValue(''),
            'username' => $this->string()->notNull()->defaultValue(''),
            'password' => $this->string()->notNull()->defaultValue(''),
            'options' => $this->json(),
            'additional' => $this->json(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('uk_model_type_name', $this->tableName, ['model_type', 'name'], true);
    }
}

<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m211207_171145_auth_token extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%auth_token}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'auth_token_id' => $this->bigPrimaryKey(),
            'user_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'source' => $this->string(50)->notNull()->defaultValue(''),
            'source_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'source_name' => $this->string(50)->notNull()->defaultValue(''),
            'access_token' => $this->string(50)->notNull()->defaultValue(''),
            'refresh_token' => $this->string(50)->notNull()->defaultValue(''),
            'expires_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('uk_user_source', $this->tableName, ['user_id', 'source'], true);
        $this->createIndex('uk_source_id_name', $this->tableName, ['source', 'source_id', 'source_name'], true);
    }
}

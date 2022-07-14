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
            'auth_service' => $this->string(50)->notNull()->defaultValue(''),
            'auth_user_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'auth_username' => $this->string(50)->notNull()->defaultValue(''),
            'access_token' => $this->string(1000)->notNull()->defaultValue(''),
            'refresh_token' => $this->string(1000)->notNull()->defaultValue(''),
            'expires_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'additional' => $this->json()
        ]);

        $this->createIndex('uk_user_auth_service', $this->tableName, ['user_id', 'auth_service'], true);
        $this->createIndex('uk_auth', $this->tableName, ['auth_service', 'auth_user_id', 'auth_username'], true);
    }
}

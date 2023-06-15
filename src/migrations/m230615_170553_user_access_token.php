<?php
/**
 * @copyright Copyright (c) 2019
 */

use lujie\extend\db\Migration;

class m230615_170553_user_access_token extends Migration
{
    public $traceBy = false;

    public $tableName = '{{%user_access_token}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'user_access_token_id' => $this->bigPrimaryKey(),
            'user_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'access_token' => $this->string(64)->notNull()->defaultValue(''),
            'token_type' => $this->string(20)->notNull()->defaultValue(''),
            'expired_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'last_accessed_at' => $this->integer()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('idx_access_token', $this->tableName, ['access_token']);
        $this->createIndex('idx_expired_at_user_id', $this->tableName, ['expired_at', 'user_id']);
    }
}

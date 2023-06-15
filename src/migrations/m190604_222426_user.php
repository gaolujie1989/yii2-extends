<?php

use lujie\extend\db\Migration;

/**
 * @copyright Copyright (c) 2019
 */

class m190604_222426_user extends Migration
{
    public $tableName = '{{%user}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'user_id' => $this->bigPrimaryKey(),
            'username' => $this->string(200)->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string(60)->notNull(),
            'email' => $this->string(200)->notNull()->unique(),

            'status' => $this->tinyInteger()->notNull()->defaultValue(10),
        ]);
    }
}

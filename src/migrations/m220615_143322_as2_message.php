<?php

use lujie\extend\db\Migration;

class m220615_143322_as2_message extends Migration
{
    public $tableName = '{{%as2_message}}';

    public $traceBy = false;

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->bigPrimaryKey(),
            'message_id' => $this->string(50)->notNull()->defaultValue(0),
            'message_type' => $this->string(50)->notNull()->defaultValue(''),
            'direction' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0),
            'sender_id' => $this->string(50)->notNull()->defaultValue(''),
            'receiver_id' => $this->string(50)->notNull()->defaultValue(''),

            'status' => $this->string(10)->notNull()->defaultValue(''),
            'status_msg' => $this->string(1000)->notNull()->defaultValue(''),

            'mdn_mode' => $this->string(50)->notNull()->defaultValue(''),
            'mdn_status' => $this->string(50)->notNull()->defaultValue(''),
            'mic' => $this->string(100)->notNull()->defaultValue(''),

            'signed' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0),
            'encrypted' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0),
            'compressed' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('uk_message_id', $this->tableName, ['message_id']);
        $this->createIndex('idx_sender_id', $this->tableName, ['sender_id']);
        $this->createIndex('idx_receiver_id', $this->tableName, ['receiver_id']);
    }
}

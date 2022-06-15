<?php

use lujie\extend\db\Migration;

class m220615_145044_as2_message_content extends Migration
{
    public $tableName = '{{%as2_message_content}}';

    public $traceBy = false;

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->bigPrimaryKey(),
            'message_id' => $this->string(50)->notNull()->defaultValue(0),
            'headers' => $this->text(),
            'payload' => $this->text(),
            'mdn_payload' => $this->text(),
        ]);

        $this->createIndex('uk_message_id', $this->tableName, ['message_id']);
    }
}

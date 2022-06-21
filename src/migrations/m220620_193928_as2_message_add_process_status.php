<?php

use lujie\extend\db\Migration;

class m220620_193928_as2_message_add_process_status extends Migration
{
    public $tableName = '{{%as2_message}}';

    public $traceBy = false;

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'processed_status', $this->tinyInteger()->notNull()->defaultValue(0)->after('compressed'));
        $this->addColumn($this->tableName, 'processed_at', $this->integer()->unsigned()->notNull()->defaultValue(0)->after('processed_status'));
        $this->addColumn($this->tableName, 'processed_result', $this->json());
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'processed_status');
        $this->dropColumn($this->tableName, 'processed_at');
        $this->dropColumn($this->tableName, 'processed_result');
    }
}

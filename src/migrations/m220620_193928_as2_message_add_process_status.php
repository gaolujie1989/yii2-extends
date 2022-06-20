<?php

use lujie\extend\db\Migration;

class m220620_193928_as2_message_add_process_status extends Migration
{
    public $tableName = '{{%as2_message}}';

    public $traceBy = false;

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'process_status', $this->tinyInteger()->notNull()->defaultValue(0)->after('compressed'));
        $this->addColumn($this->tableName, 'process_status_msg', $this->string(50)->notNull()->defaultValue('')->after('process_status'));
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'process_status_msg');
        $this->dropColumn($this->tableName, 'process_status');
    }
}

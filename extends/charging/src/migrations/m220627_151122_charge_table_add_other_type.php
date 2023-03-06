<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;

class m220627_151122_charge_table_add_other_type extends \yii\db\Migration
{
    use TraceableColumnTrait, DropTableTrait;

    public $tableName = '{{%charge_table}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'other_type', $this->string(50)->notNull()->defaultValue('')->after('custom_type'));
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'other_type');
    }
}

<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;

class m220608_161822_charge_table_add_percent extends \yii\db\Migration
{
    use TraceableColumnTrait, DropTableTrait;

    public $tableName = '{{%charge_table}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'percent', $this->decimal(5, 2)->notNull()->defaultValue(0)->after('currency'));
    }
}

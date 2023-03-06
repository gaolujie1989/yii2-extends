<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;

class m201013_104308_shipping_table_add_lwh extends \yii\db\Migration
{
    use TraceableColumnTrait, DropTableTrait;

    public $tableName = '{{%shipping_table}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'lwh_mm_limit', $this->integer()->notNull()->defaultValue(0)->after('l2wh_mm_limit'));
        $this->alterColumn($this->tableName, 'carrier', $this->string(10)->notNull()->defaultValue(''));
    }
}

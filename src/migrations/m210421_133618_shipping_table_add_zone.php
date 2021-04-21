<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;

class m210421_133618_shipping_table_add_zone extends \yii\db\Migration
{
    use TraceableColumnTrait, DropTableTrait;

    public $tableName = '{{%shipping_table}}';

    public function safeUp(): void
    {
        $this->addColumn($this->tableName, 'zone', $this->string(10)->notNull()->defaultValue(0)->after('destination'));
    }

    public function safeDown(): void
    {
        $this->dropColumn($this->tableName, 'zone');
    }
}

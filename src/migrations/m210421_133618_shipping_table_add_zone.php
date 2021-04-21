<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;

class m210421_133618_shipping_table_add_zone extends \yii\db\Migration
{
    use TraceableColumnTrait, DropTableTrait;

    public $tableName = '{{%shipping_table}}';

    public function safeUp(): void
    {
        $this->addColumn($this->tableName, 'zone', $this->integer()->notNull()->defaultValue(0)->after('country'));
    }

    public function safeDown(): void
    {
        $this->dropColumn($this->tableName, 'zone');
    }
}

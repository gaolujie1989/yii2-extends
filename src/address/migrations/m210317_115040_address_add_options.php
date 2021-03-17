<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m210317_115040_address_add_options extends Migration
{
    public $tableName = '{{%address}}';

    /**
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function safeUp(): void
    {
        $this->addColumn($this->tableName, 'options', $this->json()->after('phone'));
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'options');
    }
}

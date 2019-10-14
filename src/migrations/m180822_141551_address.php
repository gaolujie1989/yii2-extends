<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m180822_141551_address extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%address}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'address_id' => $this->bigPrimaryKey(),
            'country' => $this->char(2)->notNull(),
            'state' => $this->string(200)->notNull()->defaultValue(''),
            'city' => $this->string(200)->notNull()->defaultValue(''),
            'name1' => $this->string()->notNull()->defaultValue('')->comment('company name'),
            'name2' => $this->string()->notNull()->defaultValue('')->comment('first name'),
            'name3' => $this->string()->notNull()->defaultValue('')->comment('last name'),
            'address1' => $this->string()->notNull()->defaultValue('')->comment('street|pack station|post filiale'),
            'address2' => $this->string()->notNull()->defaultValue('')->comment('house no|pack station id'),
            'address3' => $this->string()->notNull()->defaultValue('')->comment('additional'),
            'postal_code' => $this->string(20)->notNull()->defaultValue(''),
            'email' => $this->string(100)->notNull()->defaultValue(''),
            'phone' => $this->string(50)->notNull()->defaultValue(''),
            'signature' => $this->string(32)->notNull(),
        ]);

        $this->createIndex('idx_signature', $this->tableName, ['signature']);
    }
}

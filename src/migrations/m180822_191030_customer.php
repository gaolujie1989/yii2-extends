<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m180822_191030_customer extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%customer}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'customer_id' => $this->bigPrimaryKey(),

            'email' => $this->string(100)->notNull()->unique(),
            'phone' => $this->string(50)->notNull()->defaultValue(''),
            'ebay_name' => $this->string(50)->notNull()->defaultValue(''),

            'first_name' => $this->string()->notNull()->defaultValue(''),
            'last_name' => $this->string()->notNull()->defaultValue(''),

            'additional' => $this->json(),
        ]);

        $this->createIndex('idx_email', $this->tableName, ['email']);
        $this->createIndex('idx_phone', $this->tableName, ['phone']);
        $this->createIndex('idx_ebay_name', $this->tableName, ['ebay_name']);
    }
}

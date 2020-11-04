<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m201104_102930_address_postal_code extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%address_postal_code}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'address_postal_code_id' => $this->bigPrimaryKey(),
            'country' => $this->char(2)->notNull()->defaultValue(''),
            'postal_code' => $this->string(20)->notNull()->defaultValue(''),
            'type' => $this->string(50)->notNull()->defaultValue(''),
            'started_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'ended_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'note' => $this->string()->notNull()->defaultValue(''),
        ]);

        $this->createIndex('idx_country_time_postal_code', $this->tableName, ['country', 'started_at', 'ended_at', 'postal_code']);
    }
}

<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;

class m210421_131323_country_zone extends \yii\db\Migration
{
    use TraceableColumnTrait, DropTableTrait;

    public $tableName = '{{%country_zone}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'country_zone_id' => $this->bigPrimaryKey(),
            'carrier' => $this->string(10)->notNull()->defaultValue(''),
            'zone' => $this->string(10)->notNull()->defaultValue(''),
            'country' => $this->char(2)->notNull()->defaultValue(''),
            'postal_code_from' => $this->string(20)->notNull()->defaultValue(''),
            'postal_code_to' => $this->string(20)->notNull()->defaultValue(0),
            'started_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'ended_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'owner_id' => $this->bigInteger()->notNull()->defaultValue(0),
        ]);

        $this->createIndex(
            'idx_carrier_country_date_owner',
            $this->tableName,
            ['carrier', 'country', 'started_at', 'ended_at', 'owner_id']
        );
    }
}

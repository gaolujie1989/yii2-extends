<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;

class m210422_090355_shipping_zone extends \yii\db\Migration
{
    use TraceableColumnTrait, DropTableTrait;

    public $tableName = '{{%shipping_zone}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'shipping_zone_id' => $this->bigPrimaryKey(),
            'carrier' => $this->string(10)->notNull()->defaultValue(''),
            'departure' => $this->string(10)->notNull()->defaultValue(''),
            'destination' => $this->char(2)->notNull()->defaultValue(''),
            'zone' => $this->string(10)->notNull()->defaultValue(''),
            'postal_code_from' => $this->string(20)->notNull()->defaultValue(''),
            'postal_code_to' => $this->string(20)->notNull()->defaultValue(0),
            'started_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'ended_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'owner_id' => $this->bigInteger()->notNull()->defaultValue(0),
        ]);

        $this->createIndex(
            'idx_carrier_departure_destination_date_owner',
            $this->tableName,
            ['carrier', 'departure', 'destination', 'started_at', 'ended_at', 'owner_id']
        );
    }
}

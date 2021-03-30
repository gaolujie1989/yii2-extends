<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;

class m191121_170639_shipping_table extends \yii\db\Migration
{
    use TraceableColumnTrait, DropTableTrait;

    public $tableName = '{{%shipping_table}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'shipping_table_id' => $this->bigPrimaryKey(),

            'carrier' => $this->string(10)->notNull()->defaultValue(''),
            'departure' => $this->char(2)->notNull()->defaultValue(''),
            'destination' => $this->char(2)->notNull()->defaultValue(''),
            'weight_g_limit' => $this->integer()->notNull()->defaultValue(0),
            'length_mm_limit' => $this->integer()->notNull()->defaultValue(0),
            'width_mm_limit' => $this->integer()->notNull()->defaultValue(0),
            'height_mm_limit' => $this->integer()->notNull()->defaultValue(0),
            'l2wh_mm_limit' => $this->integer()->notNull()->defaultValue(0),
            'lh_mm_limit' => $this->integer()->notNull()->defaultValue(0),
            'price_cent' => $this->integer()->notNull()->defaultValue(0),
            'currency' => $this->char(3)->notNull()->defaultValue(''),

            'started_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'ended_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'owner_id' => $this->bigInteger()->notNull()->defaultValue(0),
        ]);

        $this->createIndex(
            'idx_carrier_departure_destination_limit_date_owner',
            $this->tableName,
            ['carrier', 'departure', 'destination',
                'weight_g_limit', 'length_mm_limit',
                'width_mm_limit', 'height_mm_limit',
                'l2wh_mm_limit', 'lh_mm_limit',
                'started_at', 'ended_at', 'owner_id', 'price_cent']
        );
    }
}

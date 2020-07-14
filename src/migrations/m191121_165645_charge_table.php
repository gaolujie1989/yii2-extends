<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;

class m191121_165645_charge_table extends \yii\db\Migration
{
    use TraceableColumnTrait, DropTableTrait;

    public $tableName = '{{%charge_table}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'charge_table_id' => $this->bigPrimaryKey(),

            'charge_group' => $this->string(50)->notNull()->defaultValue(''),
            'charge_type' => $this->string(50)->notNull()->defaultValue(''),
            'custom_type' => $this->string(50)->notNull()->defaultValue(''),

            'min_limit' => $this->integer()->notNull()->defaultValue(0),
            'max_limit' => $this->integer()->notNull()->defaultValue(0),
            'limit_unit' => $this->string(10)->notNull()->defaultValue(''),
            'display_limit_unit' => $this->string(10)->notNull()->defaultValue(''),

            'price_cent' => $this->integer()->notNull()->defaultValue(0),
            'currency' => $this->char(3)->notNull()->defaultValue(''),

            'over_limit_price_cent' => $this->integer()->notNull()->defaultValue(0),
            'per_limit' => $this->integer()->notNull()->defaultValue(0),
            'max_over_limit' => $this->integer()->notNull()->defaultValue(0),

            'additional' => $this->json(),

            'started_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'ended_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'owner_id' => $this->bigInteger()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('idx_charge_type_started_at_ended_at', $this->tableName, ['charge_type', 'started_at', 'ended_at']);
    }
}

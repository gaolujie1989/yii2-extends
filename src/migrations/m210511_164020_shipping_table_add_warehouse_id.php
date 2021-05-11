<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;

class m210511_164020_shipping_table_add_warehouse_id extends \yii\db\Migration
{
    use TraceableColumnTrait, DropTableTrait;

    public $tableName = '{{%shipping_table}}';

    public function safeUp(): void
    {
        $this->addColumn($this->tableName, 'warehouse_id', $this->bigInteger()->notNull()->defaultValue(0)->after('shipping_table_id'));
        $this->dropIndex('idx_carrier_departure_destination_date_owner', $this->tableName);
        $this->createIndex('idx_departure_destination_date_owner_warehouse_carrier', $this->tableName, [
            'departure', 'destination',
            'started_at', 'ended_at',
            'owner_id', 'warehouse_id', 'carrier'
        ]);
    }

    public function safeDown(): void
    {
        $this->dropColumn($this->tableName, 'warehouse_id');
    }
}

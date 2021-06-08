<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;

class m200915_155759_shipping_table_add_min_other_limit extends \yii\db\Migration
{
    use TraceableColumnTrait, DropTableTrait;

    public $tableName = '{{%shipping_table}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'length_mm_min_limit', $this->integer()->notNull()->defaultValue(0)->after('length_mm_limit'));
        $this->addColumn($this->tableName, 'width_mm_min_limit', $this->integer()->notNull()->defaultValue(0)->after('width_mm_limit'));
        $this->addColumn($this->tableName, 'height_mm_min_limit', $this->integer()->notNull()->defaultValue(0)->after('height_mm_limit'));
        $this->addColumn($this->tableName, 'volume_mm3_limit', $this->integer()->notNull()->defaultValue(0)->after('lh_mm_limit'));

        $this->dropIndex('idx_carrier_departure_destination_limit_date_owner', $this->tableName);
        $this->createIndex('idx_carrier_departure_destination_date_owner', $this->tableName, [
            'carrier', 'departure', 'destination',
            'started_at', 'ended_at', 'owner_id'
        ]);
    }
}

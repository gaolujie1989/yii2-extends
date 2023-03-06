<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use lujie\fulfillment\constants\FulfillmentConst;
use yii\db\Migration;

class m210714_170341_fulfillment_order_add_charging extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%fulfillment_order}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'charge_pulled_at', $this->integer()->unsigned()->notNull()->defaultValue(0)->after('order_pulled_at'));

        $this->dropIndex('idx_status_type_account', $this->tableName);
        $this->createIndex('idx_status_type_account_charge', $this->tableName,
            ['fulfillment_status', 'fulfillment_type', 'fulfillment_account_id', 'charge_pulled_at']);
    }

    public function safeDown(): void
    {
        $this->dropColumn($this->tableName, 'idx_status_type_account_charge');
    }
}

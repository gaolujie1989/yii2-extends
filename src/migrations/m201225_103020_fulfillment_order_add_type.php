<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use lujie\fulfillment\constants\FulfillmentConst;
use yii\db\Migration;

class m201225_103020_fulfillment_order_add_type extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%fulfillment_order}}';

    public function safeUp()
    {
        $typeColumn = $this->string(10)->notNull()
            ->defaultValue(FulfillmentConst::FULFILLMENT_TYPE_SHIPPING)
            ->after('fulfillment_status');
        $this->addColumn($this->tableName, 'fulfillment_type', $typeColumn);

        $this->dropIndex('uk_order_account', $this->tableName);
        $this->dropIndex('idx_external_order_account', $this->tableName);
        $this->dropIndex('idx_status_account', $this->tableName);

        $this->createIndex('uk_order_type_account', $this->tableName, ['order_id', 'fulfillment_type', 'fulfillment_account_id'], true);
        $this->createIndex('idx_external_order_type_account', $this->tableName, ['external_order_key', 'fulfillment_type', 'fulfillment_account_id']);
        $this->createIndex('idx_status_type_account', $this->tableName, ['fulfillment_status', 'fulfillment_type', 'fulfillment_account_id']);
    }

    public function safeDown(): void
    {
        $this->dropIndex('uk_order_type_account', $this->tableName);
        $this->dropIndex('idx_external_order_type_account', $this->tableName);
        $this->dropIndex('idx_status_type_account', $this->tableName);
        $this->dropColumn('fulfillment_type', $this->tableName);

        $this->createIndex('uk_order_account', $this->tableName, ['order_id', 'fulfillment_account_id'], true);
        $this->createIndex('idx_external_order_account', $this->tableName, ['external_order_key', 'fulfillment_account_id']);
        $this->createIndex('idx_status_account', $this->tableName, ['fulfillment_status', 'fulfillment_account_id']);
    }
}

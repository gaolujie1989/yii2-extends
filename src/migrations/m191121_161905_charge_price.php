<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;

class m191121_161905_charge_price extends \yii\db\Migration
{
    use TraceableColumnTrait, DropTableTrait;

    public $tableName = '{{%charge_price}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'charge_price_id' => $this->bigPrimaryKey(),

            'charge_group' => $this->string(50)->notNull()->defaultValue(''),
            'charge_type' => $this->string(50)->notNull()->defaultValue(''),
            'custom_type' => $this->string(50)->notNull()->defaultValue(''),

            'model_type' => $this->string(50)->notNull()->defaultValue(''),
            'model_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'parent_model_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'price_table_id' => $this->bigInteger()->notNull()->defaultValue(0),

            'price_cent' => $this->integer()->notNull()->defaultValue(0),
            'qty' => $this->integer()->notNull()->defaultValue(0),
            'subtotal_cent' => $this->integer()->notNull()->defaultValue(0),
            'discount_cent' => $this->integer()->notNull()->defaultValue(0),
            'surcharge_cent' => $this->integer()->notNull()->defaultValue(0),
            'grant_total_cent' => $this->integer()->notNull()->defaultValue(0),
            'currency' => $this->char(3)->notNull()->defaultValue(''),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),
            'note' => $this->string(1000)->notNull()->defaultValue(''),

            'additional' => $this->json(),
            'owner_id' => $this->bigInteger()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('uk_model_id_model_type_charge_type', $this->tableName, ['model_id', 'model_type', 'charge_type'], true);
        $this->createIndex('idx_parent_model_id_model_type', $this->tableName, ['parent_model_id', 'model_type']);
        $this->createIndex('idx_owner_id_charge_type', $this->tableName, ['owner_id', 'charge_type']);
    }
}

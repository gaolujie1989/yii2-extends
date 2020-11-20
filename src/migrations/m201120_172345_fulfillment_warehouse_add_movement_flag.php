<?php

use yii\db\Migration;

class m201120_172345_fulfillment_warehouse_add_movement_flag extends Migration
{
    public $tableName = '{{%fulfillment_warehouse}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'support_movement', $this->tinyInteger()->notNull()->defaultValue(0)->after('external_warehouse_additional'));
        $this->addColumn($this->tableName, 'external_movement_at', $this->integer()->unsigned()->notNull()->defaultValue(0)->after('support_movement'));
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'support_movement');
        $this->dropColumn($this->tableName, 'external_movement_at');
    }
}

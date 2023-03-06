<?php

use yii\db\Migration;

class m201209_173055_fulfillment_item_value_add_latest extends Migration
{
    public $tableName = '{{%fulfillment_item_value}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'latest', $this->tinyInteger()->notNull()->defaultValue(0)->after('value_date'));
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'latest');
    }
}

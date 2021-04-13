<?php

class m210413_164512_charge_price_add_model_no extends \yii\db\Migration
{
    public $tableName = '{{%charge_price}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'model_no', $this->string(50)->notNull()->defaultValue('')->after('model_id'));
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'model_no');
    }
}

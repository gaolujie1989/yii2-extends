<?php

use yii\db\Migration;

class m170720_163729_history_detail extends Migration
{

    protected $tableName = '{{%history}}';

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'history_id' => $this->integer()->notNull(),
            'field_name' => $this->string(50)->notNull(),
            'old_value' => $this->text()->notNull()->defaultValue(''),
            'new_value' => $this->text()->notNull()->defaultValue(''),

            'KEY `history_id` (`history_id`)',
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}

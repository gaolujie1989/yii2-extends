<?php

use yii\db\Migration;

class m170720_163717_history extends Migration
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
            'event' => $this->integer()->notNull()->defaultValue(1),
            'table_name' => $this->string(50)->notNull(),
            'row_id' => $this->integer()->notNull(),
            'custom_id' => $this->integer()->notNull()->defaultValue(0),
            'custom_data' => $this->json(),

            'created_at' => $this->integer(11)->notNull(),
            'created_by' => $this->integer(11)->notNull(),

            'KEY `table_name` (`table_name`)',
            'KEY `row_id` (`row_id`)',
            'KEY `other_id` (`other_id`)',
            'KEY `created_at` (`created_at`)',
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}

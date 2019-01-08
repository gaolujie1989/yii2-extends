<?php

class m190108_141522_deleted_data extends \yii\db\Migration
{
    public $tableName = 'deleted_data';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'table_name' => $this->string()->notNull(),
            'row_id' => $this->integer()->notNull(),
            'row_data' => $this->json(),

            'created_at' => $this->integer()->notNull()->defaultValue(0),
            'created_by' => $this->integer()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('idx_table_name_data_id', $this->tableName, ['table_name', 'row_id']);
    }
}

<?php

class m170720_163717_history extends \lujie\core\db\Migration
{
    protected $tableName = '{{%history}}';

    /**
     * @return array
     * @inheritdoc
     */
    public function getDefaultTableColumns()
    {
        return [
            'created_at' => $this->integer()->notNull()->defaultValue(0),
            'created_by' => $this->integer()->notNull()->defaultValue(0),
        ];
    }

    /**
     * @inheritdoc
     */
    public function createDefaultTableIndexes()
    {
    }

    /**
     * @return bool|void
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'event' => $this->integer()->notNull()->defaultValue(0),
            'table_name' => $this->string(50)->notNull(),
            'row_id' => $this->integer()->notNull(),
            'custom_id' => $this->integer()->notNull()->defaultValue(0),
            'custom_data' => $this->json(),

            'created_at' => $this->integer(11)->notNull(),
            'created_by' => $this->integer(11)->notNull(),
        ]);

        $this->createIndex('table_row_id', $this->tableName, ['table_name', 'row_id']);
    }
}

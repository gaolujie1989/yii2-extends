<?php

class m170720_163729_history_detail extends \lujie\core\db\Migration
{
    protected $tableName = '{{%history_detail}}';

    /**
     * @return array
     * @inheritdoc
     */
    public function getDefaultTableColumns()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function createDefaultTableIndexes()
    {
    }

    /**
     * @return bool|void
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'history_id' => $this->integer()->notNull(),
            'field_name' => $this->string(50)->notNull(),
            'old_value' => $this->text()->notNull()->defaultValue(''),
            'new_value' => $this->text()->notNull()->defaultValue(''),
        ]);

        $this->createIndex('history_id', $this->tableName, ['history_id']);
    }
}

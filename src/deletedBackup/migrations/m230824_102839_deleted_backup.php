<?php

class m230824_102839_deleted_backup extends \lujie\extend\db\Migration
{
    public $tableName = '{{%deleted_backup}}';

    public $traceUpdate = false;

    /**
     * @return bool|void
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'deleted_backup_id' => $this->bigPrimaryKey(),
            'model_type' => $this->string(50)->notNull()->defaultValue(''),
            'model_class' => $this->string(200)->notNull()->defaultValue(''),
            'row_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'row_key' => $this->string(50)->notNull()->defaultValue(''),
            'row_parent_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'row_data' => $this->json(),
        ]);

        $this->createIndex('idx_row_id', $this->tableName, ['row_id']);
        $this->createIndex('idx_row_key', $this->tableName, ['row_key']);
        $this->createIndex('idx_row_parent_id', $this->tableName, ['row_parent_id']);
    }
}

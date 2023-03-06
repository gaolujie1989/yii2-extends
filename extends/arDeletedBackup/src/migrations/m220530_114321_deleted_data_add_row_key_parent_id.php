<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m220530_114321_deleted_data_add_row_key_parent_id extends \yii\db\Migration
{
    use TraceableColumnTrait, DropTableTrait;

    public $tableName = '{{%deleted_data}}';

    /**
     * @return bool|void
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'row_key', $this->string(50)->notNull()->defaultValue('')->after('row_id'));
        $this->addColumn($this->tableName, 'row_parent_id', $this->bigInteger()->notNull()->defaultValue(0)->after('row_key'));

        $this->createIndex('idx_table_name_row_key', $this->tableName, ['table_name', 'row_key']);
        $this->createIndex('idx_table_name_row_parent_id', $this->tableName, ['table_name', 'row_parent_id']);
    }
}

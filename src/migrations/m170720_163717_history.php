<?php

use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m170720_163717_history extends \yii\db\Migration
{
    use TraceableColumnTrait;

    protected $tableName = '{{%history}}';

    /**
     * @return array
     * @inheritdoc
     */
    public function getDefaultTableColumns(): array
    {
        /** @var Migration $this */
        return [
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'created_by' => $this->integer()->unsigned()->notNull()->defaultValue(0),
        ];
    }

    /**
     * @return bool|void
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->bigPrimaryKey(),
            'table_name' => $this->string(50)->notNull()->defaultValue(''),
            'row_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'old_data' => $this->json(),
            'new_data' => $this->json(),
        ]);

        $this->createIndex('idx_table_name_row_id', $this->tableName, ['table_name', 'row_id']);
    }
}

<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m190523_222903_data_record extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%data_record}}';

    /**
     * @return array
     * @inheritdoc
     */
    public function getDefaultTableColumns(): array
    {
        /** @var \yii\db\Migration $this */
        return [
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
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
            'data_record_id' => $this->bigPrimaryKey(),
            'data_account_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'data_source_type' => $this->string(50)->notNull()->defaultValue(0),
            'data_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'data_key' => $this->string()->notNull()->defaultValue(''),
            'data_parent_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'data_additional' => $this->json(),
            'data_created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'data_updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('idx_data_id_source_type_account', $this->tableName, ['data_id', 'data_source_type', 'data_account_id']);
        $this->createIndex('idx_data_key_source_type_account', $this->tableName, ['data_parent_id', 'data_source_type', 'data_account_id']);
        $this->createIndex('idx_data_parent_id_source_type_account', $this->tableName, ['data_parent_id', 'data_source_type', 'data_account_id']);
        $this->createIndex('idx_data_source_account', $this->tableName, ['data_source_type', 'data_account_id']);
        $this->createIndex('idx_updated_at_source_type_account', $this->tableName, ['data_updated_at', 'data_source_type', 'data_account_id']);
        $this->createIndex('idx_created_at_source_type_account', $this->tableName, ['data_created_at', 'data_source_type', 'data_account_id']);
    }
}

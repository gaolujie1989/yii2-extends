<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m190506_095550_upload_saved_file extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%upload_saved_file}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'upload_saved_file_id' => $this->bigPrimaryKey()->unsigned(),
            'model_type' => $this->string(50)->notNull()->defaultValue(''),
            'model_id' => $this->bigInteger()->unsigned()->notNull()->defaultValue(0),
            'model_parent_id' => $this->bigInteger()->unsigned()->notNull()->defaultValue(0),
            'position' => $this->smallInteger()->unsigned()->notNull()->defaultValue(0),
            'file' => $this->string()->notNull(),
            'name' => $this->string()->notNull()->defaultValue(''),
            'ext' => $this->string(10)->notNull()->defaultValue(''),
            'size' =>  $this->integer()->unsigned()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('idx_model_type_model_id', $this->tableName, ['model_type', 'model_id']);
        $this->createIndex('idx_model_type_model_parent_id', $this->tableName, ['model_type', 'model_parent_id']);
        $this->createIndex('idx_file', $this->tableName, ['file']);
    }
}

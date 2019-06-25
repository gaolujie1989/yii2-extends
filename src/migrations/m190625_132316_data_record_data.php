<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m190625_132316_data_record_data extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%data_record_data}}';

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
            'data_record_data_id' => $this->bigPrimaryKey()->unsigned(),
            'data_record_id' => $this->bigPrimaryKey()->unsigned(),
            'data_text' => $this->binary(),
        ]);

        $this->createIndex('idx_data_record_id', $this->tableName, ['data_record_id']);
    }
}

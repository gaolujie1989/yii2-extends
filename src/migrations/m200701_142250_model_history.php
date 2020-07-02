<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m200701_142250_model_history extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    protected $tableName = '{{%model_history}}';

    /**
     * @return array
     * @inheritdoc
     */
    public function getDefaultTableColumns(): array
    {
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
            'model_history_id' => $this->bigPrimaryKey(),
            'model_type' => $this->string(50)->notNull()->defaultValue(''),
            'model_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'parent_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'summary' => $this->string()->notNull()->defaultValue(''),
            'details' => $this->json(),
        ]);

        $this->createIndex('idx_model_id', $this->tableName, ['model_id']);
        $this->createIndex('idx_parent_id', $this->tableName, ['parent_id']);
    }
}

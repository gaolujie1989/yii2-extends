<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m200111_145522_comment extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%comment}}';

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

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'comment_id' => $this->bigPrimaryKey(),
            'model_type' => $this->string(50)->notNull()->defaultValue(''),
            'model_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'content' => $this->string(2000)->notNull()->defaultValue(''),
        ]);

        $this->createIndex('idx_model_type_id', $this->tableName, ['model_type', 'model_id']);
    }
}

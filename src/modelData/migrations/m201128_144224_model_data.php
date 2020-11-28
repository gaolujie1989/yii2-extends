<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m201128_144224_model_data extends Migration
{
    use TraceableColumnTrait, DropTableTrait;

    public $tableName = '{{%model_data}}';

    /**
     * @return bool|void
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'model_data_id' => $this->bigPrimaryKey(),
            'model_type' => $this->string(50)->notNull()->defaultValue(0),
            'model_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'data_text' => $this->binary(),
        ]);

        $this->createIndex('idx_model_id_type', $this->tableName, ['model_id', 'model_type']);
    }
}

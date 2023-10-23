<?php

class m230824_104122_model_history extends \lujie\extend\db\Migration
{
    public $tableName = '{{%model_history}}';

    public $traceUpdate = false;

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
            'model_class' => $this->string(200)->notNull()->defaultValue(''),
            'model_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'model_key' => $this->string(50)->notNull()->defaultValue(''),
            'model_parent_id' => $this->bigInteger()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('idx_model_id', $this->tableName, ['model_id']);
        $this->createIndex('idx_model_key', $this->tableName, ['model_key']);
        $this->createIndex('idx_model_parent_id', $this->tableName, ['model_parent_id']);
    }
}

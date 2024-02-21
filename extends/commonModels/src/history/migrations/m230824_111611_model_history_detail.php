<?php

class m230824_111611_model_history_detail extends \lujie\extend\db\Migration
{
    public $tableName = '{{%model_history_detail}}';

    public $traceUpdate = false;

    /**
     * @return bool|void
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'model_history_detail_id' => $this->bigPrimaryKey(),
            'model_history_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'changed_attribute' => $this->string(50)->notNull()->defaultValue(''),
            'old_value' => $this->text(),
            'new_value' => $this->text(),
        ]);

        $this->createIndex('uk_model_history_id_attribute', $this->tableName, ['model_history_id', 'changed_attribute'], true);
    }
}

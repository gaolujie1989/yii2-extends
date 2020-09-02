<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use yii\db\Migration;

/**
 * Class m200902_163924_model_text
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class m200902_163924_model_text extends Migration
{
    use DropTableTrait, TraceableBehaviorTrait;

    public $tableName = '{{%model_text}}';

    /**
     * @return bool|void
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'model_text_id' => $this->bigPrimaryKey(),
            'model_type' => $this->string(50)->notNull()->defaultValue(''),
            'model_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'key' => $this->string(50)->notNull(),
            'value' => $this->text()->notNull(),
            'channel' => $this->string(50)->notNull(),
        ]);

        $this->createIndex('idx_model_type_model_id_key_channel', $this->tableName, ['model_type', 'model_id', 'key', 'channel']);
    }
}

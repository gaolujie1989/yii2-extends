<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

/**
 * Class m200911_104910_option
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class m220323_092010_model_option extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%model_option}}';

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
     * @return false|mixed|void
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'model_option_id' => $this->bigPrimaryKey(),
            'model_type' => $this->string(50)->notNull()->defaultValue(''),
            'model_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'option_id' => $this->bigInteger()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('idx_model_type_id', $this->tableName, ['model_type', 'model_id']);
        $this->createIndex('idx_option_id', $this->tableName, ['option_id']);
    }
}

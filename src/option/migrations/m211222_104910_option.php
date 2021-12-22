<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use yii\db\Migration;

/**
 * Class m200911_104910_option
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class m211222_104910_option extends Migration
{
    use DropTableTrait, TraceableBehaviorTrait;

    public $tableName = '{{%option}}';

    /**
     * @return bool|void
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'option_id' => $this->bigPrimaryKey(),
            'type' => $this->string(50)->notNull()->defaultValue(''),
            'value' => $this->string(50)->notNull()->defaultValue(''),
            'value_type' => $this->tinyInteger()->notNull()->defaultValue(0),
            'tag' => $this->string(10)->notNull()->defaultValue(0),
            'position' => $this->smallInteger()->notNull()->defaultValue(0),
            'name' => $this->string()->notNull()->defaultValue(''),
            'labels' => $this->json(),
            'additional' => $this->json(),
        ]);

        $this->createIndex('uk_type_value', $this->tableName, ['type', 'value'], true);
    }
}

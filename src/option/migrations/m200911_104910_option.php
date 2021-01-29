<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use yii\db\Migration;

/**
 * Class m200902_163924_model_text
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class m200911_104910_option extends Migration
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
            'parent_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'position' => $this->smallInteger()->notNull()->defaultValue(0),
            'key' => $this->string(50)->notNull()->defaultValue(''),
            'name' => $this->string()->notNull()->defaultValue(''),
            'labels' => $this->json(),
            'additional' => $this->json(),
        ]);

        $this->createIndex('uk_parent_id_key', $this->tableName, ['parent_id', 'key'], true);
    }
}

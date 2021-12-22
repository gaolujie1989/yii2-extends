<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use yii\db\Migration;

/**
 * Class m200911_104910_option
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class m211222_100845_category extends Migration
{
    use DropTableTrait, TraceableBehaviorTrait;

    public $tableName = '{{%category}}';

    /**
     * @return bool|void
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'category_id' => $this->bigPrimaryKey(),
            'parent_id' => $this->string(50)->notNull()->defaultValue(''),
            'name' => $this->string(50)->notNull()->defaultValue(''),
            'position' => $this->smallInteger()->notNull()->defaultValue(0),
            'labels' => $this->json(),
            'additional' => $this->json(),
        ]);

        $this->createIndex('uk_parent_name', $this->tableName, ['parent_id', 'name'], true);
    }
}

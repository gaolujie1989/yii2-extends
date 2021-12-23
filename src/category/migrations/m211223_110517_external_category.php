<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use yii\db\Migration;

/**
 * Class m211223_110517_external_category
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class m211223_110517_external_category extends Migration
{
    use DropTableTrait, TraceableBehaviorTrait;

    public $tableName = '{{%external_category}}';

    /**
     * @return bool|void
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->bigPrimaryKey(),
            'external_type' => $this->string(50)->notNull()->defaultValue(''),
            'category_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'parent_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'position' => $this->smallInteger()->notNull()->defaultValue(0),
            'name' => $this->string(200)->notNull()->defaultValue(''),
            'labels' => $this->json(),
            'additional' => $this->json(),
        ]);

        $this->createIndex('uk_external_type_id', $this->tableName, ['external_type', 'category_id'], true);
        $this->createIndex('idx_external_type_parent_id', $this->tableName, ['external_type', 'parent_id']);
    }
}

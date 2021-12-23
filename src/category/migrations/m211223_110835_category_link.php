<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use yii\db\Migration;

/**
 * Class m211223_110517_external_category
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class m211223_110835_category_link extends Migration
{
    use DropTableTrait, TraceableBehaviorTrait;

    public $tableName = '{{%category_link}}';

    /**
     * @return bool|void
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'category_link_id' => $this->bigPrimaryKey(),
            'category_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'external_type' => $this->bigInteger()->notNull()->defaultValue(0),
            'external_category_id' => $this->bigInteger()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('uk_category_external_type', $this->tableName, ['category_id', 'external_type'], true);
        $this->createIndex('idx_external_type_category', $this->tableName, ['external_type', 'external_category_id']);
    }
}

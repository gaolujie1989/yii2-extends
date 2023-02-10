<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m230210_145923_otto_category extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $traceBy = false;

    public $tableName = '{{%otto_category}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'otto_category_id' => $this->bigPrimaryKey(),
            'category_group' => $this->string(200)->notNull()->defaultValue(''),
            'name' => $this->string(200)->notNull()->defaultValue(''),
            'title' => $this->string(200)->notNull()->defaultValue(''),
            'attributes' => $this->json(),
            'variation_themes' => $this->json(),
            'otto_created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'otto_updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('uk_category_group_name', $this->tableName, ['category_group', 'name'], true);
        $this->createIndex('idx_name', $this->tableName, ['name']);
    }
}

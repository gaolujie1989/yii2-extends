<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m230213_112131_otto_category_group_attribute extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $traceBy = false;

    public $tableName = '{{%otto_category_group_attribute}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'otto_category_group_attribute_id' => $this->bigPrimaryKey(),
            'category_group' => $this->string(200)->notNull()->defaultValue(''),
            'attribute_group' => $this->string(200)->notNull()->defaultValue(''),
            'name' => $this->string(200)->notNull()->defaultValue(''),
        ]);

        $this->createIndex('idx_attribute_group_name_category', $this->tableName, ['attribute_group', 'name', 'category_group']);
        $this->createIndex('idx_category_group', $this->tableName, ['category_group']);
    }
}

<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m230210_152342_otto_category extends Migration
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
        ]);

        $this->createIndex('idx_category_group_name', $this->tableName, ['category_group', 'name']);
        $this->createIndex('idx_name', $this->tableName, ['name']);
    }
}

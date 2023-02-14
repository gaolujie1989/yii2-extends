<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m230214_162934_otto_brand extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $traceBy = false;

    public $tableName = '{{%otto_brand}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'otto_brand_id' => $this->bigPrimaryKey(),
            'key' => $this->string(20)->notNull()->defaultValue(''),
            'name' => $this->string(200)->notNull()->defaultValue(''),
            'logo' => $this->string(200)->notNull()->defaultValue(''),
            'usable' => $this->tinyInteger()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('uk_key', $this->tableName, ['key'], true);
        $this->createIndex('idx_name', $this->tableName, ['name']);
    }
}

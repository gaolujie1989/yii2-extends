<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m230210_145923_otto_category_group extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $traceBy = false;

    public $tableName = '{{%otto_category_group}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'otto_category_group_id' => $this->bigPrimaryKey(),
            'category_group' => $this->string(200)->notNull()->defaultValue(''),
            'categories' => $this->json(),
            'title' => $this->string(200)->notNull()->defaultValue(''),
            'title_attributes' => $this->json(),
            'attributes' => $this->json(),
            'variation_themes' => $this->json(),
            'otto_created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'otto_updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('uk_category_group', $this->tableName, ['category_group'], true);
    }
}

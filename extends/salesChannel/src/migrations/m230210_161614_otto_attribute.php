<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m230210_161614_otto_attribute extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $traceBy = false;

    public $tableName = '{{%otto_attribute}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'otto_attribute_id' => $this->bigPrimaryKey(),
            'attribute_group' => $this->string(200)->notNull()->defaultValue(''),
            'name' => $this->string(200)->notNull()->defaultValue(''),
            'type' => $this->string(10)->notNull()->defaultValue(''),
            'multi_value' => $this->tinyInteger()->notNull()->defaultValue(0),
            'unit' => $this->string(20)->notNull()->defaultValue(''),
            'unit_display_name' => $this->string(50)->notNull()->defaultValue(''),
            'allowed_values' => $this->json(),
            'feature_relevance' => $this->json(),
            'related_media_assets' => $this->json(),
            'relevance' => $this->string(10)->notNull()->defaultValue(''),
            'description' => $this->string(1000)->notNull()->defaultValue(''),
            'example_values' => $this->json(),
            'recommended_values' => $this->json(),
            'reference' => $this->string(200)->notNull()->defaultValue(''),
        ]);

        $this->createIndex('idx_attribute_group_name', $this->tableName, ['attribute_group', 'name']);
        $this->createIndex('idx_name', $this->tableName, ['name']);
    }
}

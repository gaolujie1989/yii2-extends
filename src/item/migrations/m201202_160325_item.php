<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m201202_160325_item extends Migration
{
    use TraceableColumnTrait, DropTableTrait;

    public $tableName = '{{%item}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'item_id' => $this->bigPrimaryKey(),
            'item_no' => $this->string(50)->notNull()->defaultValue(''),
            'item_type' => $this->string(20)->notNull()->defaultValue(''),

            'names' => $this->json(),

            'weight_g' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'length_mm' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'width_mm' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'height_mm' => $this->integer()->unsigned()->notNull()->defaultValue(0),

            'note' => $this->string(1000)->notNull()->defaultValue(''),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),
            'additional' => $this->json(),
        ]);

        $this->createIndex('idx_item_no', $this->tableName, ['item_no']);
    }
}

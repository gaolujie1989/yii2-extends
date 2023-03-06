<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m220322_150726_barcode extends Migration
{
    use TraceableColumnTrait, DropTableTrait;

    public $tableName = '{{%barcode}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'barcode_id' => $this->bigPrimaryKey(),
            'code_type' => $this->char(3)->notNull()->defaultValue('')->comment('EAN or UPC'),
            'code_text' => $this->string(13)->notNull()->defaultValue(''),
            'model_type' => $this->string(50)->notNull()->defaultValue(''),
            'model_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'owner_id' => $this->bigInteger()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('uk_code_text', $this->tableName, ['code_text'], true);
        $this->createIndex('idx_model_type_id', $this->tableName, ['model_type', 'model_id']);
    }
}

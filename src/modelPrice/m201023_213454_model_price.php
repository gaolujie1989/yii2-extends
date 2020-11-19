<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m201023_213454_model_price extends Migration
{
    use TraceableColumnTrait, DropTableTrait;

    public $tableName = '{{%model_price}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'model_price_id' => $this->bigPrimaryKey(),
            'model_type' => $this->string(50)->notNull()->defaultValue(0),
            'model_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'currency' => $this->char(3)->notNull()->defaultValue(''),
            'price_cent' => $this->integer()->notNull()->defaultValue(0),
            'cost_cent' => $this->integer()->notNull()->defaultValue(0),
            'margin_cent' => $this->integer()->notNull()->defaultValue(0),
            'is_custom_price' => $this->tinyInteger()->notNull()->defaultValue(0),
            'is_custom_cost' => $this->tinyInteger()->notNull()->defaultValue(0),
            'additional_prices' => $this->json(),
            'additional' => $this->json(),
            'note' => $this->string(1000)->notNull()->defaultValue(''),
        ]);

        $this->createIndex('idx_model_type_model_id', $this->tableName, ['model_type', 'model_id']);
    }
}

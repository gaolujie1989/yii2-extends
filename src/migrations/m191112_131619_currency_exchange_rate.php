<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m191112_131619_currency_exchange_rate extends Migration
{
    use TraceableColumnTrait, DropTableTrait;

    public $tableName = '{{%currency_exchange_rate}}';

    /**
     * @return array
     * @inheritdoc
     */
    public function getDefaultTableColumns(): array
    {
        /** @var Migration $this */
        return [
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
        ];
    }

    /**
     * @inheritdoc
     */
    public function safeUp(): void
    {
        $this->createTable($this->tableName, [
            'id' => $this->bigPrimaryKey(),
            'from' => $this->char(3)->notNull()->defaultValue(''),
            'to' => $this->char(3)->notNull()->defaultValue(''),
            'rate' => $this->decimal(10, 4)->notNull()->defaultValue(0),
            'date' => $this->date()->notNull(),
        ]);

        $this->createIndex('uk_from_to_date', $this->tableName, ['from', 'to', 'date'], true);
    }
}

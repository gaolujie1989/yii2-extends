<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

/**
 * Class m210319_131517_shipping_carrier_rule
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class m210319_131517_shipping_rule extends Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName = '{{%shipping_rule}}';

    public function safeUp(): void
    {
        $this->createTable($this->tableName, [
            'shipping_rule_id' => $this->bigPrimaryKey(),
            'country' => $this->char(2)->notNull()->defaultValue(''),
            'item_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'warehouse_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'carrier' => $this->string(10)->notNull()->defaultValue(''),
            'priority' => $this->tinyInteger()->notNull()->defaultValue(0),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),
            'owner_id' => $this->bigInteger()->notNull()->defaultValue(0),
        ]);

        $this->createIndex(
            'uk_owner_country_item_warehouse',
            $this->tableName,
            ['owner_id', 'country', 'item_id', 'warehouse_id'],
            true
        );
    }
}

<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\base\InvalidConfigException;
use yii\db\Migration;

/**
 * Initializes RBAC tables.
 *
 * @author Alexander Kochetov <creocoder@gmail.com>
 * @since 2.0
 */
class m220222_171717_new_rbac_init extends Migration
{
    use TraceableColumnTrait, DropTableTrait;

    public $itemTable = '{{%new_auth_item}}';

    public $itemChildTable = '{{%new_auth_item_child}}';

    public $assignmentTable = '{{%new_auth_assignment}}';

    public $ruleTable = '{{%new_auth_rule}}';

    public $traceUpdate = true;

    /**
     * @return bool|void|null
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->ruleTable, [
            'rule_id' => $this->bigPrimaryKey(),
            'name' => $this->string(64)->notNull()->unique(),
            'data' => $this->binary(),
        ]);

        $this->createTable($this->itemTable, [
            'item_id' => $this->bigPrimaryKey(),
            'name' => $this->string(64)->notNull()->unique(),
            'type' => $this->smallInteger()->notNull(),
            'description' => $this->text(),
            'rule_name' => $this->string(64),
            'data' => $this->binary(),
        ]);
        $this->createIndex('idx_type', $this->itemTable, 'type');
        $this->addForeignKey('fk_rule_name',
            $this->itemTable, 'rule_name',
            $this->ruleTable, 'name',
            'SET NULL', 'CASCADE');

        $this->traceUpdate = false;
        $this->createTable($this->itemChildTable, [
            'item_child_id' => $this->bigPrimaryKey(),
            'parent' => $this->string(64)->notNull(),
            'child' => $this->string(64)->notNull(),
        ]);
        $this->createIndex('uk_parent_child', $this->itemChildTable, ['parent', 'child'], true);
        $this->addForeignKey('fk_parent',
            $this->itemChildTable, 'parent',
            $this->itemTable, 'name',
            'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_child',
            $this->itemChildTable, 'child',
            $this->itemTable, 'name',
            'CASCADE', 'CASCADE');

        $this->createTable($this->assignmentTable, [
            'assignment_id' => $this->bigPrimaryKey(),
            'item_name' => $this->string(64)->notNull(),
            'user_id' => $this->string(64)->notNull(),
        ]);
        $this->createIndex('idx_user_id', $this->assignmentTable, 'user_id');
        $this->addForeignKey('fk_item_name',
            $this->assignmentTable, 'item_name',
            $this->itemTable, 'name',
            'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable($this->assignmentTable);
        $this->dropTable($this->itemChildTable);
        $this->dropTable($this->itemTable);
        $this->dropTable($this->ruleTable);
    }
}

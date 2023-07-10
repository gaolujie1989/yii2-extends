<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

use lujie\extend\db\Migration;
use yii\log\DbTarget;

/**
 * Class m201106_185632_log_init
 * @package lujie\extend\migrations
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class m201106_185632_log_init extends Migration
{
    public $traceBy = false;
    public $traceUpdate = false;
    public $traceCreate = false;

    private $dbTargets = [];

    /**
     * @return DbTarget[]
     */
    protected function getDbTargets(): array
    {
        if (empty($this->dbTargets)) {
            $log = Yii::$app->getLog();

            $usedTargets = [];
            foreach ($log->targets as $target) {
                if ($target instanceof DbTarget) {
                    $currentTarget = [
                        $target->db,
                        $target->logTable,
                    ];
                    if (!in_array($currentTarget, $usedTargets, true)) {
                        // do not create same table twice
                        $usedTargets[] = $currentTarget;
                        $this->dbTargets[] = $target;
                    }
                }
            }
        }
        return $this->dbTargets;
    }

    /**
     * @inheritdoc
     */
    public function safeUp(): void
    {
        foreach ($this->getDbTargets() as $target) {
            $this->db = $target->db;

            $this->createTable($target->logTable, [
                'id' => $this->bigPrimaryKey(),
                'level' => $this->tinyInteger()->notNull()->defaultValue(0),
                'category' => $this->string(100)->notNull()->defaultValue(''),
                'log_at' => $this->double()->notNull()->defaultValue(0),
                'duration' => $this->double()->notNull()->defaultValue(0),
                'prefix' => $this->string(100),
                'message' => $this->text(),
                'summary' => $this->string(200),
                'memory_usage' => $this->bigInteger()->notNull()->defaultValue(0),
                'memory_diff' => $this->bigInteger()->notNull()->defaultValue(0),
            ]);

            $this->createIndex('idx_log_level', $target->logTable, 'level');
            $this->createIndex('idx_log_category', $target->logTable, 'category');
            $this->createIndex('idx_log_at', $target->logTable, 'log_at');
        }
    }

    public function safeDown(): void
    {
        foreach ($this->getDbTargets() as $target) {
            $this->db = $target->db;

            $this->dropTable($target->logTable);
        }
    }
}

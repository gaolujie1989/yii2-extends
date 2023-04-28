<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

/**
 * Class ActiveRecord
 * @package lujie\extend\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Migration extends \yii\db\Migration
{
    use DropTableTrait, TraceableColumnTrait;

    public $tableName;
    public $traceCreate = true;
    public $traceUpdate = true;
    public $traceBy = true;
    public $version = false;

    /**
     * @param string $table
     * @param array $columns
     * @param string|null $options
     * @inheritdoc
     */
    public function createTable($table, $columns, $options = null): void
    {
        if ($this->db->driverName === 'mysql' && $options === null) {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $options = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $tableColumns = [];
        $tableIndexes = [];
        foreach ($columns as $key => $column) {
            if (is_int($key)) {
                $tableIndexes[] = $column;
            } else {
                $tableColumns[$key] = $column;
            }
        }
        $tableColumns = array_merge($tableColumns, $this->getTraceableColumns());
        if ($this->version) {
            $tableColumns['version'] = $this->integer()->unsigned()->notNull()->defaultValue(0);
        }
        parent::createTable($table, array_merge($tableColumns, $tableIndexes), $options);
        $this->createTraceableIndexes($table);
    }
}
<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

use yii\db\Migration;

/**
 * @property string $tableName
 * @property bool $traceCreate
 * @property bool $traceUpdate
 * @property bool $traceBy
 *
 * Trait LogColumnMigrateTrait
 * @package lujie\extend\db
 */
trait TraceableColumnTrait
{
    /**
     * @return array
     * @inheritdoc
     */
    public function getDefaultTableColumns(): array
    {
        /** @var Migration $this */
        $columns = [
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'created_by' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'updated_by' => $this->integer()->unsigned()->notNull()->defaultValue(0),
        ];
        if (isset($this->traceCreate) && $this->traceCreate === false) {
            unset($columns['created_at'], $columns['created_by']);
        }
        if (isset($this->traceUpdate) && $this->traceUpdate === false) {
            unset($columns['updated_at'], $columns['updated_by']);
        }
        if (isset($this->traceBy) && $this->traceBy === false) {
            unset($columns['created_by'], $columns['updated_by']);
        }
        return $columns;
    }

    /**
     * @param string $table
     * @inheritdoc
     */
    public function createDefaultTableIndexes(string $table): void
    {
        $columns = $this->getDefaultTableColumns();
        /** @var Migration|TraceableColumnTrait $this */
        if (isset($columns['updated_at'])) {
            $this->createIndex('idx_updated_at', $table, 'updated_at');
        } elseif (isset($columns['created_at'])) {
            $this->createIndex('idx_created_at', $table, 'created_at');
        }
    }

    /**
     * @param string $table
     * @param array $columns
     * @param string|null $options
     * @inheritdoc
     */
    public function createTable($table, $columns, $options = null): void
    {
        /** @var Migration|TraceableColumnTrait $this */
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
        $tableColumns = array_merge($tableColumns, $this->getDefaultTableColumns());
        parent::createTable($table, array_merge($tableColumns, $tableIndexes), $options);
        $this->createDefaultTableIndexes($table);
    }
}

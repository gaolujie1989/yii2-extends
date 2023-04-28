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
    public function getTraceableColumns(): array
    {
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
    public function createTraceableIndexes(string $table): void
    {
        $columns = $this->getTraceableColumns();
        if (isset($columns['updated_at'])) {
            $this->createIndex('idx_updated_at', $table, 'updated_at');
        } elseif (isset($columns['created_at'])) {
            $this->createIndex('idx_created_at', $table, 'created_at');
        }
    }
}

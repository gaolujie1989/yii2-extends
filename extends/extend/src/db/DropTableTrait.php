<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

/**
 * Trait DropTableTrait
 *
 * @property string $tableName
 *
 * @package lujie\extend\db
 */
trait DropTableTrait
{
    /**
     * @return false|void|mixed
     * @inheritdoc
     */
    public function safeDown()
    {
        if ($this->tableName) {
            $this->dropTable($this->tableName);
        }
    }
}

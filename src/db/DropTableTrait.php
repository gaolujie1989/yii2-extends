<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

use \yii\db\Migration;

/**
 * Trait DropTableTrait
 *
 * @property string tableName
 *
 * @package lujie\extend\db
 */
trait DropTableTrait
{
    /**
     * @return bool|void
     * @inheritdoc
     */
    public function safeDown()
    {
        if ($this->tableName) {
            /** @var Migration|DropTableTrait $this */
            $this->dropTable($this->tableName);
        }
    }
}

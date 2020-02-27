<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;

use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\Connection;
use yii\db\Query;
use yii\di\Instance;

/**
 * Class DbDataLoader
 * @package lujie\data\loader
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DbDataLoader extends QueryDataLoader
{
    /**
     * @var Connection|mixed
     */
    public $db = 'db';

    /**
     * @var string
     */
    public $table;

    /**
     * @var ActiveRecord
     */
    public $modelClass;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        if ($this->modelClass) {
            $this->table = $this->modelClass::tableName();
            $this->db = $this->modelClass::getDb();
        }
        $this->db = Instance::ensure($this->db, Connection::class);
        $this->initUniqueKey();
        if (empty($this->query)) {
            $this->query = (new Query())->from($this->table);
        }
        parent::init();
    }

    /**
     * @inheritdoc
     */
    private function initUniqueKey(): void
    {
        if (empty($this->key)) {
            $tableSchema = $this->db->getTableSchema($this->table);
            if ($tableSchema) {
                $primaryKey = $tableSchema->primaryKey;
                if ($primaryKey) {
                    $this->key = reset($primaryKey);
                }
            }
        }
    }
}

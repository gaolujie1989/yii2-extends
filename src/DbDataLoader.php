<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;

use yii\base\InvalidConfigException;
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
     * @var Connection
     */
    public $db = 'db';

    /**
     * @var string
     */
    public $table;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        $this->db = Instance::ensure($this->db);
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

<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;

use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\db\BaseActiveRecord;
use yii\db\Connection;
use yii\db\QueryInterface;
use yii\di\Instance;

/**
 * Class QueryDataLoader
 * @package lujie\data\loader
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class QueryDataLoader extends BaseObject implements DataLoaderInterface
{
    /**
     * @var QueryInterface
     */
    public $query;

    /**
     * @var Connection
     */
    public $db;

    /**
     * @var string|int
     */
    public $uniqueKey;

    /**
     * @var array
     */
    public $condition = [];

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if (empty($this->uniqueKey)) {
            throw new InvalidConfigException('The "uniqueKey" property must be set.');
        }
        if (!($this->query instanceof QueryInterface)) {
            throw new InvalidConfigException('The "query" property must be instanceof QueryInterface.');
        }
        $this->query->andFilterWhere($this->condition);
        if ($this->db) {
            $this->db = Instance::ensure($this->db);
        }
    }

    /**
     * @param int|string $key
     * @return array|BaseActiveRecord|null
     * @inheritdoc
     */
    public function get($key)
    {
        $query = clone $this->query;
        return $query->andWhere([$this->uniqueKey => $key])->one($this->db);
    }

    /**
     * @return array|BaseActiveRecord[]
     * @inheritdoc
     */
    public function all(): ?array
    {
        return $this->query->all($this->db);
    }
}

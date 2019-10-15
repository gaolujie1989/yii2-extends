<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;

use yii\base\InvalidConfigException;
use yii\db\BaseActiveRecord;
use yii\db\Connection;
use yii\db\Query;
use yii\db\QueryInterface;
use yii\di\Instance;

/**
 * Class QueryDataLoader
 * @package lujie\data\loader
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class QueryDataLoader extends BaseDataLoader
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
     * @var string
     */
    public $key;

    /**
     * @var string
     */
    protected $indexBy;

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
        if (empty($this->key)) {
            throw new InvalidConfigException('The "key" property must be set.');
        }
        if (!($this->query instanceof QueryInterface)) {
            throw new InvalidConfigException('The "query" property must be instanceof QueryInterface.');
        }
        if ($this->indexBy) {
            $this->query->indexBy($this->indexBy);
        }
        if ($this->db) {
            $this->db = Instance::ensure($this->db);
        }
    }

    /**
     * @param string|null $column
     * @inheritdoc
     */
    public function setIndexBy(?string $column): void
    {
        $this->query->indexBy($column);
    }

    /**
     * @param int|string $key
     * @return array|BaseActiveRecord|null
     * @inheritdoc
     */
    public function get($key)
    {
        $query = clone $this->query;
        return $query->andFilterWhere($this->condition)
            ->andWhere([$this->key => $key])
            ->one($this->db);
    }

    /**
     * @param array $keys
     * @return array
     * @inheritdoc
     */
    public function multiGet(array $keys): array
    {
        $query = clone $this->query;
        return $query->andFilterWhere($this->condition)
            ->andWhere([$this->key => $keys])
            ->indexBy($this->key)
            ->all($this->db);
    }

    /**
     * @return array|BaseActiveRecord[]
     * @inheritdoc
     */
    public function all(): ?array
    {
        $query = clone $this->query;
        return $query->andFilterWhere($this->condition)->all($this->db);
    }

    /**
     * @return \Iterator
     * @inheritdoc
     */
    public function batch($batchSize = 100): \Iterator
    {
        /** @var Query $query */
        $query = clone $this->query;
        $query->andFilterWhere($this->condition)->batch($batchSize, $this->db);
    }
}

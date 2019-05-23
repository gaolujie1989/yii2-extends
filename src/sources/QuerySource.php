<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\sources;

use Iterator;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\db\Connection;
use yii\db\Query;
use yii\db\QueryInterface;
use yii\di\Instance;

/**
 * Class QuerySource
 * @package lujie\data\exchange\sources
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class QuerySource extends BaseObject implements BatchSourceInterface, ConditionSourceInterface
{
    /**
     * @var Connection
     */
    public $db;

    /**
     * @var QueryInterface|Query
     */
    public $query;

    /**
     * @var array
     */
    public $condition;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if (!($this->query instanceof QueryInterface)) {
            throw new InvalidConfigException('Query must be a query object');
        }
        $this->query->andFilterWhere($this->condition);
        if ($this->db) {
            $this->db = Instance::ensure($this->db);
        }
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function all(): array
    {
        return $this->query->all($this->db);
    }

    /**
     * @param int $batchSize
     * @return \Iterator
     * @inheritdoc
     */
    public function batch($batchSize = 100): Iterator
    {
        return $this->query->batch($batchSize);
    }

    /**
     * @param int $batchSize
     * @return \Iterator
     * @inheritdoc
     */
    public function each($batchSize = 100): Iterator
    {
        return $this->query->each($batchSize);
    }

    /**
     * @param $condition
     * @inheritdoc
     */
    public function setCondition($condition): void
    {
        $this->condition = $condition;
    }
}

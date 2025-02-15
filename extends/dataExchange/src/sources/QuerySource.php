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
use yii\di\Instance;

/**
 * Class QuerySource
 * @package lujie\data\exchange\sources
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class QuerySource extends BaseObject implements BatchSourceInterface, ConditionSourceInterface
{
    /**
     * @var Connection|null
     */
    public $db;

    /**
     * @var Query
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
        if (!($this->query instanceof Query)) {
            throw new InvalidConfigException('Query must be a query object');
        }
        if ($this->condition) {
            $this->query->andFilterWhere($this->condition);
        }
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
    public function batch(int $batchSize = 100): Iterator
    {
        return $this->query->batch($batchSize, $this->db);
    }

    /**
     * @param int $batchSize
     * @return \Iterator
     * @inheritdoc
     */
    public function each(int $batchSize = 100): Iterator
    {
        return $this->query->each($batchSize, $this->db);
    }

    /**
     * @return int
     * @inheritdoc
     */
    public function count(): int
    {
        return (int)$this->query->count();
    }

    /**
     * @param array $condition
     * @inheritdoc
     */
    public function setCondition(array $condition): void
    {
        $this->condition = $condition;
    }
}

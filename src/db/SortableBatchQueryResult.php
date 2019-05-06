<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\extend\db;

use yii\db\ActiveQuery;
use yii\db\BaseActiveRecord;

/**
 * Class SortableBatchQueryResult
 * @package lujie\extend\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SortableBatchQueryResult extends \yii\db\BatchQueryResult
{
    /**
     * @var mixed
     */
    protected $lastSortValue;

    /**
     * @var array
     */
    protected $sortCondition;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->initSort();
    }

    /**
     * @inheritdoc
     */
    public function initSort() : void
    {
        if ($this->query->orderBy) {
            foreach ($this->query->orderBy as $column => $sort) {
                $column = is_int($column) ? $sort : $column;
                $sort = is_int($column) ? SORT_ASC : $sort;
                $this->sortCondition = [$sort === SORT_ASC ? '>' : '<', $column, 0];
                break;
            }
        } else if ($this->query instanceof ActiveQuery) {
            /** @var BaseActiveRecord $modelClass */
            $modelClass = $this->query->modelClass;
            $column = reset($modelClass::primaryKey());
            if (empty($this->query->select) || in_array('*', $this->query->select) || in_array($column, $this->query->select)) {
                $this->sortCondition = ['>', $column, 0];
            }
        }
    }

    /**
     * @return array
     * @inheritdoc
     */
    protected function fetchData()
    {
        if ($this->sortCondition) {
            return $this->fetchSortData();
        } else {
            return parent::fetchData();
        }
    }

    /**
     * @return array
     * @inheritdoc
     */
    protected function fetchSortData(): array
    {
        $query = clone $this->query;
        if ($this->lastSortValue !== null) {
            $this->sortCondition[2] = $this->lastSortValue;
            $query->andWhere($this->sortCondition);
        }
        if ($results = $query->limit($this->batchSize)->all()) {
            $lastResult = end($results);
            $this->lastSortValue = $lastResult[$this->sortCondition[1]];
        }
        return $results;
    }

    /**
     * @inheritdoc
     */
    public function reset()
    {
        parent::reset();
        $this->lastSortValue = null;
    }
}

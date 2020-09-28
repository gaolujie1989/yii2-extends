<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\extend\db;

use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;
use yii\db\BaseActiveRecord;

/**
 * Class SortableBatchQueryResult
 *
 * @property ActiveQuery $query
 *
 * @package lujie\extend\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 * @deprecated
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
    public function init(): void
    {
        parent::init();
        $this->initSort();
    }

    /**
     * @inheritdoc
     */
    public function initSort(): void
    {
        $orderBy = $this->query->orderBy;
        if ($orderBy) {
            foreach ($orderBy as $column => $sort) {
                if (empty($this->query->select)
                    || in_array('*', $this->query->select, true)
                    || in_array($column, $this->query->select, true)) {
                    $column = is_int($column) ? $sort : $column;
                    $sort = is_int($column) ? SORT_ASC : $sort;
                    $this->sortCondition = [$sort === SORT_ASC ? '>=' : '<=', $column, 0];
                    break;
                }
            }
        } else if ($this->query instanceof ActiveQueryInterface) {
            /** @var BaseActiveRecord $modelClass */
            $modelClass = $this->query->modelClass;
            $column = $modelClass::primaryKey()[0];
            if (empty($this->query->select)
                || in_array('*', $this->query->select, true)
                || in_array($column, $this->query->select, true)) {
                $this->sortCondition = ['>', $column, 0];
            }
        }
    }

    /**
     * @return array
     * @inheritdoc
     */
    protected function fetchData(): array
    {
        if ($this->sortCondition) {
            return $this->fetchSortData();
        }
        return parent::fetchData();
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
    public function reset(): void
    {
        parent::reset();
        $this->lastSortValue = null;
    }
}

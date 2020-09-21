<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

use yii\base\InvalidCallException;
use yii\base\InvalidValueException;

/**
 * Class OffsetBatchQueryResult
 * @package lujie\extend\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OffsetBatchQueryResult extends \yii\db\BatchQueryResult
{
    /**
     * @var int
     */
    public $limit = 10;

    /**
     * @var int
     */
    private $_offset = 0;

    /**
     * @var array
     */
    private $_offsetData = [];

    /**
     * @var int
     */
    private $_queryCount;

    /**
     * @var int
     */
    private $_fetchCount;

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->_queryCount = $this->query->count();
    }

    /**
     * @return array
     * @inheritdoc
     */
    protected function fetchData(): array
    {
        return $this->fetchOffsetData();
    }

    /**
     * @inheritdoc
     */
    public function reset(): void
    {
        parent::reset();
        $this->_offset = 0;
    }

    /**
     * @return array
     */
    protected function fetchOffsetData(): array
    {
        if ($this->_offsetData) {
            return array_shift($this->_offsetData);
        }

        $offset = $this->_offset;
        $this->_offset += $this->limit;
        $offsetData = (clone $this->query)
            ->offset($this->batchSize * $offset)
            ->limit($this->batchSize * $this->limit)
            ->all();

        $offsetDataCount = count($offsetData);
        $this->_fetchCount += $offsetDataCount;
        if ($this->_fetchCount > $this->_queryCount
            || ($offsetDataCount === 0 && $this->_fetchCount < $this->_queryCount)) {
            throw new InvalidValueException("This query with offset batch is invalid, query count {$this->_queryCount} is not equal with real fetched count {$this->_fetchCount}.");
        }
        if ($offsetDataCount === 0) {
            return [];
        }

        if ($this->limit === 1) {
            return $offsetData;
        } else {
            $this->_offsetData = array_chunk($offsetData, $this->batchSize);
            return array_shift($this->_offsetData);
        }
    }
}
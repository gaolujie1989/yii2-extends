<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\db;

use Yii;
use yii\db\BatchQueryResult;

/**
 * Class OffsetBatchQueryResult
 * @package lujie\extend\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OffsetBatchQueryResult extends BatchQueryResult
{
    /**
     * @var int
     */
    public $limit = 10;

    /**
     * @var int
     */
    private $offset = 0;

    /**
     * @var array
     */
    private $offsetData = [];

    /**
     * @var int
     */
    private $queryCount;

    /**
     * @var int
     */
    private $fetchCount;

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->queryCount = $this->query->count();
        Yii::debug('Using OffsetBatchQueryResult, be sure that condition result and sort will not be changed in query', __METHOD__);
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
        $this->offset = 0;
    }

    /**
     * @return array
     */
    protected function fetchOffsetData(): array
    {
        if ($this->offsetData) {
            return array_shift($this->offsetData);
        }

        $offset = $this->offset;
        $this->offset += $this->limit;
        $offsetData = (clone $this->query)
            ->offset($this->batchSize * $offset)
            ->limit($this->batchSize * $this->limit)
            ->all($this->db);

        $offsetDataCount = count($offsetData);
        $this->fetchCount += $offsetDataCount;
        if ($this->fetchCount > $this->queryCount
            || ($offsetDataCount === 0 && $this->fetchCount < $this->queryCount)) {
            Yii::warning("OffsetBatchQueryResult query count {$this->queryCount} is not equal with real fetched count {$this->fetchCount}.", __METHOD__);
        }
        if ($offsetDataCount === 0) {
            return [];
        }

        if ($this->limit === 1) {
            return $offsetData;
        } else {
            $this->offsetData = array_chunk($offsetData, $this->batchSize);
            return array_shift($this->offsetData);
        }
    }
}

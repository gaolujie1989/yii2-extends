<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\sources;

use Iterator;

/**
 * Class InBatchIncrementSource
 * @package lujie\data\exchange\sources
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class InBatchIncrementSource extends IncrementSource
{
    /**
     * @var array
     */
    protected $lastCondition = [];

    /**
     * @return bool
     * @inheritdoc
     */
    public function ackReceived(): void
    {
        $this->incrementCondition = $this->generateCondition($this->lastCondition);
        $this->dataStorage->set($this->sourceKey, $this->incrementCondition);
    }

    /**
     * @param $condition
     * @return array
     * @inheritdoc
     */
    abstract protected function generateBatchConditions($condition): array;

    /**
     * @param int $batchSize
     * @return Iterator
     * @inheritdoc
     */
    public function batch($batchSize = 100): Iterator
    {
        $batchIncrementConditions = $this->generateBatchConditions($this->incrementCondition);
        foreach ($batchIncrementConditions as $condition) {
            $this->source->setCondition($condition);
            $items = iterator_to_array($this->source->each($batchSize));
            $this->lastRow = end($items);
            $this->lastCondition = $condition;
            yield $items;
        }
    }

    /**
     * @param int $batchSize
     * @return Iterator
     * @inheritdoc
     */
    public function each($batchSize = 100): Iterator
    {
        $batchIncrementConditions = $this->generateBatchConditions($this->incrementCondition);
        foreach ($batchIncrementConditions as $condition) {
            $this->source->setCondition($condition);
            $items = iterator_to_array($this->source->each($batchSize));
            while ($items) {
                $item = array_shift($items);
                $this->lastRow = $item;
                if (count($items) === 0) {
                    $this->lastCondition = $condition;
                }
                yield $item;
            }
        }
    }
}

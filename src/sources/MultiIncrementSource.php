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
abstract class MultiIncrementSource extends IncrementSource
{
    /**
     * @var array
     */
    protected $multiIncrementCondition = [];

    /**
     * @var array
     */
    protected $lastCondition = [];

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->multiIncrementCondition = $this->incrementCondition;
        $this->incrementCondition = [];
    }

    /**
     * @return bool
     * @inheritdoc
     */
    public function ackReceived(): void
    {
        $this->multiIncrementCondition = $this->generateIncrementCondition();
        $this->dataStorage->set($this->sourceKey, $this->multiIncrementCondition);
    }

    /**
     * @param $condition
     * @return array
     * @inheritdoc
     */
    abstract protected function generateMultiConditions($condition): array;

    /**
     * @param int $batchSize
     * @return Iterator
     * @inheritdoc
     */
    public function batch($batchSize = 100): Iterator
    {
        $multiIncrementConditions = $this->generateMultiConditions($this->incrementCondition);
        foreach ($multiIncrementConditions as $condition) {
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
        $multiIncrementConditions = $this->generateMultiConditions($this->incrementCondition);
        foreach ($multiIncrementConditions as $condition) {
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

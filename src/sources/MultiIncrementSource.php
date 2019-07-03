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
     * @var string
     */
    protected $multiKey;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->multiIncrementCondition = $this->incrementCondition ?: $this->multiIncrementCondition;
        $this->incrementCondition = [];
    }

    /**
     * @return bool
     * @inheritdoc
     */
    public function ackReceived(): void
    {
        $condition = $this->generateIncrementCondition();
        $this->multiIncrementCondition[$condition[$this->multiKey]] = $condition;
        $this->dataStorage->set($this->sourceKey, $this->multiIncrementCondition);
    }

    /**
     * @param $condition
     * @return array
     * @inheritdoc
     */
    abstract protected function getMultiConditions(): array;

    /**
     * @param int $batchSize
     * @return Iterator
     * @inheritdoc
     */
    public function batch($batchSize = 100): Iterator
    {
        $multiIncrementConditions = $this->getMultiConditions();
        foreach ($multiIncrementConditions as $condition) {
            $this->incrementCondition = $condition;
            $iterator = parent::batch($batchSize);
            yield from $iterator;
        }
    }

    /**
     * @param int $batchSize
     * @return Iterator
     * @inheritdoc
     */
    public function each($batchSize = 100): Iterator
    {
        $multiIncrementConditions = $this->getMultiConditions();
        foreach ($multiIncrementConditions as $condition) {
            $this->incrementCondition = $condition;
            $iterator = parent::each($batchSize);
            yield from $iterator;
        }
    }
}

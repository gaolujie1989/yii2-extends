<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\sources;

use Iterator;
use yii\base\InvalidConfigException;

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
        $this->multiIncrementCondition = $this->incrementCondition;
        $this->incrementCondition = [];
        if (empty($this->multiKey)) {
            throw new InvalidConfigException('The property `multiKey` must be set');
        }
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
            //@TODO
            //yield from another yield from will cause problems. unknown reason
            //so change it to foreach
            foreach ($iterator as $item) {
                yield $item;
            }
//            yield from $iterator;
        }
    }
}

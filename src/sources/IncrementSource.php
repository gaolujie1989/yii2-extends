<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\sources;

use Iterator;
use lujie\data\storage\DataStorageInterface;
use yii\base\BaseObject;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use yii\di\Instance;

/**
 * Class IncrementSource
 * @package lujie\data\exchange\sources
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class IncrementSource extends BaseObject implements BatchSourceInterface, ConditionSourceInterface
{
    /**
     * @var BatchSourceInterface|ConditionSourceInterface
     */
    public $source;

    /**
     * ex. xxxSource4xxx must be unique
     * @var string
     */
    public $sourceKey;

    /**
     * @var DataStorageInterface
     */
    public $dataStorage;

    /**
     * @var array
     */
    public $incrementCondition = [];

    /**
     * @var array
     */
    protected $lastRow;

    /**
     * @var array
     */
    protected $lastCondition;

    /**
     * @var bool
     */
    protected $sortable = false;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->source = Instance::ensure($this->source, BatchSourceInterface::class);
        $this->dataStorage = Instance::ensure($this->dataStorage, DataStorageInterface::class);
        if (empty($this->sourceKey)) {
            throw new InvalidConfigException('The "sourceKey" property must be set');
        }
        $this->incrementCondition = $this->dataStorage->get($this->sourceKey) ?: $this->incrementCondition;
    }

    /**
     * @return bool
     * @inheritdoc
     */
    public function ackReceived(): void
    {
        $this->incrementCondition = $this->generateIncrementCondition();
        $this->dataStorage->set($this->sourceKey, $this->incrementCondition);
    }

    /**
     * @param $data
     * @return array
     * @inheritdoc
     */
    abstract protected function generateIncrementCondition(): array;

    /**
     * @param $condition
     * @return array
     * @inheritdoc
     */
    protected function generateBatchConditions($condition): array
    {
        if ($this->sortable) {
            return [$condition];
        }
        throw new InvalidCallException('Generate batch condition must be rewrite for unSortable source');
    }

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
            $iterator = $this->source->batch($batchSize);

            if ($this->sortable) {
                $this->lastCondition = $condition;
                foreach ($iterator as $items) {
                    $this->lastRow = end($items);
                    yield $items;
                }
            } else {
                $items = iterator_to_array($this->source->each($batchSize));
                $this->lastRow = end($items);
                $this->lastCondition = $condition;
                yield $items;
            }
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
            $iterator = $this->source->each($batchSize);

            if ($this->sortable) {
                $this->lastCondition = $condition;
                foreach ($iterator as $item) {
                    $this->lastRow = $item;
                    yield $item;
                }
            } else {
                $items = iterator_to_array($this->source->each($batchSize));
                $this->lastRow = end($items);
                if (count($items) === 0) {
                    $this->lastCondition = $condition;
                }
                yield from $items;
            }
        }
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function all(): array
    {
        $this->source->setCondition($this->incrementCondition);
        $all = $this->source->all();
        $this->lastRow = end($all);
        return $all;
    }

    /**
     * @param $condition
     * @inheritdoc
     */
    public function setCondition($condition): void
    {
        $this->source->setCondition($condition);
    }
}

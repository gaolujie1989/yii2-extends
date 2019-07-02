<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\sources;

use Iterator;
use lujie\data\storage\DataStorageInterface;
use yii\base\BaseObject;
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
    protected $incrementCondition = [];

    /**
     * @var array
     */
    protected $lastRow;

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
        $this->incrementCondition = $this->dataStorage->get($this->sourceKey) ?: [];
    }

    /**
     * @return bool
     * @inheritdoc
     */
    public function ackReceived(): void
    {
        $this->incrementCondition = $this->generateCondition($this->lastRow);
        $this->dataStorage->set($this->sourceKey, $this->incrementCondition);
    }

    /**
     * @param $data
     * @return array
     * @inheritdoc
     */
    abstract protected function generateCondition($data): array;

    /**
     * @param int $batchSize
     * @return Iterator
     * @inheritdoc
     */
    public function batch($batchSize = 100): Iterator
    {
        $this->source->setCondition($this->incrementCondition);
        $iterator = $this->source->batch($batchSize);
        foreach ($iterator as $items) {
            $this->lastRow = end($items);
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
        $this->source->setCondition($this->incrementCondition);
        $iterator = $this->source->each($batchSize);
        foreach ($iterator as $item) {
            $this->lastRow = $item;
            yield $item;
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

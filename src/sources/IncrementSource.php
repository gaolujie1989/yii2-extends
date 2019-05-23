<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\sources;

use Iterator;
use yii\base\BaseObject;
use yii\di\Instance;

/**
 * Class IncrementSource
 * @package lujie\data\exchange\sources
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class IncrementSource extends BaseObject implements BatchSourceInterface
{
    /**
     * @var BatchSourceInterface|ConditionSourceInterface
     */
    public $source;

    public $sourceKey;

    public $sourceCondition;

    public $storage;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->source = Instance::ensure($this->source, BatchSourceInterface::class);
    }

    /**
     * @param int $batchSize
     * @return Iterator
     * @inheritdoc
     */
    public function batch($batchSize = 100): Iterator
    {
        $this->source->setCondition($this->getSourceCondition());
        $this->source->batch($batchSize);
    }

    /**
     * @param int $batchSize
     * @return Iterator
     * @inheritdoc
     */
    public function each($batchSize = 100): Iterator
    {
        $this->source->setCondition($this->getSourceCondition());
        return $this->source->each($batchSize);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function all(): array
    {
        $this->source->setCondition($this->getSourceCondition());
        return $this->source->all();
    }

    /**
     * @param array $row
     * @inheritdoc
     */
    public function ackLastRow(array $row)
    {

    }

    /**
     * @inheritdoc
     */
    public function getSourceCondition()
    {

    }
}

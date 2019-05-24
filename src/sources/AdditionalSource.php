<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\center\sources;

use Iterator;
use lujie\data\exchange\sources\BatchSourceInterface;
use lujie\data\exchange\sources\ConditionSourceInterface;
use yii\base\BaseObject;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * Class AdditionalSource
 * @package lujie\data\center\sources
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AdditionalSource extends BaseObject implements BatchSourceInterface, ConditionSourceInterface
{
    /**
     * @var BatchSourceInterface|ConditionSourceInterface
     */
    public $source;

    /**
     * @var array
     */
    public $additionalData = [];

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
        $iterator = $this->source->batch($batchSize);
        foreach ($iterator as $items) {
            $items = array_map(function ($item) {
                return ArrayHelper::merge($this->additionalData, $item);
            }, $items);
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
        $iterator = $this->source->each($batchSize);
        foreach ($iterator as $item) {
            yield ArrayHelper::merge($this->additionalData, $item);
        }
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function all(): array
    {
        return $this->source->all();
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

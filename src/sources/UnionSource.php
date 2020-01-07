<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\sources;

use Iterator;
use yii\base\BaseObject;
use yii\di\Instance;

/**
 * Class UnionSource
 * @package lujie\data\exchange\sources
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UnionSource extends BaseObject implements SourceInterface, BatchSourceInterface
{
    /**
     * @var SourceInterface[]
     */
    public $sources = [];

    /**
     * @var SourceInterface
     */
    public $currentSource;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        foreach ($this->sources as $key => $source) {
            $this->sources[$key] = Instance::ensure($source, SourceInterface::class);
        }
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function all(): array
    {
        $sourceAll = [];
        foreach ($this->sources as $source) {
            $sourceAll[] = $source->all();
        }
        return array_merge(...$sourceAll);
    }

    /**
     * @param int $batchSize
     * @return Iterator
     * @inheritdoc
     */
    public function batch(int $batchSize = 100): Iterator
    {
        foreach ($this->sources as $source) {
            $this->currentSource = $source;
            if ($source instanceof BatchSourceInterface) {
                foreach ($source->batch() as $batch) {
                    yield $batch;
                }
            } else {
                $all = $source->all();
                yield from array_chunk($all, $batchSize);
            }
        }
    }

    /**
     * @param int $batchSize
     * @return Iterator
     * @inheritdoc
     */
    public function each(int $batchSize = 100): Iterator
    {
        $iterator = $this->batch($batchSize);
        foreach ($iterator as $items) {
            yield from $items;
        }
    }
}

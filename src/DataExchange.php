<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange;

use lujie\data\exchange\pipelines\ActiveRecordPipeline;
use lujie\data\exchange\pipelines\DbPipelineInterface;
use lujie\data\exchange\pipelines\PipelineInterface;
use lujie\data\exchange\sources\IncrementSource;
use lujie\data\exchange\sources\SourceInterface;
use lujie\data\exchange\transformers\TransformerInterface;
use lujie\executing\ExecutableInterface;
use lujie\executing\ExecutableTrait;
use lujie\executing\LockableInterface;
use lujie\executing\LockableTrait;
use lujie\executing\QueueableInterface;
use lujie\executing\QueueableTrait;
use yii\base\BaseObject;
use yii\di\Instance;

/**
 * Class Exchanger
 * @package lujie\data\exchange
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DataExchange extends BaseObject implements ExecutableInterface, LockableInterface, QueueableInterface
{
    use ExecutableTrait, LockableTrait, QueueableTrait;

    /**
     * @var SourceInterface
     */
    public $source;

    /**
     * @var TransformerInterface
     */
    public $transformer;

    /**
     * @var PipelineInterface
     */
    public $pipeline;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if ($this->source) {
            $this->source = Instance::ensure($this->source, SourceInterface::class);
        }
        if ($this->transformer) {
            $this->transformer = Instance::ensure($this->transformer, TransformerInterface::class);
        }
        $this->pipeline = Instance::ensure($this->pipeline, PipelineInterface::class);
    }

    /**
     * @return bool
     * @inheritdoc
     */
    public function execute(): bool
    {
        $source = $this->source;
        if ($source === null) {
            return false;
        }
        $data = $source instanceof IncrementSource ? $source->each() : $source->all();
        if ($this->transformer) {
            $data = $this->transformer->transform($data);
        }
        return $this->pipeline->process($data);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function getAffectedRowCounts(): array
    {
        if ($this->pipeline instanceof DbPipelineInterface) {
            return $this->pipeline->getAffectedRowCounts();
        }
        return [];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function getErrors(): array
    {
        if ($this->pipeline instanceof ActiveRecordPipeline) {
            return $this->pipeline->getErrors();
        }
        return [];
    }
}

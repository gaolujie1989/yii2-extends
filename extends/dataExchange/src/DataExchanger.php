<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange;

use lujie\data\exchange\pipelines\ActiveRecordPipeline;
use lujie\data\exchange\pipelines\DbPipelineInterface;
use lujie\data\exchange\pipelines\FilePipeline;
use lujie\data\exchange\pipelines\PipelineInterface;
use lujie\data\exchange\sources\BatchSourceInterface;
use lujie\data\exchange\sources\SourceInterface;
use lujie\data\exchange\transformers\TransformerInterface;
use lujie\executing\ExecutableInterface;
use lujie\executing\ExecutableTrait;
use lujie\executing\LockableInterface;
use lujie\executing\LockableTrait;
use lujie\executing\ProgressInterface;
use lujie\executing\ProgressTrait;
use lujie\executing\QueueableInterface;
use lujie\executing\QueueableTrait;
use Yii;
use yii\base\BaseObject;
use yii\di\Instance;

/**
 * Class Exchanger
 * @package lujie\data\exchange
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DataExchanger extends BaseObject implements ExecutableInterface, LockableInterface, QueueableInterface, ProgressInterface
{
    use ExecutableTrait, LockableTrait, QueueableTrait, ProgressTrait;

    /**
     * @var SourceInterface|array|null
     */
    public $source;

    /**
     * @var TransformerInterface|array|string|null
     */
    public $transformer;

    /**
     * @var PipelineInterface|array
     */
    public $pipeline;

    /**
     * @var bool
     */
    public $useProgress = false;

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
     * @return bool|\Generator|void
     * @inheritdoc
     */
    public function execute()
    {
        $source = $this->source;
        if ($source === null) {
            Yii::warning('Null source', __METHOD__);
            return false;
        }
        $result = $this->executeInternal();
        if ($this->useProgress) {
            return $result;
        }
        iterator_to_array($result, false);
        return $result->getReturn();
    }

    /**
     * @return \Generator
     * @inheritdoc
     */
    protected function executeInternal(): \Generator
    {
        $source = $this->source;
        $batch = $source instanceof BatchSourceInterface && !($this->pipeline instanceof FilePipeline)
            ? $source->batch() : [$source->all()];
        if ($this->useProgress) {
            $progress = $this->getProgress($source->count());
        }
        foreach ($batch as $data) {
            if (empty($data)) {
                Yii::info('Empty source data', __METHOD__);
                continue;
            }
            if (!$this->exchange($data)) {
                Yii::warning('Exchange data failed', __METHOD__);
                return false;
            }
            if (isset($progress)) {
                yield $progress->done += count($data);
                if ($progress->break) {
                    break;
                }
            }
        }
        return true;
    }

    /**
     * @param array $data
     * @return bool
     * @inheritdoc
     */
    public function exchange(array $data): bool
    {
        if (empty($data)) {
            return true;
        }
        if ($this->transformer) {
            $data = $this->transformer->transform($data);
        }
        if ($this instanceof TransformerInterface) {
            $data = $this->transform($data);
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

    /**
     * @return string
     * @inheritdoc
     */
    public function getFilePath(): string
    {
        if ($this->pipeline instanceof FilePipeline) {
            return $this->pipeline->getFilePath();
        }
        return '';
    }
}

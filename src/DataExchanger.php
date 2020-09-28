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
class DataExchanger extends BaseObject implements ExecutableInterface, LockableInterface, QueueableInterface
{
    use ExecutableTrait, LockableTrait, QueueableTrait;

    /**
     * @var ?SourceInterface
     */
    public $source;

    /**
     * @var ?TransformerInterface
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
            Yii::warning('Null source', __METHOD__);
            return false;
        }
        $batch = $source instanceof BatchSourceInterface && !($this->pipeline instanceof FilePipeline)
            ? $source->batch() : [$source->all()];
        foreach ($batch as $data) {
            if (empty($data)) {
                Yii::info('Empty source data', __METHOD__);
                continue;
            }
            if (!$this->exchange($data)) {
                Yii::warning('Exchange data failed', __METHOD__);
                return false;
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

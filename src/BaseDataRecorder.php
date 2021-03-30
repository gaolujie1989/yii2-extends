<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording;

use lujie\data\exchange\DataExchanger;
use lujie\data\exchange\pipelines\CombinedPipeline;
use lujie\data\exchange\pipelines\DbPipelineInterface;
use lujie\data\exchange\pipelines\PipelineInterface;
use lujie\data\exchange\sources\SourceInterface;
use lujie\data\exchange\transformers\TransformerInterface;
use lujie\data\recording\models\DataSource;
use lujie\data\recording\pipelines\ActiveRecordRecordDataPipeline;
use lujie\data\recording\pipelines\RecordPipeline;
use lujie\data\recording\transformers\RecordTransformer;
use lujie\extend\helpers\ExecuteHelper;

/**
 * Class DataRecorder
 * @package lujie\data\recording
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseDataRecorder extends DataExchanger
{
    /**
     * @var TransformerInterface
     */
    public $transformer = [
        'class' => RecordTransformer::class,
    ];

    /**
     * @var PipelineInterface|CombinedPipeline
     */
    public $pipeline = [
        'class' => ActiveRecordRecordDataPipeline::class
    ];

    /**
     * @var DataSource
     */
    protected $dataSource;

    abstract protected function createSource(DataSource $dataSource): SourceInterface;

    /**
     * @param DataSource $dataSource
     * @inheritdoc
     */
    public function prepare(DataSource $dataSource): void
    {
        $this->dataSource = $dataSource;
        if ($this->transformer instanceof RecordTransformer) {
            $this->transformer->dataSource = $dataSource;
        }
        if ($this->pipeline instanceof RecordPipeline) {
            $this->pipeline->dataSource = $dataSource;
        }
        $this->source = $this->createSource($dataSource);
        $this->id = $dataSource->data_source_id;
    }

    /**
     * @return bool
     * @throws \Throwable
     * @inheritdoc
     */
    public function execute(): bool
    {
        $dataSource = $this->dataSource;
        return ExecuteHelper::execute(function () use ($dataSource) {
            parent::execute();
            if ($this->pipeline instanceof DbPipelineInterface) {
                $dataSource->last_exec_result = $this->pipeline->getAffectedRowCounts();
            } elseif ($this->pipeline instanceof CombinedPipeline) {
                foreach ($this->pipeline->pipelines as $pipeline) {
                    if ($pipeline instanceof DbPipelineInterface) {
                        $dataSource->last_exec_result = $pipeline->getAffectedRowCounts();
                        break;
                    }
                }
            }
            return true;
        }, $dataSource, 'last_exec_at', 'last_exec_status', 'last_exec_result');
    }
}

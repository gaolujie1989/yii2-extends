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
use lujie\extend\constants\ExecStatusConst;
use lujie\extend\helpers\ComponentHelper;
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
     * @var int the probability (parts per million) that clean the expired exec records
     * when log success record. Defaults to 10, meaning 0.1% chance.
     * This number should be between 0 and 10000. A value 0 means no clean will be performed at all.
     */
    public $cleanProbability = 10;

    /**
     * @var array
     */
    public $timeToClean = [
        ExecStatusConst::EXEC_STATUS_RUNNING => '-70 days',
        ExecStatusConst::EXEC_STATUS_SUCCESS => '-30 day',
        ExecStatusConst::EXEC_STATUS_FAILED => '-70 days',
        ExecStatusConst::EXEC_STATUS_SKIPPED => '-30 day',
        ExecStatusConst::EXEC_STATUS_QUEUED => '-70 days',
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
        $this->cleanSources();
        $dataSource = $this->dataSource;
        return ExecuteHelper::execute(function () use ($dataSource) {
            parent::execute();
            if ($this->pipeline instanceof CombinedPipeline) {
                foreach ($this->pipeline->pipelines as $pipeline) {
                    if ($pipeline instanceof DbPipelineInterface) {
                        $dataSource->last_exec_result = $pipeline->getAffectedRowCounts();
                        break;
                    }
                }
            } else if ($this->pipeline instanceof DbPipelineInterface) {
                $dataSource->last_exec_result = $this->pipeline->getAffectedRowCounts();
            }
            return true;
        }, $dataSource, 'last_exec_at', 'last_exec_status', 'last_exec_result');
    }

    /**
     * @param bool $force
     * @throws \Exception
     * @inheritdoc
     */
    public function cleanSources(bool $force = false): void
    {
        if ($force || random_int(0, 10000) < $this->cleanProbability) {
            $condition = ['OR'];
            foreach ($this->timeToClean as $status => $expire) {
                $condition[] = ['AND', ['last_exec_status' => $status], ['<', 'created_at', strtotime($expire)]];
            }
            $this->deleteSources($condition);
        }
    }

    /**
     * @param array $condition
     * @inheritdoc
     */
    protected function deleteSources(array $condition): void
    {
        DataSource::deleteAll($condition);
    }
}

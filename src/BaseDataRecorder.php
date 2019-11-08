<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording;

use lujie\data\exchange\DataExchanger;
use lujie\data\exchange\pipelines\CombinedPipeline;
use lujie\data\exchange\pipelines\DbPipelineInterface;
use lujie\data\exchange\sources\SourceInterface;
use lujie\data\recording\models\DataSource;
use lujie\data\recording\pipelines\ActiveRecordRecordDataPipeline;
use lujie\data\recording\pipelines\DataRecordPipeline;
use lujie\data\recording\transformers\RecordTransformer;
use lujie\extend\constants\ExecStatusConst;

/**
 * Class DataRecorder
 * @package lujie\data\recording
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseDataRecorder extends DataExchanger
{
    /**
     * @var array
     */
    public $transformer = [
        'class' => RecordTransformer::class,
    ];

    /**
     * @var array
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
        if ($this->pipeline instanceof DataRecordPipeline) {
            $this->pipeline->dataSource = $dataSource;
        }
        $this->source = $this->createSource($dataSource);
        $this->id = $dataSource->data_source_id;
    }

    /**
     * @return bool
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\NotSupportedException
     * @inheritdoc
     */
    public function execute(): bool
    {
        $dataSource = $this->dataSource;
        $dataSource->last_exec_at = time();
        $dataSource->last_exec_status = ExecStatusConst::EXEC_STATUS_RUNNING;
        $dataSource->save(false);

        try {
            $execute = parent::execute();
            $dataSource->last_exec_status = ExecStatusConst::EXEC_STATUS_SUCCESS;
            if ($this->pipeline instanceof DbPipelineInterface) {
                $dataSource->last_exec_result = $this->pipeline->getAffectedRowCounts();
            } else if ($this->pipeline instanceof CombinedPipeline) {
                foreach ($this->pipeline->pipelines as $pipeline) {
                    if ($pipeline instanceof DbPipelineInterface) {
                        $dataSource->last_exec_result = $pipeline->getAffectedRowCounts();
                        break;
                    }
                }
            }
            $dataSource->save(false);
            return $execute;
        } catch (\Throwable $e) {
            $dataSource->last_exec_status = ExecStatusConst::EXEC_STATUS_FAILED;
            $dataSource->last_exec_result = ['error' => mb_substr($e->getMessage(), 0, 1000)];
            $dataSource->save(false);
        }
    }
}

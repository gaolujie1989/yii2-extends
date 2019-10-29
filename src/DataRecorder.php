<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording;

use lujie\data\exchange\DataExchanger;
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
abstract class DataRecorder extends DataExchanger
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
     * @param DataSource $dataSource
     * @return bool
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\NotSupportedException
     * @inheritdoc
     */
    public function pull(DataSource $dataSource): bool
    {
        $this->prepare($dataSource);
        return $this->execute();
    }

    abstract protected function createSource(DataSource $dataSource): SourceInterface;

    /**
     * @param DataSource $dataSource
     * @inheritdoc
     */
    public function prepare(DataSource $dataSource): void
    {
        if ($this->pipeline instanceof DataRecordPipeline) {
            $this->pipeline->dataSource = $dataSource;
        }
        if ($this->transformer instanceof RecordTransformer) {
            $this->transformer->dataSource = $dataSource;
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
        if ($this->pipeline instanceof DataRecordPipeline) {
            $dataSource = $this->pipeline->dataSource;
            $dataSource->last_exec_at = time();
            $dataSource->last_exec_status = ExecStatusConst::EXEC_STATUS_RUNNING;
            $dataSource->save(false);

            try {
                $execute = parent::execute();
                $dataSource->last_exec_status = ExecStatusConst::EXEC_STATUS_SUCCESS;
                $dataSource->last_exec_result = $this->pipeline->getAffectedRowCounts();
                $dataSource->save(false);
                return $execute;
            } catch (\Throwable $e) {
                $dataSource->last_exec_status = ExecStatusConst::EXEC_STATUS_FAILED;
                $dataSource->last_exec_result = ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()];
                $dataSource->save(false);
            }
        }
        return parent::execute();
    }
}

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
        $this->source = $this->createSource($dataSource);
        $this->id = $dataSource->data_source_id;
    }

    /**
     * @param array $data
     * @return bool
     * @inheritdoc
     */
    public function exchange(array $data): bool
    {
        if ($this->pipeline instanceof DataRecordPipeline) {
            $dataSource = $this->pipeline->dataSource;
            $dataSource->last_exec_at = time();
            $dataSource->last_exec_status = DataSource::EXEC_STATUS_RUNNING;
            $dataSource->save(false);

            $isSuccess = parent::exchange($data);

            $dataSource->last_exec_status = $isSuccess ? DataSource::EXEC_STATUS_SUCCESS : DataSource::EXEC_STATUS_FAILED;
            $dataSource->last_exec_result = $this->pipeline->getAffectedRowCounts();
            $dataSource->save(false);

            return $isSuccess;
        }
        return parent::exchange($data);
    }
}

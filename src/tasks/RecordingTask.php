<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\tasks;

use lujie\data\recording\jobs\RecordingJob;
use lujie\data\recording\models\DataSource;
use lujie\extend\constants\ExecStatusConst;
use lujie\extend\helpers\ExecuteHelper;
use lujie\scheduling\CronTask;
use yii\di\Instance;
use yii\queue\Queue;

/**
 * Class GenerateSourceTask
 * @package kiwi\data\recording\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RecordingTask extends CronTask
{
    /**
     * @var Queue
     */
    public $queue = 'queue';

    /**
     * @var array
     */
    public $jobConfig = [];

    /**
     * @var array
     */
    public $execStatus = [
        ExecStatusConst::EXEC_STATUS_PENDING,
        ExecStatusConst::EXEC_STATUS_FAILED,
        ExecStatusConst::EXEC_STATUS_QUEUED
    ];

    /**
     * @return bool
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function execute(): bool
    {
        $this->queue = Instance::ensure($this->queue, Queue::class);
        $dataSources = DataSource::find()->active()->execStatus($this->execStatus)->each();
        foreach ($dataSources as $dataSource) {
            /** @var RecordingJob $job */
            $job = Instance::ensure($this->jobConfig, RecordingJob::class);
            $job->dataSourceId = $dataSource->data_source_id;
            ExecuteHelper::pushJob($this->queue, $job, $dataSource,
                'last_exec_status', 'last_exec_result');
        }
        return true;
    }
}

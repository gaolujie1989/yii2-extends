<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\tasks;

use lujie\data\recording\jobs\RecordingJob;
use lujie\data\recording\models\DataSource;
use lujie\extend\constants\ExecStatusConst;
use lujie\extend\helpers\ObjectHelper;
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
     * @return bool
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function execute(): bool
    {
        $this->queue = Instance::ensure($this->queue, Queue::class);
        /** @var DataSource[] $eachSource */
        $eachSource = DataSource::find()->active()->pending()->each();
        foreach ($eachSource as $dataSource) {
            /** @var RecordingJob $job */
            $job = ObjectHelper::create($this->jobConfig, RecordingJob::class);
            $job->dataSourceId = $dataSource->data_source_id;
            if ($this->queue->push($job)) {
                $dataSource->last_exec_status = ExecStatusConst::EXEC_STATUS_QUEUED;
                $dataSource->save(false);
            }
        }
        return true;
    }
}

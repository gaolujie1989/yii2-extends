<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\queuing\monitor\behaviors;

use lujie\extend\constants\ExecStatusConst;
use lujie\queuing\monitor\models\QueueJob;
use lujie\queuing\monitor\models\QueueJobExec;
use Yii;

/**
 * Class ActiveRecordJobMonitorBehavior
 * @package lujie\queuing\monitor
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveRecordJobMonitorBehavior extends BaseJobMonitorBehavior
{
    /**
     * @var QueueJob
     */
    public $jobClass = QueueJob::class;

    /**
     * @var QueueJobExec
     */
    public $jobExecClass = QueueJobExec::class;

    /**
     * @var QueueJobExec
     */
    private $jobExec;

    /**
     * @param $data
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function saveJobRecord($data): void
    {
        $condition = ['queue' => $data['queue'], 'job_id' => $data['job_id']];
        /** @var QueueJob $job */
        $job = $this->jobClass::findOne($condition) ?: Yii::createObject($this->jobClass);
        $job->setAttributes($data);
        $job->save(false);
    }

    /**
     * @param $data
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function saveJobExecRecord($data): void
    {
        //if queue run with isolate mode
        //when it trigger error with child process error like timeout or Uncaught like compile
        //it will already triggered beforeExec in child process but trigger afterError in parent process
        //so jobExec property will be null
        if ($this->jobExec === null && $data['status'] === ExecStatusConst::EXEC_STATUS_FAILED) {
            $condition = [
                'job_id' => $data['job_id'],
                'queue' => $data['queue'],
                'worker_pid' => $data['worker_pid'],
                'status' => ExecStatusConst::EXEC_STATUS_RUNNING,
                'finished_at' => 0,
            ];
            $this->jobExec = $this->jobExecClass::findOne($condition);
        }
        if ($this->jobExec === null) {
            $this->jobExec = Yii::createObject($this->jobExecClass);
        }
        $this->jobExec->setAttributes($data);
        $this->jobExec->save(false);

        if (isset($data['finished_at'])) {
            $this->jobExec = null;
        }
    }

    /**
     * @param $condition
     * @return mixed|void
     * @inheritdoc
     */
    protected function deleteJob($condition): void
    {
        $this->jobClass::deleteAll($condition);
    }

    /**
     * @param $condition
     * @return mixed|void
     * @inheritdoc
     */
    protected function deleteJobExec($condition): void
    {
        $this->jobExecClass::deleteAll($condition);
    }
}

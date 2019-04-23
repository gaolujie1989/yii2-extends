<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\queuing\monitor;

use lujie\queuing\models\QueueJob;
use lujie\queuing\models\QueueJobExec;
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
    public $jobClass;

    /**
     * @var QueueJobExec
     */
    public $jobExecClass;

    /**
     * @var QueueJobExec
     */
    private $jobExec;

    /**
     * @param $data
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function saveJobRecord($data)
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
    protected function saveJobExecRecord($data)
    {
        if (!$this->jobExec) {
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
    protected function deleteJob($condition)
    {
        $this->jobClass::deleteAll($condition);
    }

    /**
     * @param $condition
     * @return mixed|void
     * @inheritdoc
     */
    protected function deleteJobExec($condition)
    {
        $this->jobExecClass::deleteAll($condition);
    }
}

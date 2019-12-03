<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\queuing\monitor\behaviors;

use lujie\queuing\monitor\models\QueueWorker;
use Yii;
use yii\queue\cli\WorkerEvent;

/**
 * Class ActiveRecordWorkerMonitorBehavior
 * @package lujie\queuing\monitor
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveRecordWorkerMonitorBehavior extends BaseWorkerMonitorBehavior
{
    /**
     * @var QueueWorker
     */
    public $workerClass = QueueWorker::class;

    /**
     * @var QueueWorker
     */
    private $worker;

    /**
     * @param $data
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function saveWorkerRecord($data): void
    {
        if ($this->worker === null) {
            $this->worker = Yii::createObject($this->workerClass);
        }
        $this->worker->setAttributes($data);
        try {
            $this->worker->save(false);
        } catch (\Exception $e) {
            $message = strtr('Save worker record failed. [probably reason: db connection lost]. [ex: {ex}]', [
                '{ex}' => $e->getMessage(),
            ]);
            Yii::warning($message, __METHOD__);
        }
    }

    /**
     * @param bool $success
     * @inheritdoc
     */
    public function updateCount($workerPid, $success = true): void
    {
        $worker = $this->workerClass::findOne(['pid' => $workerPid, 'finished_at' => 0]);
        if ($worker === null) {
            $message = strtr('Worker pid: {pid} not exists.', ['pid' => $workerPid]);
            Yii::info($message, __METHOD__);
            return;
        }
        $countAttribute = $success ? 'success_count' : 'failed_count';
        $worker->updateCounters([$countAttribute => 1]);
        $message = strtr('Worker pid: {pid} update counters.', ['pid' => $workerPid]);
        Yii::info($message, __METHOD__);
    }

    /**
     * @param WorkerEvent $event
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function workerStop(WorkerEvent $event): void
    {
        if (!$this->listenWorkerLoop) {
            //if not listen worker loop
            $connection = $this->workerClass::getDb();
            if (method_exists($connection, 'close')) {
                $connection->close();
            }
        }
        parent::workerStop($event);
    }
}

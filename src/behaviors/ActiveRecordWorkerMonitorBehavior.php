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
     * @param array $data
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function saveWorkerRecord(array $data): void
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
     * @param int $workerPid
     * @param bool $success
     * @inheritdoc
     */
    public function updateCount(int $workerPid, $success = true): void
    {
        /** @var QueueWorker $worker */
        $worker = $this->workerClass::find()
            ->andWhere(['pid' => $workerPid, 'finished_at' => 0])
            ->orderBy(['queue_worker_id' => SORT_DESC])
            ->one();
        if ($worker === null) {
            $message = strtr('Worker pid: {pid} not exists.', ['{pid}' => $workerPid]);
            Yii::info($message, __METHOD__);
            return;
        }
        $countAttribute = $success ? 'success_count' : 'failed_count';
        $worker->updateCounters([$countAttribute => 1]);
        $message = strtr('QueueWorkerID: {id} update counters.', ['{id}' => $worker->queue_worker_id]);
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
